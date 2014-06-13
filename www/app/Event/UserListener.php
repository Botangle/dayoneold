<?php
/**
 * UserListener.php
 *
 * @author: David Baker <dbaker@acorncomputersolutions.com
 * Date: 6/13/14
 * Time: 7:34 AM
 */

// based heavily on http://martinbean.co.uk/blog/2013/11/22/getting-to-grips-with-cakephps-events-system/

App::uses('CakeEventListener', 'Event');

class UserListener implements CakeEventListener {

    public function implementedEvents() {
        return array(
            'Controller.Users.loginSuccessful' => 'login',
            'Controller.Users.afterLogout' => 'logout',
        );
    }

    public function login($event) {
        $userController = $event->subject();

        $values = array(
            'type' => 'login',
            'user_id' => (int)$userController->Session->read('Auth.User.id'),
        );

        $this->handleSaving($values);
    }

    public function logout($event) {
        $userController = $event->subject();

        $values = array(
            'type' => 'logout',
            'user_id' => (int)$userController->request->data['User']['id'],
        );

        $this->handleSaving($values);
    }

    private function handleSaving($values)
    {
        $this->UserLog = ClassRegistry::init('UserLog');

        try {
            $this->UserLog->save($values, false); // we don't care about validation, let's just do this fast
        } catch(Exception $e) {
            // fail silently, we don't care
        }
    }
}