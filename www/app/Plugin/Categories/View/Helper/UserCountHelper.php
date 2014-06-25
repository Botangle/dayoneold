<?php
class UserCountHelper extends AppHelper {

    /**
     * @param $name string
     * @return int
     */
    public function getUserCount($name) {
        App::import("Model", "Users.User");
        $userCount = new User();
        return $userCount->find('count',array('conditions'=>array('subject LIKE'=>'%'.$name.'%')));
    }
}
