<?php
/*
 * TransactionController Controller
 */

class TransactionController extends BaseController {


    protected function generateBraintreeToken()
    {
        // sandbox or production
        Braintree_Configuration::environment(Config::get('services.braintree.mode'));

        // set other items as well
        Braintree_Configuration::merchantId(Config::get('services.braintree.merchantId'));
        Braintree_Configuration::publicKey(Config::get('services.braintree.publicKey'));

        // set the Braintree private key
        $key = Config::get('services.braintree.privateKey');
        if (!$key) {
            throw new Exception('Braintree private key is not set.');
        }
        Braintree_Configuration::privateKey($key);
        return Braintree_ClientToken::generate();
    }

    public function getBuy()
    {
        return View::make('transactions.buy', array(
                'refillNeeded'  => Session::has('credit_refill_needed'),
                'token' => $this->generateBraintreeToken(),
            ));

    }

    public function postBuy()
    {
        $inputs = Input::all();
        $inputs['user_id'] = (int)Auth::user()->id;

        // TODO: Validate the nonce?

        // adjust our payment nonce if we're in debugging mode so we can
        // make purchases without really triggering a Braintree purchase
        if(Config::get('app.debug')) {
            $inputs['nonce'] = Braintree_Test_Nonces::$paypalOneTimePayment;
        }

        $transaction = new Transaction;
        $transaction->fill($inputs);

        $errorMsg   = 'Something strange happened.  Please try again.';

        if($transaction->validate()) {

            $amount     = (int)$transaction->amount;
            $successMsg = trans("You have purchased {$amount} Botangle credits successfully");
            $errorMsg   = trans("We had problems making that purchase.  Please try again.");

            if($transaction->addBuy()) {

                // Now let's see if we're in the middle of a lesson setup that got interrupted by the need for a credit refill
                // if we are, we'll need to finish up our lesson setup
                if (Session::has('credit_refill_needed')) {

                    // initialize opentok and twiddla ready for the lesson now that payment has been made
                    $transaction->lesson->prepareLessonTools();

                    // clear up our session so we don't have anything else weird happen to this person later on
                    Session::forget('credit_refill_needed');

                    return Redirect::route('user.lessons')
                        ->with('flash_success', "Lesson added. ". $successMsg);
                } else {
                    return Redirect::route('user.credit')
                        ->with('flash_success', $successMsg);

                }
            }
        }
        return Redirect::back()
            ->withInput($inputs)
            ->with('flash_error', $errorMsg)
            ->withErrors($transaction->errors());
    }

    public function postSell()
    {
        $inputs = Input::all();
        $inputs['user_id'] = (int)Auth::user()->id;

        $transaction = new Transaction;
        $transaction->fill($inputs);

        $errorMsg   = 'Something strange happened.  Please try again.';

        if($transaction->validate()) {

            $amount     = number_format($transaction->amount, 2);
            $successMsg = trans("You have sold {$amount} Botangle credits successfully");
            $errorMsg   = trans("We had problems making that sale.  Please try again.");

            if($transaction->addSell()) {
                return Redirect::route('user.credit')
                    ->with('flash_success', $successMsg);
            }
        }
        return Redirect::back()
            ->withInput($inputs)
            ->with('flash_error', $errorMsg)
            ->withErrors($transaction->errors());
    }
}