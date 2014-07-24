<?php
App::uses('CakeEmail', 'Network/Email');
App::uses('UsersAppController', 'Users.Controller');

/**
 * Users Application controller
 *
 * @category Controllers
 * @package  Croogo.Users.Controller
 * @since    1.5
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class PostLessonAddController extends UsersAppController {

    // @TODO: UGLY.  Can we *please* move this into a different setup long-term?  In the meantime, things at least work :-/

    /**
     * Called so we can complete adding a lesson to our system as needed
     */
    protected function postLessonAddSetup($lesson_id, $user_id_to_message) {
        // @TODO: do we want to do any type of checking to see if there were problems along the way?
        $this->addLessonMessage($user_id_to_message);

        // and then we want to email the appropriate person as well
        $this->sendLessonProposal($lesson_id, $user_id_to_message);

        // if a student is proposing a lesson and the student hasn't just needed to setup billing
        // we'll go ahead and generate lesson session ids so our lessons work (and so the expert doesn't have to wait as long)
        if (isset($this->request->data['Lesson']) && $this->request->data['Lesson']['student_view'] == 1) {
            $data = array();
            $data['Lesson']['id'] = (int) $lesson_id;

            // generate our appropriate session ids
            if ($returnVal = $this->generateTwiddlaSessionId()) {
                $data['Lesson']['twiddlameetingid'] = $returnVal;
            }
            if ($returnVal = $this->generateOpenTokSessionId()) {
                $data['Lesson']['opentok_session_id'] = $returnVal;
            }

            // @TODO: eventually, we should check to make sure saving actually doesn't have errors instead of just assuming :-/
            $this->Lesson->save($data);
        }

        $message = __d('croogo', 'Your lesson has been added successfully.');

        // if we're being called via Ajax, then it's related to our mobile billing system page
        // and we need to send back a JSON message
        if ($this->request->is('ajax')) {
            $this->sendJsonSuccess($message);
        } else {
            // otherwise send a flash message so they can see it on page reload
            $this->Session->setFlash($message, 'default', array('class' => 'success'));
            $this->redirect(array(
                    'plugin' => 'users',
                    'controller' => 'users',
                    'action' => 'lessons',
                ));
        }
    }

    /**
     * Ideally we might make this an action and redirect, but at the moment our actions are a bit messy with ACL issues
     * so we'll do this here for now
     *
     * @param integer $user_id_to_message
     */
    protected function addLessonMessage($user_id_to_message) {
        $data = array();
        $data['Usermessage']['sent_from'] = $this->Auth->user('id');
        $data['Usermessage']['sent_to'] = $user_id_to_message;
        $data['Usermessage']['readmessage'] = 0;
        $data['Usermessage']['date'] = date('Y-m-d H:i:s');
        $data['Usermessage']['body'] = " Our Lesson is setup now. Please click here to read."; // @TODO: fix the body so it's clickable
        $data['Usermessage']['parent_id'] = 0;

        App::import('Model', 'Users.UserMessage');
        $userMessage = new UserMessage;
        $userMessage->save($data);
        $lastId = $userMessage->getLastInsertId();

// @TODO: what were we planning to do with this line?  parent_id is always hard-coded to zero above ...
//        if ($this->request->data['Usermessage']['parent_id'] == 0) {
        $userMessage->query(" UPDATE `usermessages` SET parent_id = '" . $lastId . "' WHERE id = '" . $lastId . "'");
//        }
    }

    /**
     * Sends a proposed lesson email to the receipient in addition to a message through the system
     *
     * @TODO: move this to an event listener library instead of having it in our controller
     *
     * @param $lesson_id
     * @param $user_id
     * @return bool
     */
    protected function sendLessonProposal($lesson_id, $user_id) {
        App::import('Model', 'Users.Lesson');
        $lessonModel = new Lesson;

        $lesson = $lessonModel->find('first', array('conditions' => array('Lesson.id' => $lesson_id)));

        if (count($lesson) == 0) {
            $this->log("Couldn't properly retrieve lesson information prior to sending an email notification about a new lesson.", LOG_EMERG);
            return;
        }

        App::import('Model', 'Users.User');
        $userModel = new User;

        if ($lesson['Lesson']['student'] == $user_id) {
            $contact = $userModel->find('first', array('conditions' => array('User.id' => $lesson['Lesson']['student'])));
            $lessonRequestor = $userModel->find('first', array('conditions' => array('User.id' => $lesson['Lesson']['tutor'])));
        } else {
            $contact = $this->User->find('first', array('conditions' => array('User.id' => $lesson['Lesson']['tutor'])));
            $lessonRequestor = $userModel->find('first', array('conditions' => array('User.id' => $lesson['Lesson']['student'])));
        }

        if (count($contact) == 0 || count($lessonRequestor) == 0) {
            $this->log("Couldn't retrieve tutor / student info prior to sending an email notification about a new lesson.", LOG_EMERG);
            return;
        }

        // @TODO: make sure these variables are vetted for security, people could attack each other this way too
        $emailLessonData = array(
            'contactName' => $contact['User']['name'],
            'date' => $lesson['Lesson']['lesson_date'], // @TODO: make sure these are stored in UTC on the server ...
            'time' => $lesson['Lesson']['lesson_time'],
            'subject' => $lesson['Lesson']['subject'],
            'notes' => $lesson['Lesson']['notes'],
            'requestor' => array(
                'fullName' => $lessonRequestor['User']['name'] . ' ' . $lessonRequestor['User']['lname'],
                'id' => $lessonRequestor['User']['id'],
                'name' => $lessonRequestor['User']['name'],
                'username' => $lessonRequestor['User']['username'],
            ),
        );

        // send out an email saying that we've got a new lesson request that they should take a look at
        // and give them some details on it so they know what is being requested
        return $this->_sendEmail(
            array(Configure::read('Site.title'), $this->_getSenderEmail()), $contact['User']['email'], __d('croogo', '[%s] New Lesson Proposed', 'Botangle'), 'Users.new_lesson', // @TODO: this and the next option down need work
            'new lesson proposal', $this->theme, compact('emailLessonData')
        );
    }

    /**
     * Generates some session ids we need to actually run a lesson
     *
     * @TODO: It'd be great to move all of this to a separate helper model so we're not cluttering our controller as much
     * Long-term, let's do that
     * @param $lessonId
     */
    protected function generateTwiddlaSessionId() {

        // generate our twiddla id ahead of time
        $this->Twiddla = $this->Components->load('Twiddla', Configure::read('TwiddlaComponent'));

        // @TODO: we should add some Twiddla error handling stuff here too
        return $this->Twiddla->getMeetingId();
    }

    /**
     * Generates an OpenTok session id that we can save to the DB or returns false
     *
     * @return boolean|string
     */
    protected function generateOpenTokSessionId() {
        $this->OpenTok = $this->Components->load('OpenTok', Configure::read('OpenTokComponent'));
        return $this->OpenTok->generateSessionId();
    }

    /**
     * Convenience method to send email
     *
     * @param string $from Sender email
     * @param string $to Receiver email
     * @param string $subject Subject
     * @param string $template Template to use
     * @param string $theme Theme to use
     * @param array $viewVars Vars to use inside template
     * @param string $emailType user activation, reset password, used in log message when failing.
     * @return boolean True if email was sent, False otherwise.
     */
    protected function _sendEmail($from, $to, $subject, $template, $emailType, $theme = null, $viewVars = null) {
        if (is_null($theme)) {
            $theme = $this->theme;
        }
        $success = false;

        try {
            $email = new CakeEmail();
            $email->from($from[1], $from[0]);
            $email->to($to);
            $email->subject($subject);
            $email->template($template);
            $email->viewVars($viewVars);
            $email->theme($theme);
            $success = $email->send();
        } catch (SocketException $e) {
            $this->log(sprintf('Error sending %s notification : %s', $emailType, $e->getMessage()));
        }

        return $success;
    }

    protected function _getSenderEmail() {
        return 'croogo@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
    }
}
