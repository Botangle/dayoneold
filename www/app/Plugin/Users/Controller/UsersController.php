<?php

App::uses('CakeEmail', 'Network/Email');
App::uses('UsersAppController', 'Users.Controller');
App::Import('ConnectionManager');

/*
 * Users Controller
 *
 * @category Controller
 * @package  Croogo.Users.Controller
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */

class UsersController extends UsersAppController
{

    /**
     * Components
     *
     * @var array
     * @access public
     */
    public $components = array(
        'Search.Prg' => array(
            'presetForm' => array(
                'paramType' => 'querystring',
            ),
            'commonProcess' => array(
                'paramType' => 'querystring',
                'filterEmpty' => true,
            ),
        ),
    );

    /**
     * Preset Variables Search
     *
     * @var array
     * @access public
     */
    public $presetVars = true;
    public $databaseName = "";
    /**
     * Models used by the Controller
     *
     * @var array
     * @access public
     */
    public $uses = array('Users.User', 'Users.UserRate', 'Users.Lesson', 'Users.Usermessage', 'Users.Review', 'Categories.Category', 'Users.Userpoint', 'Users.LessonPayment');
    public $helper = array('Categories.Category', 'Session');

    function beforeFilter()
    {
        $fields = ConnectionManager::getDataSource('default');
        $dsc = $fields->config;
        $this->databaseName = $dsc['database'];
        parent::beforeFilter();
        $this->Security->validatePost = false;
        $this->Security->csrfCheck = false;

        $this->Security->unlockedActions = array('*');
        $this->Auth->allow('searchstudent', 'calandareventsprofile', 'joinuser', 'lessons_add', 'updateremaining', 'paymentmade', 'claimoffer', 'paymentsetting');
        if ($this->Session->check('Auth.User') && $this->Session->read('Auth.User.role_id') == 4) {
            $this->checkpayment();
        }


    }

    /**
     * Check the student read lesson and payment not made
     * redirect at the payment page
     **/

    function checkpayment()
    {
        $redirect = "";

        if ($this->Session->check('Auth.User') && $this->request->params['action'] != 'paymentnotmade' && $this->request->params['action'] != 'billing' && $this->request->params['action'] != 'logout' && $this->request->params['action'] != 'updateremaining' && $this->request->params['action'] != 'paymentmade') {

            if ($this->Session->read('Auth.User.role_id') == 4) {
                $redirect = $this->LessonPayment->checkduepayment($this->Session->read('Auth.User.id'));

            }
            if (!empty($redirect)) {
                $this->redirect('/users/paymentnotmade/');
            }
        }
    }

    function isAuthorized()
    {
        if ($this->Auth->user('role') != 'admin') {
            $this->Auth->deny('*');
        }

    }

    /**
     * implementedEvents
     *
     * @return array
     */
    public function implementedEvents()
    {
        return parent::implementedEvents() + array(
            'Controller.Users.beforeAdminLogin' => 'onBeforeAdminLogin',
            'Controller.Users.adminLoginFailure' => 'onAdminLoginFailure',
        );
    }

    /**
     * Notify user when failed_login_limit hash been hit
     *
     * @return bool
     */
    public function onBeforeAdminLogin()
    {
        $field = $this->Auth->authenticate['all']['fields']['username'];
        if (empty($this->request->data)) {
            return true;
        }
        $cacheName = 'auth_failed_' . $this->request->data['User'][$field];
        $cacheValue = Cache::read($cacheName, 'users_login');
        if (Cache::read($cacheName, 'users_login') >= Configure::read('User.failed_login_limit')) {
            $this->Session->setFlash(__d('croogo', 'You have reached maximum limit for failed login attempts. Please try again after a few minutes.'), 'default', array('class' => 'error'));
            return $this->redirect(array('action' => $this->request->params['action']));
        }
        return true;
    }

    /**
     * Record the number of times a user has failed authentication in cache
     *
     * @return bool
     * @access public
     */
    public function onAdminLoginFailure()
    {
        $field = $this->Auth->authenticate['all']['fields']['username'];
        if (empty($this->request->data)) {
            return true;
        }
        $cacheName = 'auth_failed_' . $this->request->data['User'][$field];
        $cacheValue = Cache::read($cacheName, 'users_login');
        Cache::write($cacheName, (int)$cacheValue + 1, 'users_login');
        return true;
    }

    /**
     * Admin index
     *
     * @return void
     * @access public
     * $searchField : Identify fields for search
     */
    public function admin_index()
    {
        $this->set('title_for_layout', __d('croogo', 'Users'));
        $this->Prg->commonProcess();
        $searchFields = array('role_id', 'name');

        $this->User->recursive = 0;
        $this->paginate['conditions'] = $this->User->parseCriteria($this->request->query);

        $this->set('users', $this->paginate());
        $this->set('roles', $this->User->Role->find('list'));
        $this->set('displayFields', $this->User->displayFields());
        $this->set('searchFields', $searchFields);

        if (isset($this->request->query['chooser'])) {
            $this->layout = 'admin_popup';
        }
    }

    /**
     * Admin add
     *
     * @return void
     * @access public
     */
    public function admin_add()
    {
        if (!empty($this->request->data)) {

            $this->User->create();
            $this->request->data['User']['activation_key'] = md5(uniqid());
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__d('croogo', 'The User has been saved'), 'default', array('class' => 'success'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__d('croogo', 'The User could not be saved. Please, try again.'), 'default', array('class' => 'error'));
                unset($this->request->data['User']['password']);
            }
        } else {
            $this->request->data['User']['role_id'] = 2; // default Role: Registered
        }
        $roles = $this->User->Role->find('list');
        $this->set(compact('roles'));
    }

    /**
     * Admin edit
     *
     * @param integer $id
     * @return void
     * @access public
     */
    public function admin_edit($id = null)
    {
        if (!empty($this->request->data)) {
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__d('croogo', 'The User has been saved'), 'default', array('class' => 'success'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__d('croogo', 'The User could not be saved. Please, try again.'), 'default', array('class' => 'error'));
            }
        } else {
            $this->request->data = $this->User->read(null, $id);
        }
        $roles = $this->User->Role->find('list');
        $this->set(compact('roles'));
        $this->set('editFields', $this->User->editFields());
    }

    /**
     * Admin reset password
     *
     * @param integer $id
     * @return void
     * @access public
     */
    public function admin_reset_password($id = null)
    {
        if (!$id && empty($this->request->data)) {
            $this->Session->setFlash(__d('croogo', 'Invalid User'), 'default', array('class' => 'error'));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->request->data)) {
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__d('croogo', 'Password has been reset.'), 'default', array('class' => 'success'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__d('croogo', 'Password could not be reset. Please, try again.'), 'default', array('class' => 'error'));
            }
        }
        $this->request->data = $this->User->findById($id);
    }

    /**
     * Admin delete
     *
     * @param integer $id
     * @return void
     * @access public
     */
    public function admin_delete($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__d('croogo', 'Invalid id for User'), 'default', array('class' => 'error'));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->User->delete($id)) {
            $this->Session->setFlash(__d('croogo', 'User deleted'), 'default', array('class' => 'success'));
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash(__d('croogo', 'User cannot be deleted'), 'default', array('class' => 'error'));
            $this->redirect(array('action' => 'index'));
        }
    }

    /**
     * Admin login
     *
     * @return void
     * @access public
     */
    public function admin_login()
    {
        $this->set('title_for_layout', __d('croogo', 'Admin Login'));
        $this->layout = "admin_login";
        if ($this->request->is('post')) {
            Croogo::dispatchEvent('Controller.Users.beforeAdminLogin', $this);
            if ($this->Auth->login()) {
                Croogo::dispatchEvent('Controller.Users.adminLoginSuccessful', $this);
                $this->redirect($this->Auth->redirect());
            } else {
                Croogo::dispatchEvent('Controller.Users.adminLoginFailure', $this);
                $this->Auth->authError = __d('croogo', 'Incorrect username or password');
                $this->Session->setFlash($this->Auth->authError, 'default', array('class' => 'error'), 'auth');
                $this->redirect($this->Auth->loginAction);
            }
        }
    }

    /**
     * Admin logout
     *
     * @return void
     * @access public
     */
    public function admin_logout()
    {
        Croogo::dispatchEvent('Controller.Users.adminLogoutSuccessful', $this);
        $this->Session->setFlash(__d('croogo', 'Log out successful.'), 'default', array('class' => 'success'));
        $this->redirect($this->Auth->logout());
    }

    /**
     * Index
     *
     * @return void
     * @access public
     */
    public function index()
    {


        if (!empty($this->request->data)) {

            if (isset($this->request->data['User']['changepassword']) && $this->request->data['User']['changepassword'] == 'changepasword') {
                $oldpassw = AuthComponent::password($this->data['User']['oldpassword']);
                $user = $this->User->find('first', array(
                    'conditions' => array(
                        'User.id' => $this->request->data['User']['id'],
                    ),
                ));

                if ($oldpassw == $user['User']['password']) {
                    if ($this->User->save($this->request->data)) {
                        $this->Session->setFlash(__d('croogo', 'Password has been reset.'), 'default', array('class' => 'success'));
                        $this->redirect(array('action' => 'index'));
                    } else {
                        $this->Session->setFlash(__d('croogo', 'Password could not be reset. Please, try again.'), 'default', array('class' => 'error'));
                    }
                } else {
                    $this->Session->setFlash(__d('croogo', 'Password could not be reset. Please, try again.'), 'default', array('class' => 'error'));
                }
            } else {

                $filename = null;


                if (!empty($this->request->data['User']['profilepic']['tmp_name']) && is_uploaded_file($this->request->data['User']['profilepic']['tmp_name'])) {

                    $filename = str_replace(" ", "_", basename($this->request->data['User']['profilepic']['name']));
                    $dir = WWW_ROOT . 'uploads' . DS . $this->request->data['User']['id'];
                    $profiledir = WWW_ROOT . 'uploads' . DS . $this->request->data['User']['id'] . DS . "profile";
                    $profiledir = WWW_ROOT . 'uploads' . DS . $this->request->data['User']['id'] . DS . "profile";

                    if (!is_dir($dir)) {
                        mkdir($dir, 0777);
                    }
                    if (!is_dir($profiledir)) {
                        mkdir($profiledir, 0777);
                    }
                    move_uploaded_file(
                        $this->data['User']['profilepic']['tmp_name'],
                        $profiledir . DS . $filename
                    );


                }
                $this->request->data['User']['profilepic'] = $filename;
                $user = $this->User->find('first', array(
                    'conditions' => array(
                        'User.id' => $this->request->data['User']['id'],
                    ),
                ));


                if ($this->User->save($this->request->data)) {
                    $this->Session->setFlash(__d('croogo', 'Information has been updated'), 'default', array('class' => 'success'));
                    $user = $this->User->find('first', array(
                        'conditions' => array('User.id' => $this->request->data['User']['id'])));


                    $this->Session->write('Auth', $user);
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash(__d('croogo', 'Information can not be updated. Please, try again.'), 'default', array('class' => 'error'));
                }
            }

        }

        if ($this->Session->read('Auth.User.role_id') == 4) {
            $this->render('index2');
        }
        $this->set('title_for_layout', __d('croogo', 'Users'));
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
    protected function _sendEmail($from, $to, $subject, $template, $emailType, $theme = null, $viewVars = null)
    {
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

    /**
     * Registration
     *
     * @return void
     * @access public
     */
    public function registration()
    {
        $registrationtype = $this->params['pass'][0];
        $this->Session->write("type", $registrationtype);
        $this->redirect(array('action' => 'add'));
        exit();
    }

    /**
     * Registration
     *
     * @return void
     * @access public
     */
    public function accountsetting()
    {
        if (!empty($this->request->data)) {
            if (isset($this->request->data['User']['posttype']) && $this->request->data['User']['posttype'] == 'pic') {
                $filename = null;
                if (!empty($this->request->data['User']['profilepic']['tmp_name']) && is_uploaded_file($this->request->data['User']['profilepic']['tmp_name'])) {
                    $filename = str_replace(" ", "_", basename($this->request->data['User']['profilepic']['name']));
                    $dir = WWW_ROOT . 'uploads' . DS . $this->request->data['User']['id'];
                    $profiledir = WWW_ROOT . 'uploads' . DS . $this->request->data['User']['id'] . DS . "profile";
                    $profiledir = WWW_ROOT . 'uploads' . DS . $this->request->data['User']['id'] . DS . "profile";

                    if (!is_dir($dir)) {
                        mkdir($dir, 0777);
                    }

                    if (!is_dir($profiledir)) {
                        mkdir($profiledir, 0777);
                    }
                    move_uploaded_file(
                        $this->data['User']['profilepic']['tmp_name'],
                        $profiledir . DS . $filename
                    );


                }
                $this->request->data['User']['profilepic'] = $filename;
                $user = $this->User->find('first', array(
                    'conditions' => array(
                        'User.id' => $this->request->data['User']['id'],
                    ),
                ));

                if ($this->User->save($this->request->data)) {
                    $this->Session->setFlash(__d('croogo', 'Profile Picture has been saved.'), 'default', array('class' => 'success'));
                    $this->redirect(array('action' => 'accountsetting'));
                } else {
                    $this->Session->setFlash(__d('croogo', 'Profile Picture could not be save. Please, try again.'), 'default', array('class' => 'error'));
                }
            } else {
                $oldpassw = AuthComponent::password($this->data['User']['oldpassword']);
                $user = $this->User->find('first', array(
                    'conditions' => array(
                        'User.id' => $this->request->data['User']['id'],
                    ),
                ));


                if ($oldpassw == $user['User']['password']) {

                    if ($this->User->save($this->request->data)) {
                        $this->Session->setFlash(__d('croogo', 'Password has been reset.'), 'default', array('class' => 'success'));
                        $this->redirect(array('action' => 'accountsetting'));
                    } else {
                        $this->Session->setFlash(__d('croogo', 'Password could not be reset. Please, try again.'), 'default', array('class' => 'error'));
                    }
                } else {
                    $this->Session->setFlash(__d('croogo', 'Password could not be reset. Please, try again.'), 'default', array('class' => 'error'));
                }
            }
        }
        if ($this->Session->read('Auth.User.role_id') == 4) {
            $this->render('accountsetting2');
        }
    }

    public function accountsetting2()
    {
        if ($this->Session->read('Auth.User.role_id') == 4) {
            $this->render('accountsetting2');
        }
    }

    /**
     * Add
     *
     * @return void
     * @access public
     */
    public function add()
    {


        $this->set('title_for_layout', __d('croogo', 'Register'));
        if (!empty($this->request->data)) {
            $this->User->create();

            //$this->request->data['User']['role_id'] = 2; // Registered
            $this->request->data['User']['activation_key'] = md5(uniqid());
            $this->request->data['User']['status'] = 0;
            $this->request->data['User']['username'] = htmlspecialchars($this->request->data['User']['username']);
            //$this->request->data['User']['website'] = htmlspecialchars($this->request->data['User']['website']);
            $this->request->data['User']['name'] = htmlspecialchars($this->request->data['User']['name']);
            //$this->User->save($this->request->data)

            if ($this->User->save($this->request->data)) {
                $newUser = $this->User->getLastInsertId();
                // $newUser = 4;
                $trophyamountlesson = "trophy";

                if ($this->Session->check('requestedbyuser')) {
                    if ($newUser < 100) {
                        $requesteduser = $this->User->find('first', array(
                            'conditions' => array(
                                'User.id' => $this->Session->read('requestedbyuser'),

                            ),
                        ));

                        if ($this->Session->check('requestedbyuser')) {
                            if ($requesteduser['User']['role_id'] == 2) {
                                $trophyamountlesson = '5';
                            } else if ($requesteduser['User']['role_id'] == 4) {
                                $trophyamountlesson = 'lesson';
                            }
                        }
                    }


                    $this->request->data['Userpoint']['user_id'] = $newUser;
                    $this->request->data['Userpoint']['point'] = 5;
                    $this->request->data['Userpoint']['date'] = date('Y-m-d H:i:s');
                    $this->request->data['Userpoint']['trophyamountlesson'] = "";
                    $this->request->data['Userpoint']['paid_or_not'] = 0;

                    $this->Userpoint->save($this->request->data);
                    unset($this->request->data['Userpoint']);
                    $this->Userpoint->create();
                    $this->request->data['Userpoint']['user_id'] = $this->Session->read('requestedbyuser');
                    $this->request->data['Userpoint']['point'] = 1;
                    $this->request->data['Userpoint']['date'] = date('Y-m-d H:i:s');
                    $this->request->data['Userpoint']['trophyamountlesson'] = $trophyamountlesson;
                    $this->request->data['Userpoint']['paid_or_not'] = 0;
                    $this->Userpoint->save($this->request->data);
                    $this->Session->delete('requestedbyuser');
                }
                Croogo::dispatchEvent('Controller.Users.registrationSuccessful', $this);
                $this->request->data['User']['password'] = null;

                $this->_sendEmail(
                    array(Configure::read('Site.title'), $this->_getSenderEmail()),
                    $this->request->data['User']['email'],
                    __d('croogo', '[%s] Please activate your account', Configure::read('Site.title')),
                    'Users.register',
                    'user activation',
                    $this->theme,
                    array('user' => $this->request->data)
                );

                $this->Session->setFlash(__d('croogo', 'You have successfully registered an account. An email has been sent with further instructions.'), 'default', array('class' => 'success'));
                $this->redirect(array('action' => 'login'));
            } else {
                Croogo::dispatchEvent('Controller.Users.registrationFailure', $this);
                $this->Session->setFlash(__d('croogo', 'The User could not be saved. Please, try again.'), 'default', array('class' => 'error'));
            }
        }

        $roles = $this->User->Role->find('list');

        $this->set('type', $this->Session->read('type'));
        $this->set(compact('roles'));
    }

    /**
     * Activate
     *
     * @param string $username
     * @param string $key
     * @return void
     * @access public
     */
    public function activate($username = null, $key = null)
    {
        if ($username == null || $key == null) {
            $this->redirect(array('action' => 'login'));
        }

        if ($this->User->hasAny(array(
            'User.username' => $username,
            'User.activation_key' => $key,
            'User.status' => 0,
        ))
        ) {
            $user = $this->User->findByUsername($username);
            $this->User->id = $user['User']['id'];
            $this->User->saveField('status', 1);
            $this->User->saveField('activation_key', md5(uniqid()));
            Croogo::dispatchEvent('Controller.Users.activationSuccessful', $this);
            $this->Session->setFlash(__d('croogo', 'Account activated successfully.'), 'default', array('class' => 'success'));
        } else {
            Croogo::dispatchEvent('Controller.Users.activationFailure', $this);
            $this->Session->setFlash(__d('croogo', 'An error occurred.'), 'default', array('class' => 'error'));
        }

        $this->redirect(array('action' => 'login'));
    }

    /**
     * Edit
     *
     * @return void
     * @access public
     */
    public function edit()
    {
    }

    /**
     * Forgot
     *
     * @return void
     * @access public
     */
    public function forgot()
    {
        $this->set('title_for_layout', __d('croogo', 'Forgot Password'));

        if (!empty($this->request->data) && isset($this->request->data['User']['email'])) {
            $user = $this->User->findByEmail($this->request->data['User']['email']);


            if (!isset($user['User']['id'])) {
                $this->Session->setFlash(__d('croogo', 'Invalid email address.'), 'default', array('class' => 'error'));
                $this->redirect(array('action' => 'login'));
            }

            $this->User->id = $user['User']['id'];
            $activationKey = md5(uniqid());
            $this->User->saveField('activation_key', $activationKey);
            $this->set(compact('user', 'activationKey'));


            $emailSent = $this->_sendEmail(
                array(Configure::read('Site.title'), $this->_getSenderEmail()),
                $user['User']['email'],
                __d('croogo', '[%s] Reset Password', Configure::read('Site.title')),
                'Users.forgot_password',
                'reset password',
                $this->theme,
                compact('user', 'activationKey')
            );

            if ($emailSent) {
                $this->Session->setFlash(__d('croogo', 'If the address you provided is associated with an Botangle account, you will receive an email with a password reset link. If you do not receive this email within five minutes, please check your junk mail folder. If you still cannot locate the email, please reach out to support@botangle.com.'), 'default', array('class' => 'success'));
                $this->redirect(array('action' => 'passwordrecovery'));
            } else {
                $this->Session->setFlash(__d('croogo', 'An error occurred. Please try again.'), 'default', array('class' => 'error'));
            }
        }
    }

    function passwordrecovery()
    {
    }

    /**
     * Reset
     *
     * @param string $username
     * @param string $key
     * @return void
     * @access public
     */
    public function reset($username = null, $key = null)
    {
        $this->set('title_for_layout', __d('croogo', 'Reset Password'));

        if ($username == null || $key == null) {
            $this->Session->setFlash(__d('croogo', 'An error occurred.'), 'default', array('class' => 'error'));
            $this->redirect(array('action' => 'login'));
        }

        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.username' => $username,
                'User.activation_key' => $key,
            ),
        ));
        if (!isset($user['User']['id'])) {
            $this->Session->setFlash(__d('croogo', 'An error occurred.'), 'default', array('class' => 'error'));
            $this->redirect(array('action' => 'login'));
        }

        if (!empty($this->request->data) && isset($this->request->data['User']['password'])) {
            $this->User->id = $user['User']['id'];
            $user['User']['activation_key'] = md5(uniqid());
            $user['User']['password'] = $this->request->data['User']['password'];
            $user['User']['verify_password'] = $this->request->data['User']['verify_password'];
            $options = array('fieldList' => array('password', 'verify_password', 'activation_key'));
            if ($this->User->save($user['User'], $options)) {
                $this->Session->setFlash(__d('croogo', 'Your password has been reset successfully.'), 'default', array('class' => 'success'));
                $this->redirect(array('action' => 'login'));
            } else {
                $this->Session->setFlash(__d('croogo', 'An error occurred. Please try again.'), 'default', array('class' => 'error'));
            }
        }

        $this->set(compact('user', 'username', 'key'));
    }

    /**
     * Login
     *
     * @return boolean
     * @access public
     */
    public function login()
    {
        $this->set('title_for_layout', __d('croogo', 'Log in'));
        if ($this->request->is('post')) {
            Croogo::dispatchEvent('Controller.Users.beforeLogin', $this);

            if ($this->Auth->login()) {
                Croogo::dispatchEvent('Controller.Users.loginSuccessful', $this);;

                $this->request->data['User']['is_online'] = 1;
                $this->request->data['User']['id'] = $this->Session->read('Auth.User.id');
                $_SESSION['userid'] = $this->Session->read('Auth.User.id');
                if ($this->User->save($this->request->data)) {
                }
                $this->redirect($this->Auth->redirect());
            } else {
                Croogo::dispatchEvent('Controller.Users.loginFailure', $this);
                $this->Session->setFlash('The password you entered is incorrect.', 'default', array('class' => 'error'), 'auth');
                $this->redirect($this->Auth->loginAction);
            }
        }
    }

    /**
     * Logout
     *
     * @return void
     * @access public
     */
    public function logout()
    {
        Croogo::dispatchEvent('Controller.Users.beforeLogout', $this);
        $this->Session->setFlash(__d('croogo', 'Log out successful.'), 'default', array('class' => 'success'));
        $this->request->data['User']['is_online'] = 0;
        $this->request->data['User']['id'] = $this->Session->read('Auth.User.id');
        if ($this->User->save($this->request->data)) {
        }
        $this->redirect($this->Auth->logout());
        Croogo::dispatchEvent('Controller.Users.afterLogout', $this);
    }

    /**
     * View
     *
     * @param string $username
     * @return void
     * @access public
     */
    public function view($username = null)
    {
        if ($username == null) {
            $username = $this->Auth->user('username');
        }
        $user = $this->User->findByUsername($username);

        if (!isset($user['User']['id'])) {
            $this->Session->setFlash(__d('croogo', 'Invalid User.'), 'default', array('class' => 'error'));
            $this->redirect('/');
        }

        $this->set('title_for_layout', $user['User']['name']);

        $userRate = $this->UserRate->find('first', array('conditions' => array('UserRate.userid' => $user['User']['id'])));

        $userRating = $this->Review->find('first', array('conditions' => array('Review.rate_to' => $user['User']['id']),
            'fields' => array('avg(rating) as avg'),
        ));
        $userReviews = $this->Review->find('all', array(
            'joins' => array(array(
                'table' => 'users',
                'alias' => 'User',
                'type' => 'INNER',
                'conditions' => array(
                    "User.id = Review.rate_by"
                )),
                array(
                    'table' => 'lessons',
                    'alias' => 'Lesson',
                    'type' => 'left',
                    'conditions' => array(
                        "Lesson.id = Review.lesson_id"
                    ))
            ),


            'fields' => array('*'),
            'conditions' => array('Review.rate_to' => $user['User']['id'])
        ));
        $lessonClasscount = $this->Lesson->find('all', array('conditions' => array('created' => $user['User']['id'], 'is_confirmed' => 1),
                'fields' => array('count(id) as totalrecords,sum(duration) as totalduration'))
        );
        /*
       $log = $this->User->getDataSource()->getLog(false, false);
debug($log);*/

        $this->set(compact('user', 'userRate', 'userRating', 'userReviews', 'lessonClasscount'));


        if ($user['User']['role_id'] == 4) {
            $this->render('view2');
        }
    }

    protected function _getSenderEmail()
    {
        return 'croogo@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
    }

    public function billing()
    {
        App::import("Vendor", "Stripe", array("file" => "stripe/Stripe.php"));
        $id = $this->Session->read('Auth.User.id');
        if (!empty($this->request->data)) {

            if (isset($this->request->data['Billing']['pagetype']) && ($this->request->data['Billing']['pagetype'] == 'billing')) {
                if (isset($this->request->data['Billing']['studentpayemtn']) && $this->request->data['Billing']['studentpayemtn']) {

                    App::import("Vendor", "Stripe", array("file" => "stripe/Stripe.php"));
                    $user = $this->User->find('first', array('conditions' => array('user.id' => $id)));

                    $cartSession = "";

                    $cartSession['fname'] = $this->request->data['Billing']['tutor_id'];
                    $cartSession['lname'] = $_POST['lname'];
                    $cartSession['card'] = $_POST['card'];
                    $cartSession['acc_number'] = $_POST['acc_number'];

                    $cartSession['expiration_month'] = $_POST['expiration_month'];
                    $cartSession['expiration_year'] = $_POST['expiration_year'];;
                    $cartSession['card_security_code'] = $_POST['card_security_code'];
                    /* $cartSession['bill_addressline1'] = $_POST['bill_addressline1'];
                    $cartSession['bill_addressline2'] = $_POST['bill_addressline2'];
                    $cartSession['bill_city'] = $_POST['bill_city'];
                    $cartSession['bill_state'] = $_POST['bill_state'];
                    $cartSession['bill_zip'] = $_POST['bill_zip'];
                    $cartSession['bill_country'] = $_POST['bill_country']; */
                    $cartSession['payamount'] = $_POST['payamount'];
                    $cartSession['paymentCurrency'] = "USD";

                    include('PaypalproController.php');
                    $paypalCont = new PaypalproController();
                    $paypalResponse = $paypalCont->setRequestFields($cartSession, 10);
                    if ($paypalResponse['ACK'] == 'Success') {
                        /* PAYMENT MADE NEXT STEP TO SENT EMAIL ADMIN*/
                        $this->Session->setFlash(__d('croogo', 'Information has been updated'), 'default', array('class' => 'success'));
                        $this->redirect(array('action' => 'billing'));
                    } else {

                        $this->Session->setFlash(__d('croogo', 'Payment could not be made,please try again.'), 'default', array('class' => 'error'));
                    }

                } else {
                    if ($this->UserRate->save($this->request->data)) {
                        $this->Session->setFlash(__d('croogo', 'Information has been updated'), 'default', array('class' => 'success'));
                        $this->redirect(array('action' => 'billing'));
                    } else {
                        $this->Session->setFlash(__d('croogo', 'Information can not be updated. Please, try again.'), 'default', array('class' => 'error'));
                    }
                }
            } else if (isset($this->request->data['User']['pagetype']) && ($this->request->data['User']['pagetype'] == 'paymentsettings')) {
                $user = $this->User->find('first', array('conditions' => array('user.id' => $id)));
                $this->User->id = $user['User']['id'];
                $this->User->saveField('stripe_id', $this->request->data['User']['stripe_id']);
                $this->User->saveField('secret_key', $this->request->data['User']['secret_key']);
                $this->User->saveField('public_key', $this->request->data['User']['public_key']);
                $this->Session->setFlash(__d('croogo', 'Information has been updated'), 'default', array('class' => 'success'));
                $this->redirect(array('action' => 'billing'));
                exit();
            }

        }

        $this->set('ratedata', $this->UserRate->find('first', array('conditions' => array('UserRate.userid' => $this->Session->read('Auth.User.id')))));
        $this->set('title_for_layout', __d('croogo', 'Billing'));


        Stripe::setApiKey("sk_test_XCR1kNc15GsZReu7hKHXFJZ8"); //admin scret key
        if (isset($_GET['code'])) { // Redirect w/ code
            $code = $_GET['code'];
            $token_request_body = array(
                'client_secret' => 'sk_test_XCR1kNc15GsZReu7hKHXFJZ8', //admin secret key
                'client_id' => 'ca_3eUUoTUSZsBg8Ly0TA7XjY3noItr8cgC',
                'code' => $code,
                'grant_type' => 'authorization_code'
            );
            $refreshtoken = "";
            $req = curl_init('https://connect.stripe.com/oauth/token');
            // set url
            curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($req, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($req, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($req, CURLOPT_POST, true);
            curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));
            $respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);
            $resp = json_decode(curl_exec($req), true);
            curl_close($req);
            $refreshtoken = $resp['refresh_token'];
            $this->User->id = $id;
            $this->User->saveField('auth_code', $refreshtoken);
            $this->Session->setFlash(__d('croogo', 'Your Account Connected with Stripe Sucessfully.'), 'default', array('class' => 'success'));
        }
        if ($this->Session->read('Auth.User.role_id') == 4) {
            if ($this->Session->check('paymenttutor')) {
                $userInfo = $this->User->find('list', array('conditions' => array('role_id' => 2, 'status' => 1, 'id' => $this->Session->read('paymenttutor'))));
            } else {
                $userInfo = $this->User->find('list', array('conditions' => array('role_id' => 2, 'status' => 1)));
            }
            $roleid = 2;
            $User = $this->User->find('first', array('conditions' => array('User.id' => $id)));
            $this->set(compact('User'));
            $this->set(compact('userInfo', 'roleid'));

            $this->set('paymentamount', $this->Session->read("paymentamount"));
            $this->render('billing2');
        }

        $User = $this->User->find('first', array('conditions' => array('User.id' => $id)));
        $this->set(compact('User'));
    }

    function billing2()
    {


    }

    public function search($categoryname = null, $online = null)
    {

        $searchValue = isset($this->request->data['searchvalue']) ? $this->request->data['searchvalue'] : "";
        $this->set('title_for_layout', __d('croogo', 'Search User'));


        $this->User->recursive = 0;
        $startExperience = $endExperience = "";
        if (isset($this->request->data['Experience_start']) && $this->request->data['Experience_start'] != "") {
            $startExperience = $this->request->data['Experience_start'];

        }
        if (isset($this->request->data['Experience_end']) && $this->request->data['Experience_end'] != "") {
            $endExperience = $this->request->data['Experience_end'];
        } /**/
        $experienceConditions = "";

        $otherconditions = array("User.status" => 1, "User.role_id" => 2, "User.subject LIKE" => '%' . trim($searchValue) . '%');
        if ($startExperience != "" && $endExperience != "") {
            //echo format('User.teaching_experience',2);
            /*"format(teaching_experience,2) > '".$startExperience."','".$endExperience."'"*/
            /*$otherconditions = array_merge($otherconditions,
                array(
                    'AND' => array(
                        array('format(User.teaching_experience,2) >=' => $startExperience,
                              'format(User.teaching_experience,2) <=' => $endExperience
                             ),

                        )
                )
            );*/
            $otherconditions = array_merge($otherconditions,
                array(
                    'format(User.teaching_experience,2) BETWEEN' => intval($startExperience - 1), intval($endExperience + 1)
                )
            );
        }

        if (isset($online) && $online != null) {
            $otherconditions = array_merge($otherconditions, array('is_online' => 1));
        }

        /*$this->paginate['User']['conditions']  = array("User.status"=>1,"User.role_id"=>2,array('OR'=>array("User.username LIKE '%$searchValue%'","User.extracurricular_interests LIKE '%$searchValue%'","User.subject LIKE '%$searchValue%'","User.qualification LIKE '%$searchValue%'")));*/


        $this->paginate['User']['joins'] = array(array(
            'table' => 'reviews',
            'alias' => 'Review',
            'type' => 'LEFT',
            'conditions' => array(
                "User.id = Review.rate_to"
            ))
        );
        $this->paginate['User']['conditions'] = $otherconditions;
        $this->paginate['User']['fields'] = array('*,avg(`Review`.`rating`) as `rating`');
        $this->paginate['User']['group'] = array('User.id');
        $this->set('users', $this->paginate());

        /*
                $log = $this->User->getDataSource()->getLog(false, false);
        debug($log); die;*/
    }

    public function lessons()
    {
        $userconditionsfield = "tutor";
        $userlessonconditionsfield = "tutor";
        $readconditons = "readlessontutor";

        if ($this->Session->read('Auth.User.role_id') == 4) {
            $userconditionsfield = "created";
            $userlessonconditionsfield = "created";
            $readconditons = "readlesson";
        }

        $activeLessonSQL = "Select * from lessons as Lesson INNER JOIN
		  `$this->databaseName`.`users` AS `User` ON (`User`.`id` = `Lesson`.`$userconditionsfield`) JOIN (SELECT MAX(id) as ids FROM lessons
        GROUP BY parent_id) as newest ON Lesson.id = newest.ids WHERE  `Lesson`.`$userlessonconditionsfield` = '" . $this->Session->read('Auth.User.id') . "' AND Lesson.is_confirmed = 0 AND Lesson.lesson_date >= '" . date('Y-m-d') . "'";
        $activelesson = $this->Lesson->query($activeLessonSQL);


        $upcomingLessonSQL = "Select * from lessons as Lesson INNER JOIN `$this->databaseName`.`users` AS `User` ON (`User`.`id` = `Lesson`.`$userconditionsfield`) JOIN (SELECT MAX(id) as ids FROM lessons
        GROUP BY parent_id) as newest ON Lesson.id = newest.ids WHERE  `Lesson`.`$userlessonconditionsfield` = '" . $this->Session->read('Auth.User.id') . "'  AND Lesson.is_confirmed = 1 AND Lesson.lesson_date >= '" . date('Y-m-d') . "'";
        $upcomminglesson = $this->Lesson->query($upcomingLessonSQL);

        $pastLessonSQL = "Select * from lessons as Lesson INNER JOIN `$this->databaseName`.`users` AS `User` ON (`User`.`id` = `Lesson`.`$userconditionsfield`) JOIN (SELECT MAX(id) as ids FROM lessons
        GROUP BY parent_id) as newest ON Lesson.id = newest.ids WHERE  `Lesson`.`$userlessonconditionsfield` = '" . $this->Session->read('Auth.User.id') . "'  AND Lesson.lesson_date < '" . date('Y-m-d') . "'";
        $pastlesson = $this->Lesson->query($pastLessonSQL);


        $this->set(compact('activelesson', 'upcomminglesson', 'pastlesson'));
        /* $log = $this->User->getDataSource()->getLog(false, false);
debug($log); */
        if ($this->Session->read('Auth.User.role_id') == 4) {

        }
        $this->render('lessons');

    }

    public function whiteboarddata($lessonid = null){
        $lesson = $this->Lesson->find('first',array('conditions'=>array('id'=>$lessonid)));
        $lessonPayment = $this->LessonPayment->find('first',array('conditions'=>array('lesson_id'=>$lessonid)));

        $lesson_id = (int)$lesson['Lesson']['id'];
        $role_id = (int)$this->Session->read('Auth.User.role_id');

        // handle all our video stuff with Opentok
        $opentok_session_id = $lesson['Lesson']['opentok_session_id'];

        if($opentok_session_id == "") {
            // @TODO: consider changing this to generate a new session id and save it to the DB, instead of throwing an error
            throw new InternalErrorException("Could not load our video system up for some reason. Please try again or contact us.");
        }

        $this->OpenTok = $this->Components->load('OpenTok', Configure::read('OpenTokComponent'));
        $opentok_api_key = $this->OpenTok->apiKey;
        $opentok_token = $this->OpenTok->generateToken($opentok_session_id);

        $username = $this->Session->read('Auth.User.username');

        $this->set(compact(
                'lesson',
                'lessonPayment',
                'lesson_id',
                'opentok_api_key',
                'opentok_session_id',
                'opentok_token',
                'role_id',
                'username'
            ));
    }

    public function changelesson($lessonid = null)
    {
        if (!empty($this->request->data)) {
            $this->Lesson->create();
            $this->request->data['Lesson']['add_date'] = date('Y-m-d');
            if ($this->Auth->user('role_id') == 4) {
                $this->request->data['Lesson']['created'] = $this->request->data['Lesson']['tutor'];
                $this->request->data['Lesson']['tutor'] = $this->Auth->user('id');
                $this->request->data['Lesson']['readlesson'] = '1';
                $this->request->data['Lesson']['readlessontutor'] = '0';
                $this->request->data['Lesson']['laststatus_tutor'] = 1;
                $this->request->data['Lesson']['laststatus_student'] = 0;
            } else if ($this->Auth->user('role_id') == 2) {
                $this->request->data['Lesson']['created'] = $this->Auth->user('id');
                $this->request->data['Lesson']['tutor'] = $this->request->data['Lesson']['tutor'];
                $this->request->data['Lesson']['readlesson'] = '0';
                $this->request->data['Lesson']['readlessontutor'] = '1';
                $this->request->data['Lesson']['laststatus_tutor'] = 0;
                $this->request->data['Lesson']['laststatus_student'] = 1;
            }

            if ($this->Lesson->save($this->request->data)) {
                $lessondid = $this->Lesson->getLastInsertId();
                $sentByid = $this->request->data['Lesson']['tutor'];
                if (!isset($this->request->data['Lesson']['parent_id'])) {
                    unset($this->request->data['Lesson']);
                    $this->request->data['Lesson']['parent_id'] = $lessondid;;
                    $this->Lesson->save($this->request->data);
                }

                if ($this->Auth->user('role_id') == 2) {
                    $this->request->data['Usermessage']['sent_from'] = $this->Auth->user('id');
                    $this->request->data['Usermessage']['send_to'] = $sentByid;
                } else {
                    $this->request->data['Usermessage']['send_to'] = $this->Auth->user('id');
                    $this->request->data['Usermessage']['sent_from'] = $sentByid;
                }
                $this->request->data['Usermessage']['readmessage'] = 0;
                $this->request->data['Usermessage']['date'] = date('Y-m-d H:i:s');
                $this->request->data['Usermessage']['body'] = " Request to Chagne Lesson. Please click here to read.";
                $this->request->data['Usermessage']['parent_id'] = 0;
                unset($this->request->data['Lesson']);
                $this->Usermessage->save($this->request->data);
                $lastId = $this->Usermessage->getLastInsertId();
                if ($this->request->data['Usermessage']['parent_id'] == 0) {
                    $this->Usermessage->query(" UPDATE `usermessages` SET parent_id = '" . $lastId . "' WHERE id = '" . $lastId . "'");
                }


                $this->Session->setFlash(__d('croogo', 'Your lesson has been added successfully.'), 'default', array('class' => 'success'));
                $this->redirect(array('action' => 'lessons'));
            } else {
                $this->Session->setFlash(__d('croogo', 'The Lesson could not be saved. Please, try again.'), 'default', array('class' => 'error'));
            }
        }

        $userconditionsfield = "tutor";
        $userlessonconditionsfield = "created";
        $readconditons = "readlessontutor";
        if ($this->Session->read('Auth.User.role_id') == 4) {
            $userconditionsfield = "created";
            $userlessonconditionsfield = "tutor";
            $readconditons = "readlesson";
        }
        $Lesson = $this->Lesson->find('first', array(
            'joins' => array(
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'INNER',
                    'conditions' => array(
                        "User.id = Lesson.$userconditionsfield"
                    )
                )
            ),
            'fields' => array('User.username', 'User.id', 'Lesson.*'),
            'conditions' => array("Lesson.id" => $lessonid)
        ));
        /*$log = $this->User->getDataSource()->getLog(false, false);
debug($log);
         */
        //	 $this->autoRender = false;
        $this->layouts = false;
        $this->set(compact('Lesson'));
    }

    public function lessons_add()
    {
        if (!empty($this->request->data)) {

            if (isset($this->request->data['Lesson']['tutorname']) && $this->request->data['Lesson']['tutorname'] != "") {

            } else {

            }
            $this->Lesson->create();

            // this gets run when a student proposes a lesson to a tutor
            if (isset($this->request->data['Lesson']['tutor']) && $this->request->data['Lesson']['tutor'] != "") {
                $tutorid = $this->request->data['Lesson']['tutor'];
                $this->request->data['Lesson']['tutor'] = $tutorid;
                $this->request->data['Lesson']['created'] = $this->Auth->user('id');
                $this->request->data['Lesson']['add_date'] = date('Y-m-d');
                $this->request->data['Lesson']['readlesson'] = '0';
                $this->request->data['Lesson']['readlessontutor'] = '0';
                $this->request->data['Lesson']['is_confirmed'] = '0';
                $this->request->data['Lesson']['laststatus_tutor'] = 0;
                $this->request->data['Lesson']['laststatus_student'] = 1;
            } else {
                // this gets run when a tutor creates a lesson to do with a student on the /users/createlessons page
                $tutorid = $this->User->find('first', array('conditions' => array('username' => $this->request->data['Lesson']['tutorname'])));
                $tutorid = $tutorid['User']['id'];
                $this->request->data['Lesson']['tutor'] = $this->Auth->user('id');
                $this->request->data['Lesson']['created'] = $tutorid;
                $this->request->data['Lesson']['add_date'] = date('Y-m-d');
                $this->request->data['Lesson']['readlesson'] = '0';
                $this->request->data['Lesson']['readlessontutor'] = '0';
                $this->request->data['Lesson']['is_confirmed'] = '0';
                $this->request->data['Lesson']['laststatus_tutor'] = 1;
                $this->request->data['Lesson']['laststatus_student'] = 0;
            }

            if ($this->request->data['Lesson']['tutorname'] == "") {
                // generate our twiddla id ahead of time
                $this->Twiddla = $this->Components->load('Twiddla', Configure::read('TwiddlaComponent'));
                $this->request->data['Lesson']['twiddlameetingid'] = $this->Twiddla->getMeetingId();

                // and our opentok session id
                $this->OpenTok = $this->Components->load('OpenTok', Configure::read('OpenTokComponent'));
                $this->request->data['Lesson']['opentok_session_id'] = $this->OpenTok->generateSessionId();
            }

            if ($this->Lesson->save($this->request->data, false)) {
                $lessondid = $this->Lesson->getLastInsertId();

                if (isset($this->request->data['Lesson']['parent_id']) && $this->request->data['Lesson']['parent_id'] != "") {
                    unset($this->request->data['Lesson']);
                    $this->request->data['Lesson']['parent_id'] = $lessondid;;
                    $this->Lesson->save($this->request->data);
                }
                if (!isset($this->request->data['Lesson']['parent_id'])) {
                    unset($this->request->data['Lesson']);
                    $this->request->data['Lesson']['parent_id'] = $lessondid;
                    $this->Lesson->save($this->request->data);
                }

                $this->request->data['Usermessage']['sent_from'] = $this->Auth->user('id');
                $this->request->data['Usermessage']['sent_to'] = $tutorid;
                $this->request->data['Usermessage']['readmessage'] = 0;
                $this->request->data['Usermessage']['date'] = date('Y-m-d H:i:s');
                $this->request->data['Usermessage']['body'] = " Our Lesson is setup now. Please click here to read.";
                $this->request->data['Usermessage']['parent_id'] = 0;

                unset($this->request->data['Lesson']);

                $this->Usermessage->save($this->request->data);
                $lastId = $this->Usermessage->getLastInsertId();

                if ($this->request->data['Usermessage']['parent_id'] == 0) {
                    $this->Usermessage->query(" UPDATE `usermessages` SET parent_id = '" . $lastId . "' WHERE id = '" . $lastId . "'");
                }

                $this->Session->setFlash(__d('croogo', 'Your lesson has been added successfully.'), 'default', array('class' => 'success'));
                $this->redirect(array('action' => 'lessons'));
            } else {
                $this->Session->setFlash(__d('croogo', 'The Lesson could not be saved. Please, try again.'), 'default', array('class' => 'error'));
            }
        }

        if (isset($this->request->params['pass'][0]) && $this->request->params['pass'][0] == 'ajax') {
            $this->request->params['pass'][1];
            $Tutorinfo = $this->User->find('first', array('conditions' => array('User.id' => $this->request->params['pass'][1])));
            $this->set(compact('Tutorinfo'));
            $this->autoRender = false;
            $this->layouts = false;
            $this->render('lessoncreate');
        }

    }

    public function searchstudent()
    {
        $this->User->recursive = 0;
        $cond = array('status' => "1");

        if (!empty($this->request->query)) {
            $name = $this->request->query['term'];
            $cond = array('status' => "1", 'role_id' => "4", array('OR' => array('name LIKE ' => "$name%", 'username LIKE ' => "$name%")));
        }
        $c = $this->User->find('list', array(
            'joins' => array(
                array(
                    'table' => 'usermessages',
                    'alias' => 'usermessage',
                    'type' => 'INNER',
                    'conditions' => array(
                        'usermessage.sent_from = User.id'
                    )
                )
            ),

            'conditions' => $cond, 'group' => 'id', 'fields' => array('id', 'username')));
        $q = strtolower($this->request->query['term']);
        $result = array();

        foreach ($c as $key => $value) {
            if (strpos(strtolower($value), $q) !== false) {
                array_push($result, array("id" => $key, "label" => $value, "value" => strip_tags($value)));
            }
            if (count($result) > 11)
                break;
        }
        echo json_encode($result);
        /*$log = $this->User->getDataSource()->getLog(false, false);
debug($log);*/
        $this->autoRender = false;
        $this->layouts = false;
    }

    public function lessonreviews($lessonid = null)
    {
        if (!empty($this->request->data)) {
            $this->request->data['Review']['add_date'] = date('Y-m-d H:i:s');

            if ($this->Review->save($this->request->data)) {
                $this->redirect(array('action' => 'lessons'));
            }
        }
        $Lesson = $this->Lesson->find('first', array('conditions' => array('id' => $lessonid)));
        $this->set(compact('Lesson'));
    }

	public function confirmedbytutor($lessonid = null){
        $data = $this->Lesson->find('first',array('conditions'=>array('id'=>(int)$lessonid)));

        $data['Lesson']['readlessontutor']    = 1;
        $data['Lesson']['is_confirmed']       = 1;

		if($data['Lesson']['twiddlameetingid'] == 0) {
            $this->Twiddla = $this->Components->load('Twiddla', Configure::read('TwiddlaComponent'));
            $data['Lesson']['twiddlameetingid'] = $this->Twiddla->getMeetingId();
		}

        // retrieve our opentok session id for the upcoming lesson
        if($data['Lesson']['opentok_session_id'] == 0){
            $this->OpenTok = $this->Components->load('OpenTok', Configure::read('OpenTokComponent'));
            $data['Lesson']['opentok_session_id'] = $this->OpenTok->generateSessionId();
        }

        $this->Lesson->save($data);

        $this->redirect(array('action' => 'lessons'));
	}

    public function mycalander()
    {

    }

    public function calandareventsprofile()
    {
        $userconditionsfield = "tutor";
        $userlessonconditionsfield = "created";
        $readconditons = "readlessontutor";

        $upcomminglesson = $this->Lesson->query("Select * from lessons as Lesson INNER JOIN `$this->databaseName`.`users` AS `User` ON (`User`.`id` = `Lesson`.`$userconditionsfield`) JOIN (SELECT MAX(id) as ids FROM lessons
        GROUP BY parent_id) as newest ON Lesson.id = newest.ids WHERE  `Lesson`.`$userlessonconditionsfield` = '" . $this->request->params['userid'] . "'");
        foreach ($upcomminglesson as $k => $v) {

            $d = explode("-", $v['Lesson']['lesson_date']);

            if (strlen($d[2]) == 2 && $d[2] <= 9)
                $d[2] = substr($d[2], 1, 1);
            if (strlen($d[1]) == 2 && $d[1] <= 9)
                $d[1] = substr($d[1], 1, 1);

            $nd = $d[2] . "/" . $d[1] . "/" . $d[0];

            $info[$k]['date'] = $nd;
            $info[$k]['title'] = $v['Lesson']['subject'] . " Class with " . $v['User']['username'];
            $info[$k]['link'] = "a";
            $info[$k]['color'] = "#F38918";
            $info[$k]['class'] = "miclasse";
            $info[$k]['content'] = "";

        }

        echo json_encode($info);
        $this->autoRender = false;
        $this->layouts = false;
    }

    public function calandarevents()
    {
        $userconditionsfield = "tutor";
        $userlessonconditionsfield = "created";
        $readconditons = "readlessontutor";
        if ($this->Session->read('Auth.User.role_id') == 4) {
            $userconditionsfield = "created";
            $userlessonconditionsfield = "tutor";
            $readconditons = "readlesson";
        }

        $upcomminglesson = $this->Lesson->query("Select * from lessons as Lesson INNER JOIN `$this->databaseName`.`users` AS `User` ON (`User`.`id` = `Lesson`.`$userconditionsfield`) JOIN (SELECT MAX(id) as ids FROM lessons
        GROUP BY parent_id) as newest ON Lesson.id = newest.ids WHERE  `Lesson`.`$userlessonconditionsfield` = '" . $this->Session->read('Auth.User.id') . "'
		");

        foreach ($upcomminglesson as $k => $v) {
            $info['result'][$k]['id'] = $v['Lesson']['id'];
            $info['result'][$k]['class'] = 'event-warning';
            $info['result'][$k]['start'] = strtotime($v['Lesson']['lesson_date']) * 1000;
            $info['result'][$k]['end'] = strtotime($v['Lesson']['lesson_date']) * 1000;
            $info['result'][$k]['title'] = $v['Lesson']['subject'] . " Class with " . $v['User']['username'];
        }
        $info['success'] = 1;

        echo json_encode($info);

        $this->autoRender = false;
        $this->layouts = false;
    }

    public function topchart($categoryname = null, $online = null)
    {
        $otherconditions = array('status' => 1, 'role_id' => 2);
        if ($categoryname == 'all') {
            $categoryname = "";
        }
        if (isset($online) && $online != null) {
            $otherconditions = array_merge($otherconditions, array('is_online' => 1));
        }

        if (isset($categoryname) && ($categoryname != "")) {

            $categorysubjects = $this->Category->find('first', array('conditions' => array('status' => 1, 'parent_id' => $categoryname)));

            if (empty($categorysubjects)) {
                $categorysubjects['Category']['name'] = "0";
            }

            $otherconditions = array_merge($otherconditions, array('subject LIKE' => '%' . $categorysubjects['Category']['name'] . '%'));
        }
        $this->paginate['User']['joins'] = array(array(
            'table' => 'reviews',
            'alias' => 'Review',
            'type' => 'LEFT',
            'conditions' => array(
                "User.id = Review.rate_to"
            ))
        );
        $this->paginate['User']['conditions'] = $otherconditions;
        $this->paginate['User']['fields'] = array('*,avg(`Review`.`rating`) as `rating`');
        $this->paginate['User']['group'] = array('User.id');
        $this->set('userlist', $this->paginate());
        $category = $this->Category->find('all', array('conditions' => array('status' => 1, 'parent_id' => null)));
        $this->set(compact('userlist', 'category', 'categoryname', 'online'));
        /*$log = $this->User->getDataSource()->getLog(false, false);
debug($log); */
    }

    public function joinuser($id)
    {
        $requestedby = base64_decode($id);
        $this->Session->write('requestedbyuser', $requestedby);
        $this->Session->write('requestedbyurl', $_SERVER['REQUEST_URI']);
        $this->redirect('/register');
        exit();
    }

    public function updateremaining()
    {
        $this->Lesson->id = $this->params->query['lessonid'];
        $roletype = $this->params->query['roletype'];
        $checktwiddlaid = $this->Lesson->find('first', array('conditions' => array('id' => $this->params->query['lessonid'])));
        $totaltime = 0;

        if ($roletype == 2) {
            $totaltime = $checktwiddlaid['Lesson']['remainingduration'] + 60;
            $this->Lesson->saveField('remainingduration', $totaltime);
            if (isset($this->params->query['completelesson']) && ($this->params->query['completelesson'] == 1)) {
                $lessonPayment = $this->LessonPayment->find('first', array('conditions' => array('student_id' => $checktwiddlaid['Lesson']['tutor'], 'tutor_id' => $checktwiddlaid['Lesson']['created'], 'lesson_id' => $this->params->query['lessonid'])));
                if (empty($lessonPayment)) {
                    $u = $this->UserRate->find('first', array('conditions' => array('userid' => $checktwiddlaid['Lesson']['created'])));
                    $pritype = $u['UserRate']['price_type'];
                    $pricerate = $u['UserRate']['rate'];
                    $totalamount = 0;
                    if ($pritype == 'per min') {
                        $totaltimeuseinmin = $totaltime / 60;
                        $totalamount = ($totaltimeuseinmin) * $pricerate;
                    } else {
                        $pricerate = $pricerate / 60;
                        $totaltimeuseinmin = $totaltime / 60;
                        $totalamount = $totaltimeuseinmin * $pricerate;
                    }
                    $this->request->data['LessonPayment']['student_id'] = $checktwiddlaid['Lesson']['tutor'];
                    $this->request->data['LessonPayment']['tutor_id'] = $checktwiddlaid['Lesson']['created'];
                    $this->request->data['LessonPayment']['payment_amount'] = $totalamount;

                    $this->request->data['LessonPayment']['payment_complete'] = 0;
                    $this->request->data['LessonPayment']['lesson_id'] = $this->params->query['lessonid'];
                    $this->LessonPayment->save($this->request->data);
                } else {
                    $u = $this->UserRate->find('first', array('conditions' => array('userid' => $checktwiddlaid['Lesson']['created'])));
                    $pritype = $u['UserRate']['price_type'];
                    $pricerate = $u['UserRate']['rate'];
                    $totalamount = 0;
                    if ($pritype == 'per min') {
                        $totaltimeuseinmin = $totaltime / 60;
                        $totalamount = ($totaltimeuseinmin) * $pricerate;
                    } else {
                        $pricerate = $pricerate / 60;
                        $totaltimeuseinmin = $totaltime / 60;
                        $totalamount = $totaltimeuseinmin * $pricerate;
                    }
                    $this->request->data['LessonPayment']['student_id'] = $checktwiddlaid['Lesson']['tutor'];
                    $this->request->data['LessonPayment']['tutor_id'] = $checktwiddlaid['Lesson']['created'];
                    $this->request->data['LessonPayment']['payment_amount'] = $totalamount;
                    $this->request->data['LessonPayment']['id'] = $lessonPayment['LessonPayment']['id'];
                    $this->LessonPayment->save($this->request->data);
                }

                $this->request->data['LessonPayment']['lesson_id'] = $this->params->query['lessonid'];
                $this->request->data['LessonPayment']['lesson_complete_tutor'] = 1;
                $this->request->data['LessonPayment']['lesson_complete_student'] = 1;
                $this->LessonPayment->save($this->request->data);
            }
        } else if ($roletype == 4) {
            $totaltime = $checktwiddlaid['Lesson']['student_lessontaekn_time'] + 60;
            $this->Lesson->saveField('student_lessontaekn_time', $totaltime);
            $lessonPayment = $this->LessonPayment->find('first', array('conditions' => array('student_id' => $checktwiddlaid['Lesson']['tutor'], 'tutor_id' => $checktwiddlaid['Lesson']['created'], 'lesson_id' => $this->params->query['lessonid'])));
            if (isset($this->params->query['completelesson']) && ($this->params->query['completelesson'] == 1)) {

                $this->request->data['LessonPayment']['lesson_complete_tutor'] = 1;
                $this->request->data['LessonPayment']['lesson_complete_student'] = 1;
            }

            if (empty($lessonPayment)) {
                $u = $this->UserRate->find('first', array('conditions' => array('userid' => $checktwiddlaid['Lesson']['created'])));
                $pritype = $u['UserRate']['price_type'];
                $pricerate = $u['UserRate']['rate'];
                $totalamount = 0;
                if ($pritype == 'per min') {
                    $totaltimeuseinmin = $totaltime / 60;
                    $totalamount = ($totaltimeuseinmin) * $pricerate;
                } else {
                    $pricerate = $pricerate / 60;
                    $totaltimeuseinmin = $totaltime / 60;
                    $totalamount = $totaltimeuseinmin * $pricerate;
                }
                $this->request->data['LessonPayment']['student_id'] = $checktwiddlaid['Lesson']['tutor'];
                $this->request->data['LessonPayment']['tutor_id'] = $checktwiddlaid['Lesson']['created'];
                $this->request->data['LessonPayment']['payment_amount'] = $totalamount;
                $this->request->data['LessonPayment']['lesson_take'] = 1;
                $this->request->data['LessonPayment']['payment_complete'] = 0;
                $this->request->data['LessonPayment']['lesson_id'] = $this->params->query['lessonid'];

                $this->LessonPayment->save($this->request->data);
            } else {
                $u = $this->UserRate->find('first', array('conditions' => array('userid' => $checktwiddlaid['Lesson']['created'])));
                $pritype = $u['UserRate']['price_type'];
                $pricerate = $u['UserRate']['rate'];
                $totalamount = 0;
                if ($pritype == 'per min') {
                    $totaltimeuseinmin = $totaltime / 60;
                    $totalamount = ($totaltimeuseinmin) * $pricerate;
                } else {
                    $pricerate = $pricerate / 60;
                    $totaltimeuseinmin = $totaltime / 60;
                    $totalamount = $totaltimeuseinmin * $pricerate;
                }
                $this->request->data['LessonPayment']['student_id'] = $checktwiddlaid['Lesson']['tutor'];
                $this->request->data['LessonPayment']['tutor_id'] = $checktwiddlaid['Lesson']['created'];
                $this->request->data['LessonPayment']['payment_amount'] = $totalamount;
                $this->request->data['LessonPayment']['id'] = $lessonPayment['LessonPayment']['id'];
                $this->LessonPayment->save($this->request->data);
            }
        }

        $u = $this->LessonPayment->find('first', array('conditions' => array('lesson_id' => $this->params->query['lessonid'])));

        echo json_encode(array('totaltime' => $totaltime, 'lessonResponse' => $u));
        $this->autoRender = false;
        $this->layouts = false;
    }

    public function paymentmade()
    {
        $payment = $this->params->query['tutor'];
        $lessonid = $this->params->query['lessonid'];
        $u = $this->UserRate->find('first', array('conditions' => array('userid' => $payment)));
        $ulesson = $this->Lesson->find('first', array('conditions' => array('id' => $lessonid)));
        $pritype = $u['UserRate']['price_type'];
        $pricerate = $u['UserRate']['rate'];
        $totalamount = 0;
        $totaltime = $ulesson['Lesson']['duration'];
        if ($pritype == 'per min') {
            $totalamount = ($totaltime * 60 * 60) * $pricerate;
        } else {
            $pricerate = $pricerate / 60;
            $totalamount = $totaltime * $pricerate * 100;
        }
        $this->Session->write("paymentamount", round($totalamount));
        $this->Session->write("paymenttutor", $payment);
        $this->Session->write("paymentstudent", $this->Session->read('Auth.User.id'));
        $this->redirect('/users/billing');
        exit();
    }


    public function claimoffer()
    {
        App::import("Vendor", "Stripe", array("file" => "stripe/Stripe.php"));

        $this->autoRender = false;
        $this->layouts = false;
        $id = $this->Session->read('Auth.User.id');
        $user = $this->User->find('first', array('conditions' => array('user.id' => $id)));

        if ($user['User']['claim_status'] == 0) {
            if ($user['User']['stripe_id'] != "" && $user['User']['secret_key'] != "" && $user['User']['public_key'] != "") {
                $this->User->id = $user['User']['id'];
                $this->User->saveField('claim_status', 0);
                /* stripe code */
                Stripe::setApiKey($user['User']['secret_key']);

                //Create charge
                $token = Stripe_Token::create(array(
                    "card" => array(
                        "number" => "4242424242424242",
                        "exp_month" => 3,
                        "exp_year" => 2015,
                        "cvc" => "314"
                    )
                ));

                $tokenid = $token['id'];
                // Charge the order:
                $charge = Stripe_Charge::create(array(
                    "amount" => 500,
                    "currency" => "usd",
                    "card" => $tokenid, // obtained with Stripe.js
                    "description" => "claim $5 form botangle"
                ));

                if ($charge->paid == true) {
                    /* end stripe code */
                    $this->Session->setFlash(__d('croogo', 'Congratulation you got $5.'), 'default', array('class' => 'success'));
                    $this->Session->write('Auth.User.claim_status', '1');
                    $this->redirect('/users');
                } else {
                    $this->Session->setFlash(__d('croogo', 'Something going wrong. Please try again later.'), 'default', array('class' => 'error'));
                    $this->redirect('/users');
                }
            } else {
                $this->Session->setFlash(__d('croogo', 'You Alredy Claim this offer.'), 'default', array('class' => 'error'));
                $this->redirect('/users');
            }
        } else {
            $this->Session->setFlash(__d('croogo', 'Please set Payment Setting First.'), 'default', array('class' => 'error'));
            $this->redirect('/users/paymentsetting');
        }
    }

    public function paymentsetting()
    {
        App::import("Vendor", "Stripe", array("file" => "stripe/Stripe.php"));
        $id = $this->Session->read('Auth.User.id');
        if (!empty($this->request->data)) {
            $user = $this->User->find('first', array('conditions' => array('user.id' => $id)));
            $this->User->id = $user['User']['id'];
            $this->User->saveField('stripe_id', $this->request->data['User']['stripe_id']);
            $this->User->saveField('secret_key', $this->request->data['User']['secret_key']);
            $this->User->saveField('public_key', $this->request->data['User']['public_key']);
            $this->redirect('/users/paymentsetting');
        } else {
            Stripe::setApiKey("sk_test_XCR1kNc15GsZReu7hKHXFJZ8"); //admin scret key
            if (isset($_GET['code'])) { // Redirect w/ code
                $code = $_GET['code'];
                $token_request_body = array(
                    'client_secret' => 'sk_test_XCR1kNc15GsZReu7hKHXFJZ8', //admin secret key
                    'client_id' => 'ca_3eUUoTUSZsBg8Ly0TA7XjY3noItr8cgC',
                    'code' => $code,
                    'grant_type' => 'authorization_code'
                );
                $refreshtoken = "";
                $req = curl_init('https://connect.stripe.com/oauth/token');
                // set url
                curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($req, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($req, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($req, CURLOPT_POST, true);
                curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));
                $respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);
                $resp = json_decode(curl_exec($req), true);
                curl_close($req);
                $refreshtoken = $resp['refresh_token'];
                $this->User->id = $id;
                $this->User->saveField('auth_code', $refreshtoken);
                $this->Session->setFlash(__d('croogo', 'Your Account Connected with Stripe Sucessfully.'), 'default', array('class' => 'success'));
            }
            $User = $this->User->find('first', array('conditions' => array('User.id' => $id)));
            $this->set(compact('User'));
        }
    }

    function paymentnotmade()
    {
    }
}
