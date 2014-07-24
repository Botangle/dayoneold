<?php
/**
 * TransactionListener.php
 *
 * @author: David Baker <dbaker@acorncomputersolutions.com
 * Date: 7/23/14
 * Time: 8:08 PM
 */

// based heavily on http://martinbean.co.uk/blog/2013/11/22/getting-to-grips-with-cakephps-events-system/

App::uses('CakeEventListener', 'Event');

class TransactionListener implements CakeEventListener {

    public function implementedEvents() {
        return array(
            'Transaction.handle_purchase' => 'handlePurchase',
            'Transaction.handle_sale' => 'handleSale',
        );
    }

    /**
     * Handle a purchase through Braintree
     *
     * @param $event
     */
    public function handlePurchase(CakeEvent $event)
    {
        $amount = $event->data['amount'];
        $nonce = $event->data['nonce'];
        $user = $event->data['customer'];

        $result = Braintree_Transaction::sale(array(
                'amount'                => $amount,
                'paymentMethodNonce'    => $nonce,
                'customer' => array(
                    'firstName'     => $user['name'], // argh, I'm going to be so glad when we can clean this up :-(
                    'lastName'      => $user['lname'],
                    'email'         => $user['email'],
                ),
            ));

        // or update the transaction_key of our $event->subject() with the info we get back from Braintree
        if($result->success) {
            $ourTransaction = $event->subject();
            $ourTransaction->data['Transaction']['transaction_key'] = $result->transaction->id;

            // now let's try to submit for settlement, as we've provided the good that we wanted to provide (our credits)
            $result = Braintree_Transaction::submitForSettlement($result->transaction->id);
            if($result->success) {
                return;
            } else {
                // @TODO: we'll let this go through for now, but long-term, we'd like to setup an auto-retry to ensure that settlements go through
            }
        } else {
            // otherwise, stop everything
            $event->stopPropagation();
        }
    }

    /**
     * Handle a sale through Paypal (which means the person we send money to pays the Paypal fees)
     *
     * @param $event
     */
    public function handleSale(CakeEvent $event)
    {
        $config = array(
            'mode'              => Configure::read('Paypal.mode'),
            'acct1.UserName'    => Configure::read('Paypal.username'),
            'acct1.Password'    => Configure::read('Paypal.password'),
            'acct1.Signature'   => Configure::read('Paypal.signature'),
        );

        $receiver = new PayPal\Types\AP\Receiver();
        $receiver->email    = $event->data['email'];
        $receiver->amount   = $event->data['amount'];

        $receiverList = new PayPal\Types\AP\ReceiverList(array(
            $receiver,
        ));

        // this doesn't need to be a completely legit value, as it isn't needed for implicit payments
        $cancelUrl = 'https://www.botangle.com';

        // this doesn't need to be a completely legit value, as it isn't needed for implicit payments
        $returnUrl = 'https://www.botangle.com';

        $payRequest = new PayPal\Types\AP\PayRequest(
            new PayPal\Types\Common\RequestEnvelope("en_US"),
            'PAY',
            $cancelUrl,
            // specifying this in USD is fine
            // https://developer.paypal.com/docs/classic/api/adaptive-payments/Pay_API_Operation/#id098QF000M7Q__id092NN06105Z
            'USD',
            $receiverList,
            $returnUrl
        );

        // now we specify senderEmail address
        // doing so turns this into an implicit payment, which doesn't need visible browser auth from the user
        $payRequest->senderEmail = Configure::read('Paypal.senderEmail');

        try {
            $service = new PayPal\Service\AdaptivePaymentsService($config);
            $response = $service->Pay($payRequest);

            // if successful, update the transaction_key of our $event->subject() with the info we get back from Paypal
            if(strtoupper($response->responseEnvelope->ack == 'SUCCESS')) {

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
                if($success && isset($response->paymentInfoList->paymentInfo)) {
                    $ourTransaction = $event->subject();
                    $ourTransaction->data['Transaction']['transaction_key'] = $response->paymentInfoList->paymentInfo['transactionId'];

                    return;
                } else {
                    // otherwise, stop everything
                    $event->stopPropagation();
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
                $ourTransaction = $event->subject();
                if($response->error[0]->errorId == 520002) {
                    CakeLog::error('Charge::PayPal_Error: PayPal could be down: ' . $response->error[0]->message, 'paypal');
                    $ourTransaction->validationErrors['api'] = 'PayPal could be down: ' . $response->error[0]->message . ' (#' . $response->error[0]->errorId . '). Please try again or contact support.';
                } else {
                    CakeLog::error('Charge::PayPal_Error: PayPal error: ' . $response->error[0]->message . '(#' . $response->error[0]->errorId . ')', 'paypal');
                    $ourTransaction->validationErrors['api'] = 'We had issues with PayPal: ' . $response->error[0]->message . ' (#' . $response->error[0]->errorId . '). Please try again or contact support.';
                }
            }
        } catch (Exception $e) {
            CakeLog::error('Charge::PayPal_Error: ' . $e->getMessage(), 'paypal');
        }
        // otherwise, stop everything
        $event->stopPropagation();
    }
}