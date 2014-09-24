<?php
/*
 * UserCreditController
 */

class UserCreditController extends BaseController {

    /**
     * Loads the filters for the controller actions
     */
    public function __construct()
    {
        $this->beforeFilter('auth');
    }

    public function getIndex()
    {
        $user = Auth::user();
        if ($user->transactions->count() == 0){
            return Redirect::route('transaction.buy');
        }

        $canSell = false;
        $token = null;
// @TODO: once we have worked out how we want to handle sales, we'll turn this back on.  Right now, Paypal doesn't want to allow
// us to hold money and pay out when people ask for it, so we're going to do it manually instead.  Emails will be sent
// to contactus@botangle.com and Erik will manually run the transaction.  We'll then need to update the database on production
// We're shifting to a quite manual process, but it will mean we don't pour as much money into something that isn't making any money
//        if(Config::get('services.paypal.appId') != '') {
//            $canSell = true;
//            $token = Transaction::generateBraintreeToken();
//        }

        return View::make('user.credits', array(
                'user'  => $user,
                'enableCreditSales' => $canSell,
                'token' => $token,
            ));
    }
}
