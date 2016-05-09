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

        return View::make('user.credits', array(
                'user'  => $user,
                'enableCreditSales' => $canSell,
                'token' => $token,
            ));
    }
}
