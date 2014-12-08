<?php
/**
 * TransactionHandler.php
 *
 * @author: Martyn Ling <mling@str8-4ward.com>
 * Adapted from TransactionListener.php which was part of the Botangle CakePHP project
 * that David Baker <dbaker@acorncomputersolutions.com added Paypal payments into.
 * Date: 9/17/14
 * Time: 10:43AM
 */

class TransactionHandler {

    /**
     * @param Transaction $transaction
     * @return bool
     */
    public function onPurchase(Transaction $transaction)
    {
        $transactionDetails = array(
            'amount'                => $transaction->amount,
            'paymentMethodNonce'    => $transaction->nonce,
            'customer' => array(
                'firstName'     => $transaction->user->name, // argh, I'm going to be so glad when we can clean this up :-(
                'lastName'      => $transaction->user->lname,
                'email'         => $transaction->user->email,
            ),
        );
        Log::info("Braintree payment attempt: ". json_encode($transactionDetails));

        // TODO: INVESTIGATE
        // The config has already been set when creating the client token, but for some reason on the
        // dev server, the config is being forgotten/reset. It works fine on local dev.
        // So, to remove this launch blocking bug, we'll set the config again.
        Transaction::setBraintreeConfig();
        try {
            $result = Braintree_Transaction::sale($transactionDetails);
        } catch(Exception $e){
            return $this->logBraintreeError($transaction, get_class($e), $e->getMessage());
        }

        // update the transaction_key of our Transaction with the info we get back from Braintree
        if($result->success) {
            Log::info('Braintree_Transaction::sale success (Botangle transaction id: '. $transaction->id .')');
            $transaction->transaction_key = $result->transaction->id;

            // now let's try to submit for settlement, as we've provided the goods that we want to provide (our credits)
            $result = Braintree_Transaction::submitForSettlement($result->transaction->id);
            if($result->success) {
                Log::info('Braintree_Transaction::submitForSettlement success');
                return true;
            } else {
                Log::error("Braintree::submitForSettlement failed. Response data: ". json_encode($result));
                // @TODO: we'll let this go through for now, but long-term, we'd like to setup an auto-retry to ensure that settlements go through
            }
        } else {
            // otherwise, stop everything
            $transaction->errors()->add('user_id', 'Purchase failed. Please try again or contact support. Details below:');
            $code = $result->transaction->processorResponseCode;
            $message = $result->transaction->processorResponseText;
            $transaction->errors()->add('user_id', "Error: $code $message");
            Log::error("Braintree::sale failed. Response data: ". json_encode($result));
            return false;
        }
    }

    /**
     * @param Transaction $transaction
     * @return bool
     */
    public function onSale(Transaction $transaction)
    {
        $config = array(
            'mode'              => Config::get('services.paypal.mode'),
            'acct1.UserName'    => Config::get('services.paypal.username'),
            'acct1.Password'    => Config::get('services.paypal.password'),
            'acct1.Signature'   => Config::get('services.paypal.signature'),
            "acct1.AppId"       => Config::get('services.paypal.appId'),
        );

        $payRequest = new PayPal\Types\AP\PayRequest();

        $receiver = new PayPal\Types\AP\Receiver();
        $receiver->email    = $transaction->paypal_email_address;
        $receiver->amount   = $transaction->amount;
        $receiverList = new PayPal\Types\AP\ReceiverList(array(
            $receiver,
        ));
        $payRequest->receiverList = $receiverList;

        $requestEnvelope = new PayPal\Types\Common\RequestEnvelope("en_US");
        $payRequest->requestEnvelope = $requestEnvelope;
        $payRequest->actionType = "PAY";
        $payRequest->cancelUrl = 'https://www.botangle.com';
        $payRequest->returnUrl = "https://www.botangle.com";
        $payRequest->currencyCode = "USD";

        // now we specify senderEmail address
        // doing so turns this into an implicit payment, which doesn't need visible browser auth from the user
        $payRequest->senderEmail = Config::get('services.paypal.senderEmail');

        try {
            $service = new PayPal\Service\AdaptivePaymentsService($config);
            $response = $service->Pay($payRequest);

            // if successful, update the transaction_key of our $event->subject() with the info we get back from Paypal
            if(strtoupper($response->responseEnvelope->ack) == 'SUCCESS') {

                $success = false;

                // this may be a bit of a naive setup, but according to the docs, implicit payments
                // should always end up being complete
                switch($response->paymentExecStatus) {
                    case 'COMPLETED':
                        $success = true;
                        break;
                    default:
                        break;
                }

                // if we have success, then let's record the transaction id and return
                if($success) {
                    $transaction->transaction_key = $response->responseEnvelope->correlationId;

                    return true;
                } else {
                    // otherwise, stop everything
                    $transaction->errors()->add('paypal_email_address', 'Paypal payment failed. Please try again or contact support.');
                    Log::error('TransactionHandler::handleSale failed with no error message. Response data: '. json_encode($response));
                    return false;
                }
            } else {
                /**
                 * $response->error[0]
                 * -> errorId (520002)
                 * -> domain (PLATFORM)
                 * -> subdomain (Application)
                 * -> severity (Error)
                 * -> category (Application)
                 * -> message (Internal Error)
                 */
                if($response->error[0]->errorId == 520002) {
                    Log::error('Charge::PayPal_Error: PayPal could be down: ' . $response->error[0]->message);
                    $transaction->errors()->add('paypal_email_address', 'PayPal could be down: ' . $response->error[0]->message . ' (#' . $response->error[0]->errorId . '). Please try again or contact support.');
                } else {
                    Log::error('Charge::PayPal_Error: PayPal error: ' . $response->error[0]->message . '(#' . $response->error[0]->errorId . ')');
                    $transaction->errors()->add('paypal_email_address', 'We had issues with PayPal: ' . $response->error[0]->message . ' (#' . $response->error[0]->errorId . '). Please try again or contact support.');
                }
            }
        } catch (Exception $e) {
            $transaction->errors()->add('paypal_email_address', 'We had issues making the payment: ' . $e->getMessage() . '. Please try again or contact support.');
            Log::error('Charge::PayPal_Error: ' . $e->getMessage());
        }
        // otherwise, stop everything
        return false;
    }

    protected function logBraintreeError($transaction, $type, $message = '')
    {
        $transaction->errors()->add('user_id', 'Gateway error. Please try again or contact support.');
        Log::error("Braintree::sale failed. $type\n$message");
        return false;
    }
}
