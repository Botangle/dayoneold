<?php
/*
 * TransactionController Controller
 */

class TransactionController extends BaseController {

    /**
     * Loads the filters for the controller actions
     */
    public function __construct()
    {
        $this->beforeFilter('auth');

        $this->beforeFilter('csrf', array('on' => 'post'));
    }

    public function getBuy()
    {
        return View::make('transaction.buy', array(
                'refillNeeded'  => Session::has('credit_refill_needed'),
                'token' => Transaction::generateBraintreeToken(),
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
            $errorMsg   = trans("We had problems making that purchase.  Please try again or contact support.");

            if($transaction->addBuy()) {

                // Now let's see if we're in the middle of a lesson setup that got interrupted by the need for a credit refill
                // if we are, we'll need to finish up our lesson setup
                if (Session::has('credit_refill_needed') && Session::has('new_lesson_id')) {

                    // transactions of type buy do not have a related lesson, so we need to pull that from session
                    $lesson = Lesson::find(Session::get('new_lesson_id'));

                    // clear up our session so we don't have anything else weird happen to this person later on
                    Session::forget('credit_refill_needed');
                    Session::forget('new_lesson_id');

                    if ($lesson){
                        // initialize opentok and twiddla ready for the lesson now that payment has been made
                        $lesson->prepareLessonTools();

                        return Redirect::route('user.lessons')
                            ->with('flash_success', "Lesson added. ". $successMsg);

                    }
                }

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
