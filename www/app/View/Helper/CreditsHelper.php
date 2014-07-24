<?php
App::uses('AppHelper', 'View/Helper');

class CreditsHelper extends AppHelper {

    private $_balanceCache;

	function getBalance($userId){

        if(!isset($this->_balanceCache)) {
            App::import("Model", "UserCredit");
            $model = new UserCredit();
            $conditions = "user_id = ". (int)$userId;
            $count = $model->find('first', array('conditions' => $conditions));

            // @TODO: cache this amount so we don't have to query for it every time
            if(isset($count['UserCredit']['amount'])) {
                $this->_balanceCache = $count['UserCredit']['amount'];
            } else {
                $this->_balanceCache = 0;
            }
        }

        return $this->_balanceCache;
    }
}
