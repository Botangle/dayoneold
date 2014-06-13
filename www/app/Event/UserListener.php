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
            'Controller.Users.studentStripeAccountSetup' => 'stripeAccountSetup',
            'Controller.Users.tutorStripeAccountSetup' => 'stripeAccountSetup',
            'Controller.Users.lessonAdded' => 'lessonAdded',
        );
    }

    // 			Croogo::dispatchEvent('Controller.Users.beforeAdminLogin', $this);


    public function login($event)
    {
        $this->handleSaving($event, 'login');
    }

    public function logout($event)
    {
        $userController = $event->subject();
        $this->handleSaving($event, 'logout', $userController->request->data['User']['id']);
    }

    public function stripeAccountSetup($event)
    {
        $this->handleSaving($event, 'stripe-setup');
    }

    public function lessonAdded($event)
    {
        $this->handleSaving($event, 'lesson-added');
    }

    private function handleSaving($event, $code, $userId = null)
    {
        try {
            $userController = $event->subject();
            $userId = ($userId) ? $userId : $userController->Session->read('Auth.User.id');

            $values = array(
                'type' => $code,
                'user_id' => (int)$userId,
            );

            $this->UserLog = ClassRegistry::init('UserLog');

            $this->UserLog->save($values, false); // we don't care about validation, let's just do this fast
        } catch(Exception $e) {
            try {
                // write down info about our user retention issues
                CakeLog::alert("Had trouble while saving user retention information: " . $e->getMessage());
            } catch(Exception $e) {
                // fail silently, we won't try to handle an error here
            }
        }
    }
}