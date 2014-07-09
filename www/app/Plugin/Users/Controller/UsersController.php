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
		'Stripe' => array(
			'className' => 'StripeConnect',
		),
		'CsvView.CsvView',
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
	public $uses = array('Users.User', 'Users.UserRate', 'Users.Lesson', 'Users.Usermessage', 'Users.Review', 'Categories.Category', 'Users.Userpoint', 'Users.LessonPayment', 'Users.Mystatus');

	public $helper = array('Categories.Category', 'Session', 'Cache');
	
	public $cacheAction = array(
		'topchart' => '1 hour',
	);

	public function __construct($request = null, $response = null) {
		parent::__construct($request, $response);
		$this->getEventManager()->attach(new UserListener());
	}

	function beforeFilter() {
		$fields = ConnectionManager::getDataSource('default');
		$dsc = $fields->config;
		$this->databaseName = $dsc['database'];

		parent::beforeFilter();

// the below doesn't handle all the actions we need unlocked.  For now, to get the site working again,
// I'm enabling them all again
		$this->Security->validatePost = false;
		$this->Security->csrfCheck = false;

//        $this->Security->unlockedActions = array('search', 'add', 'billing', 'index', 'registration');
		$this->Security->unlockedActions = array('*');

		$this->Auth->allow('searchstudent', 'calandareventsprofile', 'joinuser', 'lessons_add', 'updateremaining', 'paymentmade', 'claimoffer', 'paymentsetting', 'mystatus');

		if ($this->Session->check('Auth.User') && $this->Session->read('Auth.User.role_id') == 4) {
			$this->checkpayment();
		}

//		$this->Security->blackHoleCallback = 'blackhole';
	}

//	public function blackhole($type) {
//		// handle errors.
//		debug($type);
//	}

/**
 * Check the student read lesson and payment not made
 * redirect at the payment page
 * */
	function checkpayment() {
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
		Cache::write($cacheName, (int) $cacheValue + 1, 'users_login');
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
 * Admin export to csv
 *
 * @return void
 * @access public
 */
	public function admin_export($role_id) {
		if (empty($role_id)) {
			throw new NotFoundException('404');
		}

		$results = $this->User->find('all', array(
			'fields' => 'User.name, User.lname, User.email',
			'conditions' => array(
				'User.role_id' => $role_id,
			)
		));

		$filename = 'users_experts.csv';

		if ($role_id == '4') {
			$filename = 'users_students.csv';
		}

		$this->response->download($filename);
		$this->CsvView->quickExport($results);
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

//		debug($this->request->data); die();

		if ($this->request->is('post') || $this->request->is('put')) {
			$this->User->id = $this->Auth->user('id');

			// change password
			if ($this->request->data['button'] == 'change_password') {
				$oldpassw = AuthComponent::password($this->data['User']['oldpassword']);

				$user = $this->User->find('first', array(
					'conditions' => array(
						'User.id' => $this->request->data['User']['id'],
					),
				));

				if ($oldpassw == $user['User']['password']) {
					if ($this->User->save($this->request->data)) {
						$this->Session->setFlash(__d('croogo', 'Your password has been reset.'), 'default', array('class' => 'success'));
						$this->redirect(array('action' => 'index'));
					} else {
						$this->Session->setFlash(__d('croogo', 'Your password could not be reset. Please, try again.'), 'default', array('class' => 'error'));
					}
				} else {
					$this->Session->setFlash(__d('croogo', 'Your password could not be reset. Please, try again.'), 'default', array('class' => 'error'));
				}
			}

			// update info
			if ($this->request->data['button'] == 'update_info') {

				$error = false;
				$errorMsg = 'Your information can not be updated. Please, try again.';


				$user = $this->User->find('first', array(
					'conditions' => array(
						'User.id' => $this->request->data['User']['id'],
					),
				));

				if (!$error && $this->User->save($this->request->data)) {
					$this->Session->setFlash(__d('croogo', 'Your information has been updated'), 'default', array('class' => 'success'));
					$user = $this->User->find('first', array(
						'conditions' => array('User.id' => $this->request->data['User']['id'])));


					$this->Session->write('Auth', $user);
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__d('croogo', $errorMsg), 'default', array('class' => 'error'));
				}
			}
		} else {
			$this->request->data = $this->User->read(null, $this->Auth->user('id'));
			unset($this->request->data['User']['oldpassword']);
			unset($this->request->data['User']['password']);
		}

		$this->set('title_for_layout', __d('croogo', 'Users'));

		$user = $this->User->find('first', array('conditions' => array('User.id' => $this->request->data['User']['id'])));
		$this->set('user', $user);

		if ($this->Session->read('Auth.User.role_id') == 4) {
			$this->render('index2');
		}
	}

//	public function _uploadPic() {
//
//		$path_parts = pathinfo($this->data['User']['profilepic']['name']);
//
//		// we're going to bust caching on this image if it kills us :-)
//		$filename = uniqid() . '.' . $path_parts['extension'];
//		$dir = WWW_ROOT . 'uploads' . DS . 'users' . DS . $this->request->data['User']['id'];
//		$profiledir = WWW_ROOT . 'uploads' . DS . 'users' . DS . $this->request->data['User']['id'] . DS . "profile";
//
//		// create the user's folder if not yet created
//		if (!is_dir($dir)) {
//			mkdir($dir, 0777);
//		}
//
//		// create the profile's folder if not yet created
//		if (!is_dir($profiledir)) {
//			mkdir($profiledir, 0777);
//		}
//
//		// run our nice GD image handling library so that we don't have to work too hard here
//		$imagine = new Imagine\Gd\Imagine();
//
//		$size = new Imagine\Image\Box(242, 242);
//		$point = new Imagine\Image\Point\Center($size);
//
//		$imagine->open($this->data['User']['profilepic']['tmp_name'])
//				->crop($point, $size)
//				->save($profiledir . DS . $filename);
//
//		return $filename;
//	}

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

/**
 * Registration
 *
 * @return void
 * @access public
 */
	public function registration() {
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
	public function accountsetting() {
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
							$this->data['User']['profilepic']['tmp_name'], $profiledir . DS . $filename
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

/**
 * Account Setting 2
 *
 * If the logged-in user is of `role_id` 4, then render the accountsetting2
 * view, under `../View/Users/accountsetting2.ctp`
 */
	public function accountsetting2() {
		if ($this->Session->read('Auth.User.role_id') == 4) {
			$this->render('accountsetting2');
		}
	}

/**
 * Add a user to the database
 *
 * @api
 * - expecting things about as normal.  Our form was adjusted to reflect what the mobile app is sending
 *      - firstname / lastname (instead of name/lname)
 *      - password_confirmation (instead of verify_password)
 *      - timezone (as seconds offset (normal or negative) from UTC)
 *
 * @return void
 * @access public
 */
	public function add() {
		if (empty($this->request->data)) {
			return $this->loadAddPage();
		}

		// if we haven't been sent one of these options, then someone is trying to mess with us
		if (!isset($this->request->data['RegisterExpertForm']) && !isset($this->request->data['RegisterStudentForm'])) {
			// let's error out and end this now
			throw new MethodNotAllowedException();
		}

		$type = 'student';
		$key = 'RegisterStudentForm';
		if (isset($this->request->data['RegisterExpertForm'])) {
			$type = 'expert';
			$key = 'RegisterExpertForm';
		}

		// scrub our post data to try and protect ourselves a bit against what we might get sent
		// and we only send in the core form values we are interested in
		$semiSafeData = $this->scrubRegistrationData($type, $this->request->data[$key]);

		// now we initialize our user with various values we need
		$semiSafeData = $this->setInitialValues($type, $semiSafeData);

		// translate values from something our XML API has been told to send into something that CakePHP is expecting
		// we adjust fieldnames and try to make things more like Laravel 4 will like down the road
		$semiSafeData = $this->handleTranslationlayer($semiSafeData);

		// now put things back in a form that Cake models understand
		$semiSafeData = array($key => $semiSafeData);

		// and then proceed to handle registration
		$this->handleRegistration($type, $semiSafeData);
	}

	private function loadAddPage() {
		$this->set('title_for_layout', __d('croogo', 'Register'));
		$this->set('type', $this->Session->read('type'));
		return $this->render('add');
	}

/**
 * We try and protect ourselves here a little.  Oh for a nicer framework that would guard us without this being necessary
 *
 * @param $type
 * @param $unsafeData
 */
	private function scrubRegistrationData($type, $unsafeData) {
		// protected against a malicious user overwriting any other user's info while registering
		unset($unsafeData['id']);

		// do a basic conversion of data.  It still isn't that safe, but it's better than nothing at the moment
		// Let's improve this down the road
		$unsafeData['username'] = htmlspecialchars($unsafeData['username']);
		$unsafeData['firstname'] = htmlspecialchars($unsafeData['firstname']);
		$unsafeData['lastname'] = htmlspecialchars($unsafeData['lastname']);

		// @TODO: any other security improvements we want to make?  Still a bunch of fields that are going to go through here
		//$this->request->data['User']['website'] = htmlspecialchars($this->request->data['User']['website']);

		return $unsafeData;
	}

/**
 * @param $semiSafeData
 */
	private function setInitialValues($type, $semiSafeData) {
		// prevent a trivial ability to change a user's role to an admin
		// (previously you could just change the role_id in the form submitted!)
		// instead, we're now hard-coding this and not giving people the option to mess with us

		if ($type == 'expert') {
			$semiSafeData['role_id'] = 2;
		} else {
			$semiSafeData['role_id'] = 4;
		}

		// setup an activation key if needed down the road
		$semiSafeData['activation_key'] = md5(uniqid());

		// activate this account in our system
		$semiSafeData['status'] = 1;

		return $semiSafeData;
	}

/**
 * Translates from what our API and forms are sending us into what Cake and our DB still want
 *
 * @param $semiSafeData
 * @return mixed
 */
	private function handleTranslationLayer($semiSafeData) {
		// Translation layer because our original DB column names were badly chosen
		$semiSafeData['name'] = $semiSafeData['firstname'];
		$semiSafeData['lname'] = $semiSafeData['lastname'];

		// we adjusted the fieldname for verifying passwords to more closely match Laravel 4
		$semiSafeData['verify_password'] = $semiSafeData['password_confirmation'];

		// unset the old values
		unset($semiSafeData['firstname']);
		unset($semiSafeData['lastname']);
		unset($semiSafeData['password_confirmation']);

		return $semiSafeData;
	}

/**
 * Handles the registration process
 *
 * @param $semiSafeData
 */
	private function handleRegistration($type, $semiSafeData) {
		// check to see if we're adding a student or a tutor
		if ($type == 'expert') {
			// we're registering a tutor
			$result = $this->registerExpert($semiSafeData);

			$redirectUrl = array('action' => 'billing');
			$successMessage = __d(
					'croogo', 'You have successfully registered an account. Please enter in your billing info to show up in the search results.'
			);
		}
		// we're registering a student
		else {
			$result = $this->registerStudent($semiSafeData);

			$redirectUrl = array('action' => 'index');
			$successMessage = __d(
					'croogo', 'You have successfully registered an account! On to find a good tutor! :-)'
			);
		}

		if (!$result) {
			Croogo::dispatchEvent('Controller.Users.registrationFailure', $this);

			$message = __d('croogo', 'The User could not be saved. Please, try again.');

			if ($this->RequestHandler->isXml()) {
				$errors = $this->User->invalidFields();
				return $this->sendXmlError(5, current($errors)[0]); // only send back the first validation error
			} else {
				$this->Session->setFlash(
						$message, 'default', array('class' => 'error')
				);
				return $this->loadAddPage();
			}
		}

		Croogo::dispatchEvent('Controller.Users.registrationSuccessful', $this);

		// this was never turned on, I think we can throw it away
		// $this->giveOutTrophiesToFirstOneHundredUsers()

		Croogo::dispatchEvent('Controller.Users.beforeLogin', $this);

		if (!$this->Auth->login($result['User'])) {
			Croogo::dispatchEvent('Controller.Users.loginFailure', $this);
			$message = 'The password you entered is incorrect.';
			if ($this->RequestHandler->isXml()) {
				return $this->sendXmlError(3, $message);
			} else {
				$this->Session->setFlash($message, 'default', array('class' => 'error'), 'auth');
				$this->redirect($this->Auth->loginAction);
			}
		}

		Croogo::dispatchEvent('Controller.Users.loginSuccessful', $this);

		$userId = $this->Session->read('Auth.User.id');
		$this->setOnlineStatus($userId, true);

		// Not sure what this is used for, I think we should get rid of it eventually
		$_SESSION['userid'] = $userId;

		// now, we work on sending back API information if requested
		if ($this->RequestHandler->isXml()) {
			$user = $this->Session->read('Auth.User');

			// we'll translate a bit between what we've got in the system and what we send out
			$this->set('user', $user);
			$this->set('message', $successMessage);

			$this->helpers[] = 'Users.UserXmlTransformer';
		} else {
			$this->Session->setFlash($successMessage, 'default', array('class' => 'success'));
			$this->redirect($redirectUrl);
		}
	}

/**
 * Registers our student or fails out
 *
 * @param $unsafeData
 * @return bool
 */
	private function registerStudent($unsafeData) {
		// $this->RegisterStudentForm->set($unsafeData);
		// @TODO: we'd love to enable validations here eventually
		// for now, we're leaving this off though ...
		// if($this->StudentAccountCreationForm->validates()) {
		// }
//        App::import("Model", "Users.RegisterStudentForm");
//        $student = new RegisterStudentForm();
//        App::import("Model", "Users.User");
		// now convert out of our setup to a user model array
		$unsafeData = $unsafeData['RegisterStudentForm'];
		$data['User'] = $unsafeData;

		// have the user account handle some of the filtering for us
		$this->User->create($data, true);

		return $this->User->save();
	}

/**
 * Registers our tutor or fails out
 *
 * @param $unsafeData
 * @return mixed
 */
	private function registerExpert($unsafeData) {
		// now convert out of our setup to a user model array
		$unsafeData = $unsafeData['RegisterExpertForm'];
		$data['User'] = $unsafeData;

		$this->User->create($unsafeData, true);

		return $this->User->save();
	}

/**
 * Whether the specified user is online or not
 * @param $isOnline
 */
	private function setOnlineStatus($userId, $isOnline) {
		$this->User->id = $userId;
		$this->User->saveField('is_online', $isOnline);
	}

/**
 * @TODO: decide whether we even need this or whether we can just throw it away completely
 *
 * Be forewarned, it's really messy in here, India guy code coming up
 */
	private function giveOutTrophiesToFirstOneHundredUsers() {
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
	}

/**
 * Activate the user (via email confirmation)
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
 * @TODO
 */
	public function edit() {
		
	}

/**
 * Forgot password
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
					array(Configure::read('Site.title'), $this->_getSenderEmail()), $user['User']['email'], __d('croogo', '[%s] Reset Password', Configure::read('Site.title')), 'Users.forgot_password', 'reset password', $this->theme, compact('user', 'activationKey')
			);

			if ($emailSent) {
				$this->Session->setFlash(__d('croogo', 'If the address you provided is associated with an Botangle account, you will receive an email with a password reset link. If you do not receive this email within five minutes, please check your junk mail folder. If you still cannot locate the email, please reach out to contactus@botangle.com.'), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'login'));
			} else {
				$this->Session->setFlash(__d('croogo', 'An error occurred. Please try again.'), 'default', array('class' => 'error'));
			}
		}
	}

/**
 * Recover Password
 *
 * @TODO
 */
	function passwordrecovery() {
		
	}

/**
 * Reset password
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
 * @api
 * @return boolean
 * @access public
 */
	public function login() {
		$this->set('title_for_layout', __d('croogo', 'Log in'));
		if ($this->request->is('post')) {
			Croogo::dispatchEvent('Controller.Users.beforeLogin', $this);

			if ($this->Auth->login()) {
				Croogo::dispatchEvent('Controller.Users.loginSuccessful', $this);

				$userId = (int) $this->Session->read('Auth.User.id');
				$this->setOnlineStatus($userId, true);

				// not sure what this is used for, it'd be nice to get rid of it
				$_SESSION['userid'] = $userId;

				// API: handle our API info and send back info
				if ($this->RequestHandler->isXml()) {
					$user = $this->Session->read('Auth.User');

					// we'll translate a bit between what we've got and our system
					$this->set('id', $user['id']);
					$this->set('role', $user['Role']['alias']);
					$this->set('firstname', $user['name']);
					$this->set('lastname', $user['lname']);
					$this->set('profilepic', $user['profilepic']);
					$this->set('_rootNode', 'user');
					$this->set('_serialize', array('id', 'role', 'firstname', 'lastname', 'profilepic'));
				} else {
					$this->redirect($this->Auth->redirectUrl());
				}
			} else {
				Croogo::dispatchEvent('Controller.Users.loginFailure', $this);
				if ($this->RequestHandler->isXml()) {
					return $this->sendXmlError(1, "The password you entered is incorrect");
				} else {
					$this->Session->setFlash('The password you entered is incorrect.', 'default', array('class' => 'error'), 'auth');
					$this->redirect($this->Auth->loginAction);
				}
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

		$userId = (int) $this->Session->read('Auth.User.id');
		$this->setOnlineStatus($this->Session->read('Auth.User.id'), false);

		$data = array();
		$data['User']['id'] = $userId;

		$this->redirect($this->Auth->logout(), null, false); // we don't want to die as soon as the redirect is over
		Croogo::dispatchEvent('Controller.Users.afterLogout', $this, $data);
	}

/**
 * View
 * @api
 * @param string $username
 * @return void
 * @access public
 */
	public function view($username = null) {
		if ($username == null) {
			$username = $this->Auth->user('username');
		}
		$user = $this->User->find('first', array(
			'conditions' => array(
				'username' => $username,
				// we want to make sure to leave out our admin user(s) from this list
				'NOT' => array(
					'role_id' => 1,
				)
			)
		));

		if (!isset($user['User']['id'])) {

			// API: handle our API info and send back info
			if ($this->RequestHandler->isXml()) {
				return $this->sendXmlError(2, "User not found");
			} else {
				$this->Session->setFlash(__d('croogo', 'Invalid User.'), 'default', array('class' => 'error'));
				$this->redirect('/');
			}
		}

		$this->set('title_for_layout', $user['User']['name']);

		$userRate = $this->UserRate->find('first', array('conditions' => array('UserRate.userid' => $user['User']['id'])));

		$userRating = $this->Review->find('first', array('conditions' => array('Review.rate_to' => $user['User']['id']),
			'fields' => array('avg(rating) as avg'),
		));
		$userReviews = $this->Review->find('all', array(
			'joins' => array(
				array(
					'table' => 'users',
					'alias' => 'User',
					'type' => 'INNER',
					'conditions' => array(
						"User.id = Review.rate_by"
					)),
			),
			'fields' => array('*'),
			'conditions' => array('Review.rate_to' => $user['User']['id'])
		));
		$lessonClasscount = $this->Lesson->find('all', array('conditions' => array('student' => $user['User']['id'], 'is_confirmed' => 1),
			'fields' => array('count(id) as totalrecords,sum(duration) as totalduration'))
		);

		$userstatus = $this->Mystatus->find('all', array('conditions' => array('Mystatus.created_by_id' => $user['User']['id']), 'order' => array('Mystatus.created' => 'desc')));

		/*
		  $log = $this->User->getDataSource()->getLog(false, false);
		  debug($log); */

		App::import("Model", "Users.Lesson");
		$lessonRepetitions = Lesson::$repetitionValues;

		$this->set(compact('user', 'userRate', 'userRating', 'userReviews', 'lessonClasscount', 'lessonRepetitions', 'userstatus'));

		// if this person we're viewing is a student, then show the student view
		// @TODO: get this working, right now the view is all hard-coded and wouldn't be good to show
		// if ($user['User']['role_id'] == 4) {
		//    $this->render('view2');
		//}
	}

	protected function _getSenderEmail() {
		return 'croogo@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
	}

/*	 * ************************ BEGIN BILLING FUNCTIONS ************************* */

/**
 * Billing - the main billing function
 *
 * This contains the Stripe information for sending/receiving payments.
 *
 * @package billing
 */
	public function billing($layout = null) {
		$isMobile = false;
		if ($layout != null) {
			$this->layout = $layout;
			$isMobile = true;
		}

		$id = $this->Session->read('Auth.User.id');

		if (!empty($this->request->data)) {

			// Continue if POST data is not empty...
			if (isset($this->request->data['UserRate']['pagetype']) && ($this->request->data['UserRate']['pagetype'] == 'billing')) {
				// We assume that the user wants to save some data if the Billing page is not `student_setup`...
				if ($this->UserRate->save($this->request->data)) {
					$this->Session->setFlash(__d('croogo', 'Information has been updated'), 'default', array('class' => 'success'));
					$this->redirect(array('action' => 'billing'));
				} else {
					$this->Session->setFlash(__d('croogo', 'Information can not be updated. Please, try again.'), 'default', array('class' => 'error'));
				}
			}

			if (isset($this->request->data['Billing']['pagetype']) && $this->request->data['Billing']['pagetype'] == 'student_setup') {
				$this->handleStudentCustomerAccountCreation();
			}
		}

		$this->set('ratedata', $this->UserRate->find('first', array('conditions' => array('UserRate.userid' => $this->Session->read('Auth.User.id')))));
		$this->set('title_for_layout', __d('croogo', 'Billing'));

		// @TODO: shift out of this billing action as soon as we can.  Have to work out the details on ARO/ACO first,
		// we'll need to update the info about the appropriate redirect uri / webhook uri with Stripe too
		if (isset($_GET['code'])) { // Redirect w/ code from the Stripe Connect OAuth
			$this->billing_connect();
		}

		// load up the user we need access to regardless of what info we'll show
		$User = $this->User->find('first', array('conditions' => array('User.id' => $id)));

		// role_id == 4 is student. Finally figured that out.
		// If the student is the one currently trying to pay the tutor:
		if ($this->Session->read('Auth.User.role_id') == 4) {
			$roleid = 2;

			$needs_payments_setup = true;

			if ($User['User']['stripe_customer_id'] != '') {
				$needs_payments_setup = false;
			}

			$this->set(compact('needs_payments_setup', 'roleid', 'User'));
			$this->set('paymentamount', $this->Session->read("paymentamount"));
			$this->set('publishable_key', Configure::read('Stripe.publishable_key'));


			if ($isMobile) {
				return $this->render('Users/billing/student_mobile');
			} else {
				$this->render('Users/billing/student');
			}
		} else {

			$stripe_setup = false;
			if (isset($User['User']['stripe_user_id']) &&
					$User['User']['stripe_user_id'] != "" &&
					$User['User']['access_token'] != "" &&
					$User['User']['stripe_publishable_key'] != "" &&
					$User['User']['refresh_token'] != ""
			) {
				$stripe_setup = true;
			}

			$stripe_client_id = Configure::read('Stripe.client_id');

			$this->set(compact(
							'stripe_client_id', 'stripe_setup', 'User'
			));
		}
	}

/**
 * Split out our student customer account creation as it's really a separate method showing up under the
 * same url as a completely different setup
 */
	private function handleStudentCustomerAccountCreation() {
		$id = $this->Session->read('Auth.User.id');

		// Store the user object in $user according to logged-in $id
		$user = $this->User->find('first', array('conditions' => array('User.id' => $id)));

		// Next, grab the token that represents our customer's credit card in the Stripe system
		$token = $_POST['stripeToken'];

		// The Stripe plugin automatically handles data validation and error handling
		// See docs here: https://github.com/chronon/CakePHP-StripeComponent-Plugin
		// Create a customer for our student using our stripe component
		// we're going to create a user for them in our app (not our tutor's) per this shared customer's page:
		// https://stripe.com/docs/connect/shared-customers
		// this makes it easier for us to bill them when they are have a lesson with someone else in the future
		$stripeComponent = $this->Components->load('Stripe.Stripe');
		$result = $stripeComponent->customerCreate(array(
			'card' => $token,
			// generate a unique identifier for this customer
			'description' => $user['User']['id'] . '_' . $user['User']['name'] . '_' . $user['User']['lname'],
			'email' => $user['User']['email'],
		));

		// now check the results we get back from Stripe. if this isn't an array, then we've got errors
		if (!is_array($result)) {

			// @TODO: confirm whether these errors are generic enough to show to the general public, I think they are
			$message = $result;
			if ($this->request->is('ajax')) {
				$this->sendJsonError($message);
			} else {
				$this->Session->setFlash(__d('croogo', $result), 'default', array('class' => 'error'));

				// redirect back to the page again and ask for their data again
				// @TODO: it'd be nice if we'd auto-populate their info with what we know
				// but we threw a bunch of it away :-)
				// In reality, we shouldn't have too many errors here as long as the server is setup ok
				$this->redirect(array('action' => 'billing'));
			}
		} else {

			// then let's save the user account to the DB so we can refer to it again in the future
			$user['User']['stripe_customer_id'] = $result['stripe_id']; // our Stripe customer id to refer to this person with again
			// @TODO: we should really make sure this saves in the future ...
			$this->User->save($user);

			Croogo::dispatchEvent('Controller.Users.studentStripeAccountSetup', $this);

			// Now let's see if we're in the middle of an initial lesson setup (instead of someone proactively dealing with setting up their account)
			// if we are, we'll need to finish up our lesson setup
			if ($this->Session->read('initial_lesson_setup')) {
				$user_id_to_message = $this->Session->read('new_lesson_user_id_to_message');
				$lesson_id = $this->Session->read('new_lesson_lesson_id');

				// clear up our session so we don't have anything else weird happen to this person later on
				$this->Session->delete('new_lesson_lesson_id');
				$this->Session->delete('new_lesson_user_id_to_message');

				// a redirect and session flash gets posted here
				$this->postLessonAddSetup($lesson_id, $user_id_to_message);
			} else {
				$this->Session->setFlash(__d('croogo', "You're all setup for payments now.  Thanks!"), 'default', array('class' => 'success'));
			}
		}
	}

/**
 * Handles the Stripe Connect system so we properly hook our tutor's Stripe account up with our application
 *
 * This then allows us to bill for a tutor and take a cut of the proceeds ourselves.
 */
	public function billing_connect() {
		$id = $this->Session->read('Auth.User.id');

		// Begin actual Stripe integration with Stripe Connect
		// https://stripe.com/docs/connect
		if (isset($_GET['code'])) { // Redirect w/ code from the Stripe Connect OAuth
			$token_request_body = array(
				// use our own Stripe private key, either test or production depending on our environment
				'client_secret' => Configure::read('Stripe.secret'),
				'code' => $_GET['code'],
				'grant_type' => 'authorization_code'
			);

			// @TODO: let's abstract this out into Guzzle long-term, it'd be nice to be able to have auto-retries
			// so we don't have to bug our tutors again if things fail for some reason the first time
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

			// check to make sure we're not having errors
			if (isset($resp['error'])) {

				// possible problems
				// invalid_grant (if this is happening, it's potentially more severe)
				// invalid_request, unsupported_grant_type, invalid_scope, unsupported_response_type
				// @TODO: improve the error number passed, see about making it different for each of the above items
				$this->handleStripeError($resp, 500); // we'll make these errors in the 500 range
			}

			// @TODO: does Stripe notify us if a tutor revokes access to our app?
			// Ideally we'd need to know this for the UI so we can display that they need to hook back up again
			// Tweeted their support, we'll see if they respond
			// if we only have read access, whoa, we've got errors.  We need read/write to handle transactions
			if ($resp['scope'] != 'read_write') {
				// Error 600: permissions problems
				$this->handleStripeError($resp, 600, "Sorry, we need read/write privileges in order to handle transactions for you.");
			}

			// @TODO: if we're not in debug mode, then let's check this
//            if($resp['livemode'] == false) {
//                    Error 601: wrong payment mode, we need to be in live mode instead of debug mode
//                    $this->handleStripeError($resp, 601);
//            }

			if (!isset($resp['stripe_user_id']) ||
					!isset($resp['access_token']) ||
					!isset($resp['stripe_publishable_key']) ||
					!isset($resp['refresh_token'])
			) {
				// Error 602: permissions problems
				$this->handleStripeError($resp, 600, "Sorry, we need read/write privileges in order to handle transactions for you.");
			}


//            $resp = array(
//                'refresh_token'             => 'random characters',
//                'access_token'              => 'sk_test_randomcharacters',
//                'stripe_user_id'            => 'random_id',
//                'stripe_publishable_key'    => 'pk_test_random_publishable_key_characters',
//            );

			$data = array(
				'id' => $id,
				'stripe_user_id' => $resp['stripe_user_id'], // not really if sure we need to keep this, but we will for now
				'access_token' => $resp['access_token'], // used when billing for a tutor instead of a normal Stripe secret API key
				'stripe_publishable_key' => $resp['stripe_publishable_key'], // used when we bill students for a tutor (it's visible on the frontend)
				'refresh_token' => $resp['refresh_token'], // used to generate test access tokens in production
			);
			$this->User->save($data);

			Croogo::dispatchEvent('Controller.Users.tutorStripeAccountSetup', $this);

			$this->Session->setFlash(__d('croogo', "We've connected your account with Stripe successfully."), 'default', array('class' => 'success'));

			$this->redirect(array('action' => 'billing'));
		}
	}

/**
 * @TODO: Move this into a separate component
 */
	private function handleStripeError($response, $errorNumber, $message = 'We had problems connecting to Stripe. Please try again.') {
		$this->Session->setFlash(__d('croogo', $message . " Error #") . $errorNumber, 'default', array('class' => 'error'));

		// don't redirect and die, we want a bit more control over things
		$this->redirect('billing', null, false);
		$this->response->send();

		// @TODO: log things, adjusting based on the error number?
		// stop once we're done logging things
		$this->_stop();
	}

/**
 * Charge function
 *
 * Handles payment with Stripe for a pre-setup customer account
 *
 * @access protected
 * @param  $tutorId   : The specific Tutor id we should be sending money to
 * @param  $userId   : The specific User id we should be charging
 * @param  $amount   : Payment amount
 * @param  $fee      : amount we take as our cut
 */
	protected function charge($tutorId, $userId, $amount, $fee) {

		// we need our tutor access token (from stripe) so we can add application fees and send money to this person
		$tutor = $this->User->find('first', array('conditions' => array('User.id' => $tutorId)));

		// we need our customer account ID (from stripe) so we know what credit card to charge
		$user = $this->User->find('first', array('conditions' => array('User.id' => $userId)));

		// The Stripe plugin automatically handles data validation and error handling
		// See docs here: https://github.com/chronon/CakePHP-StripeComponent-Plugin

		$charge = array(
			'stripeCustomer' => $user['User']['stripe_customer_id'],
			'amount' => $amount,
			'application_fee' => $fee,
			'currency' => 'usd',
		);

		return $this->Stripe->connectCharge($tutor['User']['access_token'], $charge);
	}

/**
 * Search function
 *
 * Searches for lessons given certen parameters of experience, online status,
 * subjects, and user input value.
 */
	public function search($categoryname = null, $online = null) {

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
			/* "format(teaching_experience,2) > '".$startExperience."','".$endExperience."'" */
			/* $otherconditions = array_merge($otherconditions,
			  array(
			  'AND' => array(
			  array('format(User.teaching_experience,2) >=' => $startExperience,
			  'format(User.teaching_experience,2) <=' => $endExperience
			  ),

			  )
			  )
			  ); */
			$otherconditions = array_merge($otherconditions, array(
				'format(User.teaching_experience,2) BETWEEN' => intval($startExperience - 1), intval($endExperience + 1)
					)
			);
		}

		if (isset($online) && $online != null) {
			$otherconditions = array_merge($otherconditions, array('is_online' => 1));
		}

		/* $this->paginate['User']['conditions']  = array("User.status"=>1,"User.role_id"=>2,array('OR'=>array("User.username LIKE '%$searchValue%'","User.extracurricular_interests LIKE '%$searchValue%'","User.subject LIKE '%$searchValue%'","User.qualification LIKE '%$searchValue%'"))); */

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
	}

/**
 * lessons function
 *
 * @api
 *
 * Lists the active, upcoming, and past lessons according to logged-in user,
 * insofar as the user is a tutor.
 */
	public function lessons() {

		$userId = (int) $this->Session->read('Auth.User.id');
		$roleId = (int) $this->Session->read('Auth.User.role_id');

		$upcomingLessons = $this->Lesson->upcomingLessons($userId, $roleId);
		$activeLessons = $this->Lesson->activeLessons($userId, $roleId);
		$pastLessons = $this->Lesson->pastLessons($userId, $roleId);

		if ($this->RequestHandler->isXml()) {
			$this->helpers[] = 'Users.LessonXmlTransformer';
		}

		$this->set('activeLessons', $activeLessons);
		$this->set('upcomingLessons', $upcomingLessons);
		$this->set('pastLessons', $pastLessons);

		$this->render('lessons');
	}

/**
 * White board data
 *
 * Selects lesson information from the database, and begins the payment
 * workflow. Presumably, this would also manage the whiteboard content, once
 * the feature is in its fully-functional form.
 *
 * @package billing
 */
	public function whiteboarddata($lessonid = null) {
		$lesson = $this->Lesson->find('first', array('conditions' => array('id' => $lessonid)));
		$lessonPayment = $this->LessonPayment->find('first', array('conditions' => array('lesson_id' => $lessonid)));

		$lesson_id = (int) $lesson['Lesson']['id'];
		$role_id = (int) $this->Session->read('Auth.User.role_id');

		// if our user doesn't have a role id, it's going to break all sorts of things
		// let's check and redirect to the login page if needed
		if ($role_id == 0) {
			$this->redirect(
					Router::url(
							array(
								'plugin' => 'users',
								'controller' => 'users',
								'action' => 'login'
							)
					)
			);
			throw new UnauthorizedException("Sorry, you need to login first");
		}

		// handle all our video stuff with Opentok
		$opentok_session_id = $lesson['Lesson']['opentok_session_id'];

		// if our session id is blank, then we're going to need to try to generate an opentok session id *right now*
		// as we need it almost immediately
		if ($opentok_session_id == "") {

			// if we properly generate a session id, then let's save it to the DB
			// and move on with our lives
			if ($returnVal = $this->generateOpenTokSessionId()) {
				$opentok_session_id = $returnVal;
				$lesson['Lesson']['opentok_session_id'] = $returnVal;
				$this->Lesson->save($lesson);
			} else {
				// we've got lesson-stopping issues right now, let's throw an error and have them try again
				throw new InternalErrorException("Could not load our video system up for some reason. Please try again or contact us for further assistance.");
			}
		}

		$this->OpenTok = $this->Components->load('OpenTok', Configure::read('OpenTokComponent'));
		$opentok_api_key = $this->OpenTok->apiKey;
		$opentok_token = $this->OpenTok->generateToken($opentok_session_id);

		$username = $this->Session->read('Auth.User.username');

		$this->set(compact(
						'lesson', 'lessonPayment', 'lesson_id', 'opentok_api_key', 'opentok_session_id', 'opentok_token', 'role_id', 'username'
		));
	}

/**
 * Change lesson
 *
 * Manages creating and saving lessons
 */
	public function changelesson($lessonid = null) {
		if (!empty($this->request->data)) {
			$lesson = $this->Lesson->find('first', array('conditions' => array('id' => $lessonid)));

			// copy out two key pieces of information that we're going to leave alone without room for change
			$studentId = $lesson['Lesson']['student'];
			$tutorId = $lesson['Lesson']['tutor'];

			// @TODO: this could be a real security risk, potentially, we're allowing total overwrite from the UI
			$data = array_merge($lesson, $this->request->data);
			unset($this->request->data['Lesson']);

			$data['Lesson']['add_date'] = date('Y-m-d');

			// put back our key pieces of data
			$data['Lesson']['student'] = $studentId;
			$data['Lesson']['tutor'] = $tutorId;

			if ($this->Auth->user('role_id') == 4) {
				$data['Lesson']['is_confirmed'] = 0;

				// the tutor hasn't confirmed this change yet, but the student has
				$data['Lesson']['readlessontutor'] = '0';
				$data['Lesson']['readlesson'] = '1';

				// the student made the last change and the tutor didn't
				$data['Lesson']['laststatus_student'] = 0;
				$data['Lesson']['laststatus_tutor'] = 1;
			} else if ($this->Auth->user('role_id') == 2) {
				$data['Lesson']['is_confirmed'] = 0;

				// the student hasn't confirmed this change yet, but the tutor has
				$data['Lesson']['readlesson'] = '0';
				$data['Lesson']['readlessontutor'] = '1';

				// the tutor made the last change and the student didn't
				$data['Lesson']['laststatus_tutor'] = 1;
				$data['Lesson']['laststatus_student'] = 0;
			}

			if ($this->Lesson->save($data)) {
				$lessondid = $this->Lesson->getLastInsertId();
				$sentByid = $data['Lesson']['tutor'];
				if (!isset($data['Lesson']['parent_id'])) {
					unset($data['Lesson']);
					$data['Lesson']['parent_id'] = $lessondid;
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
				$this->request->data['Usermessage']['body'] = " Request to Change Lesson. Please click here to read.";
				$this->request->data['Usermessage']['parent_id'] = 0;
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
		$userlessonconditionsfield = "student";
		$readconditons = "readlessontutor";
		if ($this->Session->read('Auth.User.role_id') == 4) {
			$userconditionsfield = "student";
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
		/* $log = $this->User->getDataSource()->getLog(false, false);
		  debug($log);
		 */
		//	 $this->autoRender = false;
		$this->layouts = false;
		$this->set(compact('Lesson'));
	}

/**
 * Lessons add
 *
 * @api
 * - data[Lesson][student_view] (1 = a student proposal, 0 = tutor proposal)
 * - data[Lesson][username] (if a tutor proposal)
 * - data[Lesson][user_id] (if a student proposal)
 * - data[Lesson][duration] (integer, in minutes)
 * - data[Lesson][repetition] (0 = Single Lesson, 1 = Daily, 2 = Weekly)
 * - data[Lesson][lesson_date]
 * - data[Lesson][lesson_time]
 * - data[Lesson][subject]
 * - data[Lesson][notes]
 *
 * Binds a student's proposed lesson to a tutor's account. Also notifies a student and tutor with messages about their new lesson
 */
	public function lessons_add() {
		if (!empty($this->request->data)) {

			// @TODO: ideally we'd validate things before we start trying to mess with stuff here ...

			$user_id_to_message = null;
			$id = null;
			$student_needs_stripe_account_setup = false;
			$lesson = $this->request->data;

			if (!isset($lesson) || !isset($lesson['Lesson']) || !isset($lesson['Lesson']['student_view'])) {
				// @TODO: throw an exception, we're expecting it and need it
			}

			if ($lesson['Lesson']['student_view'] != 0 && $lesson['Lesson']['student_view'] != 1) {
				// @TODO: throw an exception, someone is doing something weird here
			}

			// we adjust our public API values into what we expect internally
			// @TODO: let's change this out long-term so the fields that our DB field names match what our view / API send in
			// the view / API names are much, **much** clearer
			// we'll shift from a nice clean integer value for our repetition setup to an ugly string we'd like to get rid of
			if (isset($lesson['Lesson']['repetition'])) {

				$selectedRepetition = $lesson['Lesson']['repetition'];

				App::import("Model", "Users.Lesson");
				if (isset(Lesson::$repetitionValues[$selectedRepetition])) {
					$lesson['Lesson']['repet'] = Lesson::$repetitionValues[$selectedRepetition];
				} else {
					$lesson['Lesson']['repet'] = 'Single lesson';
				}
				unset($lesson['Lesson']['repetition']);
			}

			// shift from a nice clean integer (minute-based duration) to a nasty string (.5, 1.0, etc)
			if (isset($lesson['Lesson']['duration'])) {

				// if we're dealing with a half hour setup, our old data displayed it as .5 instead of 0.5
				// so we should mimic that for now
				$removeLeadingZero = false;
				if ($lesson['Lesson']['duration'] == 30) {
					$removeLeadingZero = true;
				}

				// converts to half-hour increments which our DB will convert to strings
				$lesson['Lesson']['duration'] = ($lesson['Lesson']['duration'] / 60);

				if ($removeLeadingZero) {
					$lesson['Lesson']['duration'] = substr($lesson['Lesson']['duration'], 1);
				}
			}


			if ($this->addLesson($lesson, $user_id_to_message, $id, $student_needs_stripe_account_setup)) {

				// if we need billing info, then we'll need to put our lesson message and session id generation
				// on hold and work on the billing stuff instead
				if ($student_needs_stripe_account_setup) {
					$this->Session->write('initial_lesson_setup', true);
					$this->Session->write('new_lesson_user_id_to_message', $user_id_to_message);
					$this->Session->write('new_lesson_lesson_id', $id);

					// redirect to our billing page to get setup with Stripe
					if ($this->RequestHandler->isXml()) {

						// send back lesson information
						// and also info on the redirect we're about to make
						$redirect = array(
							'url' => Router::url(
									array(
								'plugin' => 'users',
								'controller' => 'users',
								'action' => 'billing',
								'layout' => 'mobile-billing',
									), true
							),
						);

						$this->set('lesson', $lesson);
						$this->set('redirect', $redirect);
						return $this->render('lesson_created');
					} else {
						$this->redirect(array('action' => 'billing'));
					}
				} else {
					// otherwise, let's handle the post lesson add setup
					$this->postLessonAddSetup($id, $user_id_to_message);
				}
			} else {
				$message = __d('croogo', 'The Lesson could not be saved. Please try again.');

				if ($this->RequestHandler->isXml()) {
					return $this->sendXmlError(4, $message);
				} else {
					$this->Session->setFlash(
							$message, 'default', array('class' => 'error')
					);
				}
			}
		}

		// send this information in to our base tutor create view
		App::import("Model", "Users.Lesson");
		$this->set('lessonRepetitions', Lesson::$repetitionValues);

		// @TODO: confirm, but I think this is dead code at this point
		if (isset($this->request->params['pass'][0]) && $this->request->params['pass'][0] == 'ajax') {
			$this->request->params['pass'][1];
			$Tutorinfo = $this->User->find('first', array('conditions' => array('User.id' => $this->request->params['pass'][1])));
			$this->set(compact('Tutorinfo'));
			$this->autoRender = false;
			$this->layouts = false;
			$this->render('lessoncreate');
		}
	}

/**
 * Add a lesson for us
 * Oh, it's ugly.  Is there anyway we can improve this over time and move it to a model that handles this stuff?
 *
 * Lesson[user_id] is used for a student proposal (we already know the tutor id)
 * Lesson[username] is used for a tutor proposal (the tutor enters the student's username)
 *
 * @param $data
 * @param $user_id_to_message
 * @param $id
 * @param $student_needs_stripe_account_setup
 * @return bool
 */
	private function addLesson(&$data, &$user_id_to_message, &$id, &$student_needs_stripe_account_setup) {
		$currentUserId = (int) $this->Auth->user('id');

		// these values are common to both setups
		$data['Lesson']['add_date'] = date('Y-m-d');
		$data['Lesson']['is_confirmed'] = '0';

		// this gets run when a student proposes a lesson to a tutor
		if ($data['Lesson']['student_view'] == 1) {

			$user_id_to_message = (int) $data['Lesson']['user_id'];

			$data['Lesson']['tutor'] = $user_id_to_message;
			$data['Lesson']['student'] = $currentUserId;
			$data['Lesson']['readlesson'] = '1';
			$data['Lesson']['readlessontutor'] = '0';
			$data['Lesson']['laststatus_tutor'] = 0;
			$data['Lesson']['laststatus_student'] = 1;

			$student = $this->User->find('first', array('conditions' => array('User.id' => $this->Auth->user('id'))));

			// if we don't have a stripe customer id for this student, then we need to request billing information
			if (!isset($student['User']['stripe_customer_id']) || $student['User']['stripe_customer_id'] == "") {
				$student_needs_stripe_account_setup = true;
			}
		} else {
			// this gets run when a tutor creates a lesson to do with a student on the /users/createlessons page
			// we need to look up the username the tutor types in, and then grab the appropriate user id
			// @TODO: work on handling errors here, we're going to have them and they need to be handled
			$student = $this->User->find('first', array('conditions' => array('username' => $data['Lesson']['username'])));
			$studentId = $student['User']['id'];

			// we'll want to message this person below
			$user_id_to_message = (int) $studentId;

			$data['Lesson']['tutor'] = $currentUserId;
			$data['Lesson']['student'] = (int) $studentId;
			$data['Lesson']['readlesson'] = '0';
			$data['Lesson']['readlessontutor'] = '1';
			$data['Lesson']['laststatus_tutor'] = 1;
			$data['Lesson']['laststatus_student'] = 0;
		}

		// clean out our view specific variable, we don't want to attempt to save to the DB
		unset($data['Lesson']['student_view']);

		$this->Lesson->create($data);
		if ($this->Lesson->save($data, false)) {
			$id = $this->Lesson->getLastInsertId();
			$data['Lesson']['id'] = $id;

// @TODO: work out what the below was supposed to do
// Best guess is that it was supposed to allow duplicating lessons or editing a lesson and then creating a new
// record.  But we're going to need to re-work that, either I (David) broke it when refactoring earlier (possible)
// or it never worked in the first place (also possible)
//			if (isset($data['Lesson']['parent_id']) && $data['Lesson']['parent_id'] != "") {
//
//				unset($data['Lesson']);
//				$data['Lesson']['parent_id'] = $data['Lesson']['parent_id'];
//				$this->Lesson->save($data);
//			}
//			if (!isset($data['Lesson']['parent_id'])) {
//				unset($data['Lesson']);
//				$data['Lesson']['parent_id'] = $id;
//				$this->Lesson->save($data);
//			}

			Croogo::dispatchEvent('Controller.Users.lessonAdded', $this);

			return true;
		} else {
			return false;
		}
	}

/**
 * Called so we can complete adding a lesson to our system as needed
 */
	private function postLessonAddSetup($lesson_id, $user_id_to_message) {
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
			$this->redirect(array('action' => 'lessons'));
		}
	}

/**
 * Ideally we might make this an action and redirect, but at the moment our actions are a bit messy with ACL issues
 * so we'll do this here for now
 *
 * @param integer $user_id_to_message
 */
	private function addLessonMessage($user_id_to_message) {
		$data = array();
		$data['Usermessage']['sent_from'] = $this->Auth->user('id');
		$data['Usermessage']['sent_to'] = $user_id_to_message;
		$data['Usermessage']['readmessage'] = 0;
		$data['Usermessage']['date'] = date('Y-m-d H:i:s');
		$data['Usermessage']['body'] = " Our Lesson is setup now. Please click here to read."; // @TODO: fix the body so it's clickable
		$data['Usermessage']['parent_id'] = 0;

		$this->Usermessage->save($data);
		$lastId = $this->Usermessage->getLastInsertId();

// @TODO: what were we planning to do with this line?  parent_id is always hard-coded to zero above ...
//        if ($this->request->data['Usermessage']['parent_id'] == 0) {
		$this->Usermessage->query(" UPDATE `usermessages` SET parent_id = '" . $lastId . "' WHERE id = '" . $lastId . "'");
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
	private function sendLessonProposal($lesson_id, $user_id) {

		$lesson = $this->Lesson->find('first', array('conditions' => array('Lesson.id' => $lesson_id)));

		if (count($lesson) == 0) {
			$this->log("Couldn't properly retrieve lesson information prior to sending an email notification about a new lesson.", LOG_EMERG);
			return;
		}

		if ($lesson['Lesson']['student'] == $user_id) {
			$contact = $this->User->find('first', array('conditions' => array('User.id' => $lesson['Lesson']['student'])));
			$lessonRequestor = $this->User->find('first', array('conditions' => array('User.id' => $lesson['Lesson']['tutor'])));
		} else {
			$contact = $this->User->find('first', array('conditions' => array('User.id' => $lesson['Lesson']['tutor'])));
			$lessonRequestor = $this->User->find('first', array('conditions' => array('User.id' => $lesson['Lesson']['student'])));
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
	private function generateTwiddlaSessionId() {

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
	private function generateOpenTokSessionId() {
		$this->OpenTok = $this->Components->load('OpenTok', Configure::read('OpenTokComponent'));
		return $this->OpenTok->generateSessionId();
	}

/**
 * Search student
 *
 * Searches for a certain student in the database by usename (I think)
 */
	public function searchstudent() {
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
		/* $log = $this->User->getDataSource()->getLog(false, false);
		  debug($log); */
		$this->autoRender = false;
		$this->layouts = false;
	}

/**
 * Lesson Reviews
 *
 * Handles reviews of how the lesson went, ex-post-facto.
 */
	public function lessonreviews($lessonid = null) {
		if (!empty($this->request->data)) {
			$this->request->data['Review']['add_date'] = date('Y-m-d H:i:s');

			if ($this->Review->save($this->request->data)) {
				$this->redirect(array('action' => 'lessons'));
			}
		}
		$Lesson = $this->Lesson->find('first', array('conditions' => array('id' => $lessonid)));
		$this->set(compact('Lesson'));
	}

/**
 * Confirmed by tutor
 *
 * Handles the confirmation of a lesson. A student proposes a lesson to the tutor,
 * then the tutor confirms it. This function then establishes the twiddla
 * meeting details.
 *
 * @package billing
 */
	public function confirmedbytutor($lessonid = null) {
		$data = $this->Lesson->find('first', array('conditions' => array('id' => (int) $lessonid)));

		// if this person is the tutor and they are the one set as the tutor on this lesson
		// then we want to set things up and confirm
		if ($this->Session->read('Auth.User.role_id') == 2 && $data['Lesson']['tutor'] == $this->Session->read('Auth.User.id')
		) {

			$tutor = $this->User->find('first', array('conditions' => array('User.id' => (int) $this->Session->read('Auth.User.id'))));

			// check to see if the tutor is allowed to confirm this lesson yet or not
			// if they don't have a Stripe account, then we want to enforce that, as we can't handle payments at the end of the lesson
			// without it
			if (count($tutor) > 0 && isset($tutor['User']) && $tutor['User']['stripe_user_id'] == '') {
				$this->Session->setFlash(__d('botangle', "Sorry, we need you to setup an account with Stripe before you can confirm a lesson."), 'default', array('class' => 'error'));
				$this->redirect(array('action' => 'billing'));
			}

			$data['Lesson']['readlessontutor'] = 1;
			$data['Lesson']['is_confirmed'] = 0;
			$send_confirmation_to_user_id = $data['Lesson']['student'];
		}
		// if this is a student who had a lesson created for them that they need to confirm
		// then we want to set things up and confirm
		elseif ($this->Session->read('Auth.User.role_id') == 4 && $data['Lesson']['student'] == $this->Session->read('Auth.User.id')
		) {
			$data['Lesson']['readlesson'] = 1;
			$data['Lesson']['is_confirmed'] = 0;
			$send_confirmation_to_user_id = $data['Lesson']['tutor'];
		} else {
			throw new CakeException('Sorry, something went badly wrong, please try again.');
		}

		if ($data['Lesson']['readlesson'] == 1 && $data['Lesson']['readlessontutor'] == 1) {
			$data['Lesson']['is_confirmed'] = 1;
		}

		// generate our appropriate session ids if we don't already have from when the student suggested the lesson
		// it's possible that these values will end up being blank instead of what we need
		// so we're going to need to check these values before the lesson actually starts as well
		if ($data['Lesson']['twiddlameetingid'] == '' || $data['Lesson']['twiddlameetingid'] == 0) {
			if ($returnVal = $this->generateTwiddlaSessionId()) {
				$data['Lesson']['twiddlameetingid'] = $returnVal;
			}
		}
		if ($data['Lesson']['opentok_session_id'] == '') {
			if ($returnVal = $this->generateOpenTokSessionId()) {
				$data['Lesson']['opentok_session_id'] = $returnVal;
			}
		}

		$this->Lesson->id = (int) $data['Lesson']['id'];
		$this->Lesson->save($data);

		$this->redirect(array('action' => 'lessons'), null, false);

		// send an email confirmation after we've sent the user on to the next page
		$this->sendLessonConfirmation((int) $data['Lesson']['id'], $send_confirmation_to_user_id);
	}

/**
 * Sends a proposed lesson email to the receipient in addition to a message through the system
 *
 * @param $lesson_id
 * @param $user_id
 * @return bool
 */
	private function sendLessonConfirmation($lesson_id, $user_id) {

		$lesson = $this->Lesson->find('first', array('conditions' => array('Lesson.id' => $lesson_id)));

		if (count($lesson) == 0) {
			$this->log("Couldn't properly retrieve lesson information prior to sending an email notification about a confirmed lesson.", LOG_EMERG);
			return;
		}

		if ($lesson['Lesson']['student'] == $user_id) {
			$contact = $this->User->find('first', array('conditions' => array('User.id' => $lesson['Lesson']['student'])));
			$lessonConfirmer = $this->User->find('first', array('conditions' => array('User.id' => $lesson['Lesson']['tutor'])));
		} else {
			$contact = $this->User->find('first', array('conditions' => array('User.id' => $lesson['Lesson']['tutor'])));
			$lessonConfirmer = $this->User->find('first', array('conditions' => array('User.id' => $lesson['Lesson']['student'])));
		}

		if (count($contact) == 0 || count($lessonConfirmer) == 0) {
			$this->log("Couldn't retrieve tutor / student info prior to sending an email notification about a confirmed lesson.", LOG_EMERG);
			return;
		}

		// @TODO: make sure these variables are vetted for security, people could attack each other this way too
		$emailLessonData = array(
			'contactName' => $contact['User']['name'],
			'subject' => $lesson['Lesson']['subject'],
			'notes' => $lesson['Lesson']['notes'],
			'confirmedBy' => array(
				'fullName' => $lessonConfirmer['User']['name'] . ' ' . $lessonConfirmer['User']['lname'],
				'id' => $lessonConfirmer['User']['id'],
				'name' => $lessonConfirmer['User']['name'],
				'username' => $lessonConfirmer['User']['username'],
			),
		);

		// send out an email saying that we've got a new lesson request that they should take a look at
		// and give them some details on it so they know what is being requested
		return $this->_sendEmail(
						array(Configure::read('Site.title'), $this->_getSenderEmail()), $contact['User']['email'], __d('croogo', '[%s] Lesson Confirmed', 'Botangle'), 'Users.lesson_confirmed', // @TODO: this and the next option down need work
						'lesson confirmed', $this->theme, compact('emailLessonData')
		);
	}

	public function mycalander() {
		
	}

	public function calandareventsprofile() {
		$this->request->onlyAllow('ajax');

		$userconditionsfield = "tutor";
		$userlessonconditionsfield = "student";
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

	public function calandarevents() {
		$userconditionsfield = "tutor";
		$userlessonconditionsfield = "student";
		$readconditons = "readlessontutor";
		if ($this->Session->read('Auth.User.role_id') == 4) {
			$userconditionsfield = "student";
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

/**
 * @api
 * @param null $categoryname
 * @param null $online
 */
	public function topchart($categoryname = null, $online = null) {
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
		$this->paginate['User']['order'] = 'avg(`Review`.`rating`) DESC';

		if ($this->RequestHandler->isXml()) {
			$this->paginate['User']['limit'] = 100;
			$this->paginate['User']['maxLimit'] = 1000;

			$this->helpers[] = 'Users.UserXmlTransformer';

			$this->set('userlist', $this->paginate());
		} else {
			$this->paginate['User']['limit'] = 21;

			$this->set('userlist', $this->paginate());

			$categories = $this->Category->find('all', array('conditions' => array('status' => 1, 'parent_id' => null)));

			$total = 0;
			
			// calculate category count
			foreach ($categories as $k => $v) {
				$categories[$k]['Category']['count'] = $this->User->getCategoryUserCount($categories[$k]);
				$total += $categories[$k]['Category']['count'];
			}
			
			$this->set(compact('userlist', 'categories', 'categoryname', 'online', 'total'));
		}
	}

	public function joinuser($id) {
		$requestedby = base64_decode($id);
		$this->Session->write('requestedbyuser', $requestedby);
		$this->Session->write('requestedbyurl', $_SERVER['REQUEST_URI']);
		$this->redirect('/register');
		exit();
	}

/**
 * @param $userRate
 * @param $totalTime
 * @return double
 */
	protected function calculateTutorTotalAmount($userRate, $totalTime) {
		$pritype = $userRate['UserRate']['price_type'];
		$pricerate = $userRate['UserRate']['rate'];
		$totalamount = 0;

		if ($pritype == 'permin') {
			$totaltimeuseinmin = $totalTime / 60;
			$totalamount = ($totaltimeuseinmin) * $pricerate;
		} else {
			$pricerate = $pricerate / 60;
			$totaltimeuseinmin = $totalTime / 60;
			$totalamount = $totaltimeuseinmin * $pricerate;
		}

		// return a formatted decimal to two decimal places with no commas
		// this will work much better in our DB and with Stripe
		return sprintf('%0.2f', $totalamount);
	}

/**
 * Called if no lesson payment exists and we need one made
 *
 * @param $lessonId
 * @param $role
 * @param $userId
 * @return mixed
 * @throws InternalErrorException
 */
	protected function findLessonAndCreatePaymentForIt($lessonId, $role, $userId) {
		$conditions = array(
			'id' => (int) $lessonId,
		);
		if ($role == 2) {
			$conditions['tutor'] = (int) $this->Auth->user('id');
		} else {
			// we're dealing with a student because we've checked to make sure role is only 2 or 4 earlier
			$conditions['student'] = (int) $this->Auth->user('id');
		}

		// @TODO: this is going to need to be re-thought through
		$lesson = $this->Lesson->find(
				'first', array(
			'conditions' => $conditions,
				)
		);

		if (count($lesson) == 0) {
			throw new InternalErrorException("Hmm, things aren't working.");
		}

		// now we build our new lesson payment information
		$data['LessonPayment']['lesson_id'] = (int) $lesson['Lesson']['id'];
		$data['LessonPayment']['student_id'] = (int) $lesson['Lesson']['student'];
		$data['LessonPayment']['tutor_id'] = (int) $lesson['Lesson']['tutor'];
		$data['LessonPayment']['payment_complete'] = 0;
		$this->LessonPayment->save($data);

		$data['LessonPayment']['id'] = (int) $this->LessonPayment->id;

		// and send it on back to be used further
		return $data;
	}

/**
 * Update remaining
 *
 * A very general function, should probably be separated in the future.
 *
 * This function checks if the lesson has ended (verifies with twiddla).
 * If it has, and if no payment has yet been made, it calculates the payment
 * and saves the calculation.
 *
 * How the payment should be calculated:
 *    30% goes to Botangle
 *    70% goes to tutor
 *    Prices are set and calculated on a per-minute price.
 *
 * @package billing
 */
	public function updateremaining() {
		$totalTime = 0;
		$lessonComplete = false;
		$userRate = null;

		/**
		 * parameters sent in
		 * - lessonid
		 * - roletype (2 = tutor, 4 = student)
		 * - completelesson (only sent if we're trying to bill this lesson, will be one in that case)
		 */
		$pleaseCompleteLesson = false;
		if (isset($this->params->data['completelesson']) && $this->params->data['completelesson'] == 1) {
			$pleaseCompleteLesson = true;
		}

		$role = (int) $this->params->data['roletype'];

		// we retrieving this lesson payment either with a tutor id or a student id in addition to the lesson id
		// otherwise, anyone can try and contact this page ..
		$conditions = array(
			'lesson_id' => (int) $this->params->data['lessonid'],
		);
		if ($role == 2) {
			$conditions['tutor_id'] = (int) $this->Auth->user('id');
		} elseif ($role == 4) {
			// we're dealing with a student
			$conditions['student_id'] = (int) $this->Auth->user('id');
		} else {
			throw new NotFoundException("Sorry, things aren't working.");
		}

		// then find the lesson payment for this person that matches those criteria (id and lesson id)
		$lessonPayment = $this->LessonPayment->find(
				'first', array(
			'conditions' => $conditions,
				)
		);

		// if a lesson payment record doesn't exist for the lesson we were given
		// with this information, then we'd like to generate it (assuming that lesson / user combo exists) from
		// the lesson table
		if (empty($lessonPayment)) {
			$lessonPayment = $this->findLessonAndCreatePaymentForIt(
					(int) $this->params->data['lessonid'], $role, (int) $this->Auth->user('id')
			);
		}

		$lessonId = $lessonPayment['LessonPayment']['lesson_id'];
		$studentId = $lessonPayment['LessonPayment']['student_id'];
		$tutorId = $lessonPayment['LessonPayment']['tutor_id'];

		if ($lessonPayment['LessonPayment']['lesson_complete_tutor'] == 1 && $lessonPayment['LessonPayment']['lesson_complete_student'] == 1) {
			$lessonComplete = true;
		}

		// if our lesson payment is not complete, then we've got a bunch of things we want to do
		if (!$lessonComplete) {

			// update our lesson timer depending on our role
			// we do this for both parties on a regular basis to keep folks honest
			$totalTime = $this->updateLessonTimer($role, $lessonId);

			// retrieve our user rate and hang on to it so we can re-use it in a bit
			// @TODO: long-term, we want to be pulling this user rate from the Lesson
			// where we keep it to prevent tutors from raising rates after the lesson gets scheduled
			$userRate = $this->UserRate->find(
					'first', array('conditions' => array('userid' => $tutorId))
			);

			// figure out the payment amount
			$lessonPayment['LessonPayment']['payment_amount'] = $this->calculateTutorTotalAmount($userRate, $totalTime);

			// if someone wants to end the lesson, then we want to record that
			if ($pleaseCompleteLesson) {
				$lessonPayment['LessonPayment']['lesson_complete_tutor'] = 1;
				$lessonPayment['LessonPayment']['lesson_complete_student'] = 1;
			}
			$this->LessonPayment->save($lessonPayment);

			// then, if our payment isn't complete yet, then let's bill for it
			// we don't leave this outside for fear of having race conditions between the student and the tutor
			// we'd really like the first person to ask for this to be completed to be the one who calculates totals
			// theoretically, if the second person makes it past the lesson payment query above before the first person
			// charges things here, we could end up with a double-billing ... :-/
			if ($pleaseCompleteLesson && $lessonPayment['LessonPayment']['payment_complete'] == 0) {
				$this->chargeForLesson(
						$lessonPayment['LessonPayment']['id'], $tutorId, $studentId, $lessonPayment['LessonPayment']['payment_amount']
				);
			}
		}

		/**
		 * response sent back is JSON
		 * - specifically, we're interested in lesson_complete_student on the frontend to notify the student if the lesson is over)
		 *      we'll then notify the student about what is going on
		 */
		$updatedLessonPayment = $this->LessonPayment->find(
				'first', array(
			'conditions' => array(
				'lesson_id' => $lessonId,
			)
				)
		);

		$this->autoRender = false;
		$this->layouts = false;

		if ($lessonComplete) {
			// @TODO: we want to send back different information requesting that this person get sent to the receipt page
			echo json_encode(array(
				'lessonComplete' => 1,
			));
		} else {
			// we want to show them the updated information
			echo json_encode(array(
				'newPrice' => $this->calculateTutorTotalAmount($userRate, $totalTime + 60),
				'lessonComplete' => $updatedLessonPayment['LessonPayment']['lesson_complete_student'],
				'totaltime' => $totalTime,
			));
		}
	}

/**
 * Updates our lesson timer depending on who we're working with
 *
 * @TODO: would love to move this to our Lesson model long-term, would make this far cleaner
 *
 * @param $role
 * @param $lessonId
 */
	private function updateLessonTimer($role, $lessonId) {
		$lesson = $this->Lesson->find(
				'first', array(
			'conditions' => array(
				'id' => (int) $lessonId,
			),
				)
		);

		if ($role == 2) {
			$totalTime = $lesson['Lesson']['remainingduration'] + 60;

			$this->Lesson->id = $lesson['Lesson']['id'];
			$this->Lesson->saveField('remainingduration', $totalTime);
		} else if ($role == 4) {
			$totalTime = $lesson['Lesson']['student_lessontaekn_time'] + 60;

			$this->Lesson->id = $lesson['Lesson']['id'];
			$this->Lesson->saveField('student_lessontaekn_time', $totalTime);
		}

		return $totalTime;
	}

/**
 * Handle billing for this particular lesson.
 *
 * @param $lessonPaymentId
 * @param $tutorId
 * @param $studentId
 * @param $amount
 */
	private function chargeForLesson($lessonPaymentId, $tutorId, $studentId, $amount) {
		try {
			$fee = $amount * .30; // take a 30% commission
			// format our fee to work well with Stripe and when we save it to the DB
			$fee = sprintf('%0.2f', $fee);

			$results = $this->charge((int) $tutorId, (int) $studentId, $amount, $fee);

			// if we have success billing, we'll note the fact and save things
			if (is_array($results)) {
				$data = array();

				// @TODO: do we need to set tutor_id, or payment amount as we pass this info in to the DB?

				$data['LessonPayment']['id'] = $lessonPaymentId;
				$data['LessonPayment']['payment_complete'] = 1;
				$data['LessonPayment']['fee'] = $fee;
				$data['LessonPayment']['stripe_charge_id'] = $results['stripe_id'];
				$this->LessonPayment->save($data);

				Croogo::dispatchEvent('Controller.Users.lessonCharged', $this);
			}
		} catch (Exception $e) {
			// otherwise
			$this->log(sprintf('Error charging for lesson payment %s : %s', $lessonPaymentId, $e->getMessage()), LOG_ALERT);
		}

		// otherwise we'll leave this for the system to bill again somehow
		// @TODO: should we have an auto-retry system setup here?
	}

	public function _updateremaining() {
		$this->Lesson->id = $this->params->query['lessonid'];
		$roletype = $this->params->query['roletype'];
		$checktwiddlaid = $this->Lesson->find('first', array('conditions' => array('id' => $this->params->query['lessonid'])));
		$totaltime = 0;

		if ($roletype == 2) {
			$totaltime = $checktwiddlaid['Lesson']['remainingduration'] + 60;
			$this->Lesson->saveField('remainingduration', $totaltime);
			if (isset($this->params->query['completelesson']) && ($this->params->query['completelesson'] == 1)) {
				$lessonPayment = $this->LessonPayment->find('first', array('conditions' => array('student_id' => $checktwiddlaid['Lesson']['student'], 'tutor_id' => $checktwiddlaid['Lesson']['tutor'], 'lesson_id' => $this->params->query['lessonid'])));
				if (empty($lessonPayment)) {
					$u = $this->UserRate->find('first', array('conditions' => array('userid' => $checktwiddlaid['Lesson']['tutor'])));
					$pritype = $u['UserRate']['price_type'];
					$pricerate = $u['UserRate']['rate'];
					$totalamount = 0;
					if ($pritype == 'permin') {
						$totaltimeuseinmin = $totaltime / 60;
						$totalamount = ($totaltimeuseinmin) * $pricerate;
					} else {
						$pricerate = $pricerate / 60;
						$totaltimeuseinmin = $totaltime / 60;
						$totalamount = $totaltimeuseinmin * $pricerate;
					}
					$this->request->data['LessonPayment']['student_id'] = $checktwiddlaid['Lesson']['student'];
					$this->request->data['LessonPayment']['tutor_id'] = $checktwiddlaid['Lesson']['tutor'];
					$this->request->data['LessonPayment']['payment_amount'] = $totalamount;

					$this->request->data['LessonPayment']['payment_complete'] = 0;
					$this->request->data['LessonPayment']['lesson_id'] = $this->params->query['lessonid'];
					$this->LessonPayment->save($this->request->data);
				} else {
					$u = $this->UserRate->find('first', array('conditions' => array('userid' => $checktwiddlaid['Lesson']['tutor'])));
					$pritype = $u['UserRate']['price_type'];
					$pricerate = $u['UserRate']['rate'];
					$totalamount = 0;
					if ($pritype == 'permin') {
						$totaltimeuseinmin = $totaltime / 60;
						$totalamount = ($totaltimeuseinmin) * $pricerate;
					} else {
						$pricerate = $pricerate / 60;
						$totaltimeuseinmin = $totaltime / 60;
						$totalamount = $totaltimeuseinmin * $pricerate;
					}
					$this->request->data['LessonPayment']['student_id'] = $checktwiddlaid['Lesson']['student'];
					$this->request->data['LessonPayment']['tutor_id'] = $checktwiddlaid['Lesson']['tutor'];
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
			$lessonPayment = $this->LessonPayment->find('first', array('conditions' => array('student_id' => $checktwiddlaid['Lesson']['student'], 'tutor_id' => $checktwiddlaid['Lesson']['tutor'], 'lesson_id' => $this->params->query['lessonid'])));
			if (isset($this->params->query['completelesson']) && ($this->params->query['completelesson'] == 1)) {

				$this->request->data['LessonPayment']['lesson_complete_tutor'] = 1;
				$this->request->data['LessonPayment']['lesson_complete_student'] = 1;
			}

			if (empty($lessonPayment)) {
				$u = $this->UserRate->find('first', array('conditions' => array('userid' => $checktwiddlaid['Lesson']['tutor'])));
				$pritype = $u['UserRate']['price_type'];
				$pricerate = $u['UserRate']['rate'];
				$totalamount = 0;
				if ($pritype == 'permin') {
					$totaltimeuseinmin = $totaltime / 60;
					$totalamount = ($totaltimeuseinmin) * $pricerate;
				} else {
					$pricerate = $pricerate / 60;
					$totaltimeuseinmin = $totaltime / 60;
					$totalamount = $totaltimeuseinmin * $pricerate;
				}
				$this->request->data['LessonPayment']['student_id'] = $checktwiddlaid['Lesson']['student'];
				$this->request->data['LessonPayment']['tutor_id'] = $checktwiddlaid['Lesson']['tutor'];
				$this->request->data['LessonPayment']['payment_amount'] = $totalamount;
				$this->request->data['LessonPayment']['lesson_take'] = 1;
				$this->request->data['LessonPayment']['payment_complete'] = 0;
				$this->request->data['LessonPayment']['lesson_id'] = $this->params->query['lessonid'];
			} else {
				$u = $this->UserRate->find('first', array('conditions' => array('userid' => $checktwiddlaid['Lesson']['tutor'])));
				$pritype = $u['UserRate']['price_type'];
				$pricerate = $u['UserRate']['rate'];
				$totalamount = 0;
				if ($pritype == 'permin') {
					$totaltimeuseinmin = $totaltime / 60;
					$totalamount = ($totaltimeuseinmin) * $pricerate;
				} else {
					$pricerate = $pricerate / 60;
					$totaltimeuseinmin = $totaltime / 60;
					$totalamount = $totaltimeuseinmin * $pricerate;
				}
				$this->request->data['LessonPayment']['student_id'] = $checktwiddlaid['Lesson']['student'];
				$this->request->data['LessonPayment']['tutor_id'] = $checktwiddlaid['Lesson']['tutor'];
				$this->request->data['LessonPayment']['payment_amount'] = $totalamount;
				$this->request->data['LessonPayment']['id'] = $lessonPayment['LessonPayment']['id'];
			}

			// we'll only do this stuff if our role is a student
			// @TODO: let's make sure a student doesn't try to change their role id ...
			// save our current information to the database
			$this->LessonPayment->save($this->request->data);

			// then, if our payment isn't complete yet, then let's bill for it
			if ($this->request->data['LessonPayment']['payment_complete'] == 0) {
				$this->_chargeForLesson();
			}
		}

		$u = $this->LessonPayment->find('first', array('conditions' => array('lesson_id' => $this->params->query['lessonid'])));

		echo json_encode(array('totaltime' => $totaltime, 'lessonResponse' => $u));
		$this->autoRender = false;
		$this->layouts = false;
	}

	private function _chargeForLesson() {
		$tutorId = (int) $this->request->data['LessonPayment']['tutor_id'];
		$userId = (int) $this->Auth->user('id');

		$amount = $this->request->data['LessonPayment']['payment_amount']; // @TODO: convert this DB column to a decimal instead?
		$fee = $amount * .30; // take a 30% commission

		$results = $this->charge($tutorId, $userId, $this->request->data['LessonPayment']['payment_amount'], $fee);

		// if we have success billing, we'll note the fact and save things
		if (is_array($results)) {
			$this->request->data['LessonPayment']['payment_complete'] = 1;
			$this->request->data['LessonPayment']['fee'] = $fee; // @TODO: convert this DB column to a decimal instead?
			$this->request->data['LessonPayment']['stripe_charge_id'] = $results['stripe_id'];
			$this->LessonPayment->save($this->request->data);

			Croogo::dispatchEvent('Controller.Users.lessonCharged', $this);
		}

		// otherwise we'll leave this for the system to bill again somehow
		// @TODO: should we have an auto-retry system setup here?
	}

/**
 * Payment made.  This action displays the details on the payment they just made.
 *
 * @package billing
 */
	public function paymentmade() {

		$role = $this->params->query['role'];

		if ($role != 'tutor' && $role != 'student') {
			throw new NotFoundException("Sorry, things aren't working");
		}

		if ($role == 'student') {
			$lessonPayment = $this->LessonPayment->find('first', array('conditions' => array(
					'lesson_id' => (int) $this->params->query['lessonid'],
					'student_id' => $this->Auth->user('id'),
			)));
		} else {
			$lessonPayment = $this->LessonPayment->find('first', array('conditions' => array(
					'lesson_id' => (int) $this->params->query['lessonid'],
					'tutor_id' => $this->Auth->user('id'),
			)));
		}

		$this->set(compact('lessonPayment', 'role'));
	}

/**
 * Claim offer
 *
 * Handles offer claims. Free trials and such.
 *
 * @package billing
 */
	public function claimoffer() {

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
							"description" => "claim $5 from botangle"
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

/**
 * Payment setting
 *
 * Sets payment info, connects with Stripe, and asserts the transaction. Also
 * renders the view found in http://app.botangle.dev/users/paymentsetting
 *
 * @package billing
 */
	public function paymentsetting() {
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

	function paymentnotmade() {
		
	}

	function mystatus() {
		$this->autoRender = false;
		$this->layouts = false;
		if (!empty($this->request->data)) {
			$this->request->data['Mystatus']['status_text'] = $this->request->data['Users']['status_text'];
			$this->request->data['Mystatus']['created'] = date('Y-m-d h:i:s');
			$this->request->data['Mystatus']['status'] = '1';
			$this->request->data['Mystatus']['created_by_id'] = $this->Session->read('Auth.User.id');
			if ($this->Mystatus->save($this->request->data)) {
				$this->Session->setFlash(
						__d('croogo', 'Your Status Update Sucessfully.'), 'default', array('class' => 'success')
				);
			} else {
				$this->Session->setFlash(
						__d('croogo', 'Some Error Occured. Please try again.'), 'default', array('class' => 'error')
				);
			}
			$this->redirect('/user/' . $this->request->data['Users']['username']);
		}
	}

}
