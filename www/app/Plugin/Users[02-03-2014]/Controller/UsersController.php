<?php

App::uses('CakeEmail', 'Network/Email');
App::uses('UsersAppController', 'Users.Controller');

/**
 * Users Controller
 *
 * @category Controller
 * @package  Croogo.Users.Controller
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class UsersController extends UsersAppController {

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

/**
 * Models used by the Controller
 *
 * @var array
 * @access public
 */
	public $uses = array('Users.User','Users.UserRate','Users.Lesson','Users.Usermessage','Users.Review','Categories.Category','Users.Userpoint');
	public $helper = array('Categories.Category','Session');
	 
	function beforeFilter(){
	 
		parent::beforeFilter();
		$this->Security->validatePost = false;
		 $this->Security->csrfCheck = false;
		 
		 $this->Security->unlockedActions = array('*');
		 $this->Auth->allow('searchstudent','calandareventsprofile','joinuser','lessons_add');
	}
	function isAuthorized() {
		if ($this->Auth->user('role') != 'admin') {  
			$this->Auth->deny('*');
		}
	}
/**
 * implementedEvents
 *
 * @return array
 */
	public function implementedEvents() {
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
	public function onBeforeAdminLogin() {
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
	public function onAdminLoginFailure() {
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
	public function admin_index() {
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
	public function admin_add() {
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
	public function admin_edit($id = null) {
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
	public function admin_reset_password($id = null) {
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
	public function admin_delete($id = null) {
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
	public function admin_login() {
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
	public function admin_logout() {
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
	public function index() {
		if (!empty($this->request->data)) { 
			 
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
		  
		if($this->Session->read('Auth.User.role_id')==4){
			$this->render('index2');
		}
		$this->set('title_for_layout', __d('croogo', 'Users'));
	}
	public function index2(){
	}

/**
 * Convenience method to send email
 *
 * @param string $from Sender email
 * @param string $to Receiver email
 * @param string $subject Subject
 * @param string $template Template to use
 * @param string $theme Theme to use
 * @param array  $viewVars Vars to use inside template
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
/**
 * Registration
 *
 * @return void
 * @access public
 */
	public function registration(){
		 $registrationtype = $this->params['pass'][0];  
		 $this->Session->write("type",$registrationtype); 
		 $this->redirect(array('action' => 'add'));
		  exit();
	}
/**
 * Registration
 *
 * @return void
 * @access public
 */
	public function accountsetting(){
		 if (!empty($this->request->data)) {
			if(isset($this->request->data['User']['posttype']) && $this->request->data['User']['posttype']=='pic'){
				$filename = null;
					 
 
if (!empty($this->request->data['User']['profilepic']['tmp_name']) && is_uploaded_file($this->request->data['User']['profilepic']['tmp_name'])) {
				 
	$filename = str_replace(" ","_",basename($this->request->data['User']['profilepic']['name']));
	 $dir = 	WWW_ROOT .'uploads' . DS . $this->request->data['User']['id'] ; 
	$profiledir=  	WWW_ROOT .'uploads' . DS . $this->request->data['User']['id']. DS . "profile" ;
        $profiledir=  	WWW_ROOT .'uploads' . DS . $this->request->data['User']['id']. DS . "profile" ;
        
				if(!is_dir($dir)){ 
					mkdir($dir,0777); 
				}
				if(!is_dir($profiledir)){
					mkdir($profiledir,0777); 
				}
				move_uploaded_file(
					$this->data['User']['profilepic']['tmp_name'],
					$profiledir. DS . $filename
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
			}else{
				$oldpassw = AuthComponent::password($this->data['User']['oldpassword']);
				 $user = $this->User->find('first', array(
					'conditions' => array(
						'User.id' => $this->request->data['User']['id'], 
					),
				));
				 
				 
				if($oldpassw==$user['User']['password']){
					 
					if ($this->User->save($this->request->data)) {
						$this->Session->setFlash(__d('croogo', 'Password has been reset.'), 'default', array('class' => 'success'));
						$this->redirect(array('action' => 'accountsetting'));
					} else {  
						$this->Session->setFlash(__d('croogo', 'Password could not be reset. Please, try again.'), 'default', array('class' => 'error'));
					}
				}else{
					$this->Session->setFlash(__d('croogo', 'Password could not be reset. Please, try again.'), 'default', array('class' => 'error'));
				}
				}
		 }
		if($this->Session->read('Auth.User.role_id')==4){
			$this->render('accountsetting2');
		}
	}
	public function accountsetting2(){
		if($this->Session->read('Auth.User.role_id')==4){
			$this->render('accountsetting2');
		}
	}
/**
 * Add
 *
 * @return void
 * @access public
 */
	public function add() { 
	
		
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
				
			if($this->Session->check('requestedbyuser')){
				if($newUser < 100){
					$requesteduser =  $this->User->find('first', 	array(
						'conditions' => array(
							'User.id' =>$this->Session->read('requestedbyuser'),
							 
						),
					)); 
				
					if($this->Session->check('requestedbyuser')){
						if($requesteduser['User']['role_id'] == 2){
							$trophyamountlesson = '5';
						}else if($requesteduser['User']['role_id'] == 4){
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
		 
		$this->set('type',$this->Session->read('type'));
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
	public function activate($username = null, $key = null) {
		if ($username == null || $key == null) {
			$this->redirect(array('action' => 'login'));
		}

		if ($this->User->hasAny(array(
				'User.username' => $username,
				'User.activation_key' => $key,
				'User.status' => 0,
			))) {
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
	public function edit() {
	}

/**
 * Forgot
 *
 * @return void
 * @access public
 */
	public function forgot() {  
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
	function passwordrecovery(){
	}

/**
 * Reset
 *
 * @param string $username
 * @param string $key
 * @return void
 * @access public
 */
	public function reset($username = null, $key = null) {
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
	public function login() {
		$this->set('title_for_layout', __d('croogo', 'Log in'));
		if ($this->request->is('post')) {
			Croogo::dispatchEvent('Controller.Users.beforeLogin', $this);
			
			if ($this->Auth->login()) {
				Croogo::dispatchEvent('Controller.Users.loginSuccessful', $this);
				;
				 
				 $this->request->data['User']['is_online'] = 1 ;
				 $this->request->data['User']['id'] = $this->Session->read('Auth.User.id') ;
				 $_SESSION['userid'] = $this->Session->read('Auth.User.id');
				if ($this->User->save($this->request->data)) {
				}
				 
				$this->redirect($this->Auth->redirect());
			} else {
				Croogo::dispatchEvent('Controller.Users.loginFailure', $this);
				$this->Session->setFlash($this->Auth->authError, 'default', array('class' => 'error'), 'auth');
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
	public function logout() {
		Croogo::dispatchEvent('Controller.Users.beforeLogout', $this);
		$this->Session->setFlash(__d('croogo', 'Log out successful.'), 'default', array('class' => 'success'));
		$this->request->data['User']['is_online'] = 0 ;
		 $this->request->data['User']['id'] = $this->Session->read('Auth.User.id') ;
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
	public function view($username = null) {   
		if ($username == null) {
			$username = $this->Auth->user('username');
		}
		$user = $this->User->findByUsername($username);
		 
		if (!isset($user['User']['id'])) {
			$this->Session->setFlash(__d('croogo', 'Invalid User.'), 'default', array('class' => 'error'));
			$this->redirect('/');
		}

		$this->set('title_for_layout', $user['User']['name']);
		
		$userRate = $this->UserRate->find('first',array('conditions'=>array('UserRate.userid' => $user['User']['id'])));
		
		$userRating = $this->Review->find('first',array('conditions'=>array('Review.rate_to' => $user['User']['id']),
		'fields'=>array('avg(rating) as avg'),
		));
		$userReviews = $this->Review->find('all',array(  
		'joins' => array(array(
						'table' => 'users',
						'alias' => 'User',
						'type' => 'INNER',
						'conditions' => array(
							"User.id = Review.rate_by"
						))),
						
		 
		'fields'=>array('*'),
		'conditions'=>array(	'Review.rate_to' => $user['User']['id']) 
		 ));
		/*$log = $this->User->getDataSource()->getLog(false, false);
debug($log);*/
		$this->set(compact('user','userRate','userRating','userReviews'));
		
		
		
		if($user['User']['role_id']==4){
			$this->render('view2');
		}
	}

	protected function _getSenderEmail() {
		return 'croogo@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
	}
	public function billing(){
	if (!empty($this->request->data)) { 
		
			if(isset($this->request->data['Billing']['studentpayemtn']) && $this->request->data['Billing']['studentpayemtn']){
				$cartSession = "";
				 
				$cartSession['fname'] = $this->request->data['Billing']['tutor_id'];
				$cartSession['lname'] = $_POST['lname'];
				$cartSession['card'] = $_POST['card'];
				$cartSession['acc_number'] = $_POST['acc_number'];
				 
				
				$cartSession['expiration_month'] = $_POST['expiration_month']; 
				$cartSession['expiration_year'] = $_POST['expiration_year'];;
				$cartSession['card_security_code'] = $_POST['card_security_code'];
				$cartSession['bill_addressline1'] = $_POST['bill_addressline1'];
				$cartSession['bill_addressline2'] = $_POST['bill_addressline2'];
				$cartSession['bill_city'] = $_POST['bill_city'];
				$cartSession['bill_state'] = $_POST['bill_state'];
				$cartSession['bill_zip'] = $_POST['bill_zip'];
				$cartSession['bill_country'] = $_POST['bill_country'];
				$cartSession['payamount'] = $_POST['payamount'];
				$cartSession['paymentCurrency'] = "USD";
				 
				include('PaypalproController.php');
				$paypalCont = new PaypalproController();
						$paypalResponse = $paypalCont->setRequestFields($cartSession,10);
						
						if($paypalResponse['ACK']=='Success'){
							/* PAYMENT MADE NEXT STEP TO SENT EMAIL ADMIN*/
							$this->Session->setFlash(__d('croogo', 'Information has been updated'), 'default', array('class' => 'success'));
							$this->redirect(array('action' => 'billing'));
						}else{
						
							$this->Session->setFlash(__d('croogo', 'Payment could not be made,please try again.'), 'default', array('class' => 'error'));
						}
						 
			}else{
				if ($this->UserRate->save($this->request->data)) {
					$this->Session->setFlash(__d('croogo', 'Information has been updated'), 'default', array('class' => 'success')); 
					$this->redirect(array('action' => 'billing'));
				} else {
					$this->Session->setFlash(__d('croogo', 'Information can not be updated. Please, try again.'), 'default', array('class' => 'error'));
				}
			}
		}  
	
		
		
		$this->set('ratedata',$this->UserRate->find('first',array('conditions'=>array('UserRate.userid' => $this->Session->read('Auth.User.id')))));
		$this->set('title_for_layout', __d('croogo', 'Billing'));	
		
		if($this->Session->read('Auth.User.role_id')==4){
			$userInfo = $this->User->find('list',array('conditions'=>array('role_id'=>2,'status'=>1)));
			$roleid = 2;
			$this->set(compact('userInfo','roleid'));
			$this->render('billing2');
		}		
	}
	function billing2(){
		
		 
	}
	public function search($categoryname = null,$online= null){
		 
		$searchValue = isset($this->request->data['searchvalue'])?$this->request->data['searchvalue']:"";
		$this->set('title_for_layout', __d('croogo', 'Search User'));
		  

		$this->User->recursive = 0;
		/* if(isset($this->request->data['Experience_start']) && $this->request->data['Experience_start']!=""){
		 $startExperience = $this->request->data['Experience_start'];
		 
		 }if(isset($this->request->data['Experience_end']) && $this->request->data['Experience_end']!=""){
		 $endExperience = $this->request->data['Experience_end'];
		 
		 }*/
		  $otherconditions = array("User.status"=>1,"User.role_id"=>2,"User.subject LIKE" =>'%'.trim($searchValue).'%');
		 if(isset($online) && $online!=null){ 
		 $otherconditions = array_merge($otherconditions,array('is_online'=>1));
		}

		/*$this->paginate['User']['conditions']  = array("User.status"=>1,"User.role_id"=>2,array('OR'=>array("User.username LIKE '%$searchValue%'","User.extracurricular_interests LIKE '%$searchValue%'","User.subject LIKE '%$searchValue%'","User.qualification LIKE '%$searchValue%'")));*/
		
		 
		
		$this->paginate['User']['joins']  = array(array(
						'table' => 'reviews',
						'alias' => 'Review',
						'type' => 'LEFT',
						'conditions' => array(
							"User.id = Review.rate_to"
						))
					); 
		$this->paginate['User']['conditions']  = $otherconditions;
		$this->paginate['User']['fields']  = array('*,avg(`Review`.`rating`) as `rating`');
		$this->paginate['User']['group']  = array('User.id');
		$this->set('users', $this->paginate()); 
		 

		/*$log = $this->User->getDataSource()->getLog(false, false);
debug($log); die;*/
	}
	
	public function lessons(){
		
		$userconditionsfield = "tutor";
		$userlessonconditionsfield = "created";
		$readconditons = "readlessontutor";
		 if($this->Session->read('Auth.User.role_id')==4){
			$userconditionsfield = "created";
			$userlessonconditionsfield = "tutor";
			$readconditons = "readlesson";
		 }
		  
		  $activelesson =  $this->Lesson->query("Select * from lessons as Lesson INNER JOIN `phelixin_bota`.`users` AS `User` ON (`User`.`id` = `Lesson`.`$userconditionsfield`) JOIN (SELECT MAX(id) as ids FROM lessons 
        GROUP BY parent_id) as newest ON Lesson.id = newest.ids WHERE  `Lesson`.`$userlessonconditionsfield` = '".$this->Session->read('Auth.User.id')."'  AND `Lesson`.`$readconditons` = 0  AND Lesson.lesson_date >= '".date('Y-m-d')."'  
		");
		/*echo "Select * from lessons as Lesson INNER JOIN `phelixin_bota`.`Users` AS `User` ON (`User`.`id` = `Lesson`.`created`) JOIN (SELECT MAX(id) as ids FROM lessons 
        GROUP BY parent_id) as newest ON Lesson.id = newest.ids WHERE  `Lesson`.`$userlessonconditionsfield` = '".$this->Session->read('Auth.User.id')."'  AND `Lesson`.`$readconditons` = 0  
		";
		  */
		 
		$upcomminglesson =  $this->Lesson->query("Select * from lessons as Lesson INNER JOIN `phelixin_bota`.`users` AS `User` ON (`User`.`id` = `Lesson`.`$userconditionsfield`) JOIN (SELECT MAX(id) as ids FROM lessons 
        GROUP BY parent_id) as newest ON Lesson.id = newest.ids WHERE  `Lesson`.`$userlessonconditionsfield` = '".$this->Session->read('Auth.User.id')."'  AND `Lesson`.`$readconditons` = 1 AND Lesson.lesson_date >= '".date('Y-m-d')."'  
		");
		  
		$pastlesson =  $this->Lesson->query("Select * from lessons as Lesson INNER JOIN `phelixin_bota`.`users` AS `User` ON (`User`.`id` = `Lesson`.`$userconditionsfield`) JOIN (SELECT MAX(id) as ids FROM lessons 
        GROUP BY parent_id) as newest ON Lesson.id = newest.ids WHERE  `Lesson`.`$userlessonconditionsfield` = '".$this->Session->read('Auth.User.id')."'  AND Lesson.lesson_date < '".date('Y-m-d')."'  
		"); 
		 
		
		$this->set(compact('activelesson','upcomminglesson','pastlesson'));
		  /* $log = $this->User->getDataSource()->getLog(false, false);
debug($log); */
		  if($this->Session->read('Auth.User.role_id')==4){
			
		 }
		 $this->render('lessons2');
		
	}
	public function whiteboarddata($lessonid = null){
		$lesson = $this->Lesson->find('first',array('conditions'=>array('id'=>$lessonid)));
		 $this->set(compact('lesson'));
	}
	public function changelesson($lessonid = null){	 
		    if (!empty($this->request->data)) { 
				$this->Lesson->create(); 
				 
				
				
				$this->request->data['Lesson']['add_date'] = date('Y-m-d');
				if($this->Auth->user('role_id')==4){
				
				$this->request->data['Lesson']['created'] = $this->request->data['Lesson']['tutor'];
				$this->request->data['Lesson']['tutor'] = $this->Auth->user('id');
				$this->request->data['Lesson']['readlesson'] = '1';
				
				$this->request->data['Lesson']['readlessontutor'] = '0'; 
				}else if($this->Auth->user('role_id')==2){
				$this->request->data['Lesson']['created'] = $this->Auth->user('id');
				$this->request->data['Lesson']['tutor'] = $this->request->data['Lesson']['tutor'];
				$this->request->data['Lesson']['readlesson'] = '0';
				
				$this->request->data['Lesson']['readlessontutor'] = '1'; 
				}
				 
			if ($this->Lesson->save($this->request->data)) {
				$lessondid = $this->Lesson->getLastInsertId();
				$sentByid = $this->request->data['Lesson']['tutor'];
				 if(!isset($this->request->data['Lesson']['parent_id'])){
				 unset($this->request->data['Lesson']);
				 $this->request->data['Lesson']['parent_id'] = $lessondid;;
				 $this->Lesson->save($this->request->data);
				 }
				 
				 if($this->Auth->user('role_id')==2){
				$this->request->data['Usermessage']['sent_from'] = $this->Auth->user('id');
				$this->request->data['Usermessage']['send_to'] = $sentByid;
				}else{
				$this->request->data['Usermessage']['send_to'] = $this->Auth->user('id');
				$this->request->data['Usermessage']['sent_from'] = $sentByid;
				}
				$this->request->data['Usermessage']['readmessage'] = 0; 
				$this->request->data['Usermessage']['date'] = date('Y-m-d H:i:s');
				$this->request->data['Usermessage']['body'] = " Request to Chagne Lesson. Please click here to read.";
				$this->request->data['Usermessage']['parent_id']=0;
				 unset($this->request->data['Lesson']);
				$this->Usermessage->save($this->request->data);
				$lastId = $this->Usermessage->getLastInsertId();   
				if($this->request->data['Usermessage']['parent_id']==0){   
					 $this->Usermessage->query(" UPDATE `usermessages` SET parent_id = '".$lastId."' WHERE id = '".$lastId."'"); 
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
		 if($this->Session->read('Auth.User.role_id')==4){
			$userconditionsfield = "created";
			$userlessonconditionsfield = "tutor";
			$readconditons = "readlesson";
		 }
			 $Lesson =  $this->Lesson->find('first',array( 
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
				'fields'=>array('User.username','User.id','Lesson.*'),
				'conditions'=>array("Lesson.id" => $lessonid)
				));
				/*$log = $this->User->getDataSource()->getLog(false, false);
debug($log);
				 */
				 
				$this->set(compact('Lesson'));
	}
	public function lessons_add(){
		
		
		
		if (!empty($this->request->data)) { 
                                        
                                
				$this->Lesson->create();
                                $tutorid = $this->request->data['Lesson']['tutor'];
				$this->request->data['Lesson']['tutor'] = $this->Auth->user('id');
				$this->request->data['Lesson']['add_date'] = date('Y-m-d');
				$this->request->data['Lesson']['readlesson'] = '0';
				$this->request->data['Lesson']['readlessontutor'] = '0'; 
				$meetingid = $this->gettwiddlameetingid();
				$this->request->data['Lesson']['twiddlameetingid'] = $meetingid;
                                $this->request->data['Lesson']['created']= $tutorid;
                                
			if ($this->Lesson->save($this->request->data,false)) {
				$lessondid = $this->Lesson->getLastInsertId(); 
				
				
				 if(isset($this->request->data['Lesson']['parent_id']) && $this->request->data['Lesson']['parent_id']!=""){
				 unset($this->request->data['Lesson']);
				 $this->request->data['Lesson']['parent_id'] = $lessondid;;
				 $this->Lesson->save($this->request->data);
				 }
				if(!isset($this->request->data['Lesson']['parent_id'])){
				unset($this->request->data['Lesson']);
				 $this->request->data['Lesson']['parent_id'] = $lessondid; 
				 $this->Lesson->save($this->request->data);
				}
				
				$this->request->data['Usermessage']['sent_from'] = $this->Auth->user('id');
				$this->request->data['Usermessage']['sent_to'] = $tutorid;
				$this->request->data['Usermessage']['readmessage'] = 0; 
				$this->request->data['Usermessage']['date'] = date('Y-m-d H:i:s');
				$this->request->data['Usermessage']['body'] = " Our Lesson is setup now. Please click here to read.";
				$this->request->data['Usermessage']['parent_id']=0;
				 unset($this->request->data['Lesson']);
				$this->Usermessage->save($this->request->data);
				$lastId = $this->Usermessage->getLastInsertId();   
				if($this->request->data['Usermessage']['parent_id']==0){   
					 $this->Usermessage->query(" UPDATE `usermessages` SET parent_id = '".$lastId."' WHERE id = '".$lastId."'"); 
				} 
				 
				 
				 
				$this->Session->setFlash(__d('croogo', 'Your lesson has been added successfully.'), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'lessons'));
			} else {
				$this->Session->setFlash(__d('croogo', 'The Lesson could not be saved. Please, try again.'), 'default', array('class' => 'error'));
			}
		}
	 
		if(isset($this->request->params['pass'][0]) && $this->request->params['pass'][0]=='ajax'){
		  $this->request->params['pass'][1];
			$Tutorinfo = $this->User->find('first',array('conditions'=>array('User.id'=>$this->request->params['pass'][1])));
			$this->set(compact('Tutorinfo'));
			$this->autoRender = false;
		   $this->layouts =  false;
			$this->render('lessoncreate');
		}
		 
	}
	public function gettwiddlameetingid(){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"http://www.twiddla.com/API/CreateMeeting.aspx");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,
					"username=deepakjain&password=123456789");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec ($ch);
		curl_close ($ch);
		return $server_output; 
	}
	public function searchstudent(){
		$this->User->recursive = 0;	
		$cond= array('status'=>"1");
	 
	if(!empty($this->request->query)){
			 
				$name = $this->request->query['term'];
				$cond= array('status'=>"1",'role_id'=>"4",array('OR'=>array('name LIKE '=>"$name%",'username LIKE '=>"$name%")));
			}
		$c = $this->User->find('list',array(
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
		
		'conditions'=> $cond,'group'=>'id','fields'=>array('id','username')));
			$q = strtolower($this->request->query['term']);  
		$result = array();
		
foreach ($c as $key=>$value) {  
	if (strpos(strtolower($value), $q) !== false) {
		array_push($result, array("id"=>$key, "label"=>$value, "value" => strip_tags($value)));
	}
	if (count($result) > 11)
		break;
	
}	  echo json_encode($result);
			/*$log = $this->User->getDataSource()->getLog(false, false);
debug($log);*/
		  $this->autoRender = false;
		   $this->layouts =  false;
		   
		  
	}
	public function lessonreviews($lessonid = null){
		 if (!empty($this->request->data)) {
			$this->request->data['Review']['add_date'] = date('Y-m-d H:i:s');
			
			if ($this->Review->save($this->request->data)) {
				$this->redirect(array('action' => 'lessons'));
			} 
		 }
		$Lesson = $this->Lesson->find('first',array('conditions'=>array('id'=>$lessonid)));
		$this->set(compact('Lesson'));
		 
	}	
	public function confirmedbytutor($lessonid = null){
		 $this->Lesson->id    = $lessonid;
$this->Lesson->saveField('readlessontutor', '1');
$this->Lesson->saveField('readlesson', '1');
$this->redirect(array('action' => 'lessons'));
	}
	public function mycalander(){
		 
	}
	public function calandareventsprofile(){
	 $userconditionsfield = "tutor";
	$userlessonconditionsfield = "created";
	$readconditons = "readlessontutor";
	 
	$upcomminglesson =  $this->Lesson->query("Select * from lessons as Lesson INNER JOIN `phelixin_bota`.`users` AS `User` ON (`User`.`id` = `Lesson`.`$userconditionsfield`) JOIN (SELECT MAX(id) as ids FROM lessons 
        GROUP BY parent_id) as newest ON Lesson.id = newest.ids WHERE  `Lesson`.`$userlessonconditionsfield` = '".$this->request->params['userid']."'   
		");
	 foreach($upcomminglesson as $k=>$v){
		
		$d = explode("-",$v['Lesson']['lesson_date']);
		 
		if(strlen($d[2])==2 && $d[2]<=9)
			$d[2] = substr($d[2],1,1);
		if(strlen($d[1])==2 && $d[1]<=9)
			$d[1] = substr($d[1],1,1);
			
		$nd = $d[2]."/".$d[1]."/".$d[0];
		 
		 $info[$k]['date'] = $nd;
		 $info[$k]['title'] = $v['Lesson']['subject']." Class with ".$v['User']['username'];
		 $info[$k]['link']="a";
		 $info[$k]['color'] = "#F38918";
		 $info[$k]['class']="miclasse";
		 $info[$k]['content']="";
		
	 }
	/* pr( $info);
	  pr( json_decode('[{"date":"27\/2\/2014","title":"Getting Contacts Barcelona - test1","link":"http:\/\/gettingcontacts.com\/events\/view\/barcelona","color":"red"},{"date":"25\/5\/2014","title":"test2","link":"http:\/\/gettingcontacts.com\/events\/view\/barcelona","color":"pink"},{"date":"20\/6\/2014","title":"test2","link":"http:\/\/gettingcontacts.com\/events\/view\/barcelona","color":"green"},{"date":"7\/10\/2014","title":"test3","link":"http:\/\/gettingcontacts.com\/events\/view\/barcelona","color":"blue","class":"miclasse ","content":"contingut popover<img src=\"http:\/\/gettingcontacts.com\/upload\/news\/estiu_productiu.png"}]',true));*/
	 
	    echo json_encode($info);
	  $this->autoRender = false;
		   $this->layouts =  false;		
	 }
	public function calandarevents(){
		$userconditionsfield = "tutor";
		$userlessonconditionsfield = "created";
		$readconditons = "readlessontutor";
		 if($this->Session->read('Auth.User.role_id')==4){
			$userconditionsfield = "created";
			$userlessonconditionsfield = "tutor";
			$readconditons = "readlesson";
		 }
		 
		$upcomminglesson =  $this->Lesson->query("Select * from lessons as Lesson INNER JOIN `phelixin_bota`.`users` AS `User` ON (`User`.`id` = `Lesson`.`$userconditionsfield`) JOIN (SELECT MAX(id) as ids FROM lessons 
        GROUP BY parent_id) as newest ON Lesson.id = newest.ids WHERE  `Lesson`.`$userlessonconditionsfield` = '".$this->Session->read('Auth.User.id')."'   
		");
	 foreach($upcomminglesson as $k=>$v){
		$info['result'][$k]['id'] = $v['Lesson']['id'];
		$info['result'][$k]['class'] = 'event-warning';
		 $info['result'][$k]['start'] = strtotime($v['Lesson']['lesson_date'])*1000;
		$info['result'][$k]['end'] = strtotime($v['Lesson']['lesson_date'])*1000;
		$info['result'][$k]['title'] = $v['Lesson']['subject']." Class with ".$v['User']['username'];
	 }
	 $info['success'] = 1;
	 
	  echo json_encode($info);
	 /*
		echo '{
				"success": 1,
				"result": [
					{
						"id": "293",
						"title": "This is warning class event",
						"url": "http://www.example.com/",
						"class": "event-warning",
						"start": "1362938400000",
						"end":   "1363197686300"
					},
					{
						"id": "294",
						"title": "This is information class ",
						"url": "http://www.example.com/",
						"class": "event-info",
						"start": "1363111200000",
						"end":   "1363284086400"
					},
					{
						"id": "297",
						"title": "This is success event",
						"url": "http://www.example.com/",
						"class": "event-success",
						"start": "1363284000000",
						"end":   "1363284086400"
					},
					{
						"id": "54",
						"title": "This is simple event",
						"url": "http://www.example.com/",
						"class": "",
						"start": "1363629600000",
						"end":   "1363716086400"
					},
					{
						"id": "532",
						"title": "This is inverse event",
						"url": "http://www.example.com/",
						"class": "event-inverse",
						"start": "1364407200000",
						"end":   "1364493686400"
					},
					{
						"id": "548",
						"title": "This is special event",
						"url": "http://www.example.com/",
						"class": "event-special",
						"start": "1363197600000",
						"end":   "1363629686400"
					},
					{
						"id": "295",
						"title": "Event 3",
						"url": "http://www.example.com/",
						"class": "event-important",
						"start": "1364320800000",
						"end":   "1364407286400"
					}
				]
			}
			';*/
		  $this->autoRender = false;
		   $this->layouts =  false;		
	}
	public function topchart($categoryname = null,$online= null){
		$otherconditions = array('status'=>1, 'role_id'=>2);
		if($categoryname=='all'){
				$categoryname = "";
		}
		if(isset($online) && $online!=null){ 
		 $otherconditions = array_merge($otherconditions,array('is_online'=>1));
		}
		 
		if(isset($categoryname) && ($categoryname!="")){
			
			$categorysubjects = $this->Category->find('first',array('conditions'=> array('status'=>1,'parent_id'=>$categoryname)));
			 
			 if(empty($categorysubjects)){
				$categorysubjects['Category']['name'] = "0";
			 }
			 
			 $otherconditions = array_merge($otherconditions,array('subject LIKE'=>'%'.$categorysubjects['Category']['name'].'%'));
			
		}
		
	 
		$this->paginate['User']['joins']  = array(array(
						'table' => 'reviews',
						'alias' => 'Review',
						'type' => 'LEFT',
						'conditions' => array(
							"User.id = Review.rate_to"
						))
					); 
		$this->paginate['User']['conditions']  = $otherconditions;
		$this->paginate['User']['fields']  = array('*,avg(`Review`.`rating`) as `rating`');
		$this->paginate['User']['group']  = array('User.id');
		$this->set('userlist', $this->paginate()); 
		$category = $this->Category->find('all',array('conditions'=>array('status'=>1,'parent_id'=>null)));
		$this->set(compact('userlist','category','categoryname','online')); 
		/*$log = $this->User->getDataSource()->getLog(false, false);
debug($log); */
	}
	public function joinuser($id){  
		$requestedby = base64_decode($id);
		$this->Session->write('requestedbyuser',$requestedby);
		$this->Session->write('requestedbyurl',$_SERVER['REQUEST_URI']);
		$this->redirect('/register');  
		exit();
		
	}
	
}
