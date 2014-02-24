<?php 
App::uses('CakeEmail', 'Network/Email');
App::uses('UsersAppController', 'Users.Controller');
class InviteController extends UsersAppController {
/**
 * Controller name
 *
 * @var string
 * @access public
 */
	public $name = 'Invite';
/**
 * Models used by the Controller
 *
 * @var array
 * @access public
 */
  public $uses = array('Users.Invite','Users.User');
 function beforeFilter(){
	 
		parent::beforeFilter();
		$this->Security->validatePost = false;
		 $this->Security->csrfCheck = false;
		 
		 
	}
 public function index(){
 
	if (!empty($this->request->data)) { 
		$this->request->data['Invite']['invited_date'] = date('Y-m-d H:i:s');
		$this->request->data['Invite']['invited_link'] = Configure::read('SiteUrl')."users/joinuser/".base64_encode($this->request->data['Invite']['invited_by']);
		$msgdata = 	 $this->request->data['Invite']['message'];
			$msgdata.='<br /> <a href="'.$this->request->data['Invite']['invited_link'].'">click here</a> to join ';
		$this->request->data['Invite']['message'] = $msgdata;
		$this->request->data['Invite']['linkused_or_not'] = 0;
		
		
		if ($this->Invite->save($this->request->data)) {
		 
		$user = $this->User->find('first', array(
					'conditions' => array(
						'User.id' => $this->request->data['Invite']['invited_by'], 
					),
				));
			
			$from = $user['User']['email'];
			$subject = "Invite to join ".Configure::read('Site.title');
			
			$template = "invite";
			$invite = $msgdata;
			
			$to=$this->request->data['Invite']['email'];
			//$this->_sendEmail($from,$to,$subject,$template,'both');
			 
			$emailSent = $this->_sendEmail(
				array(Configure::read('Site.title'), $from),
				$to,
				$subject,
				'Users.invite',
				'invite user',
				$this->theme,
				compact('invite')
			);
			if ($emailSent) {
				$this->Session->setFlash(__d('croogo', 'Your email has been sent to your friend.'), 'default', array('class' => 'success'));
				 $this->redirect(array('controllers'=>'invite','action' => 'index')); 
			} else {
				$this->Session->setFlash(__d('croogo', 'An error occurred. Please try again.'), 'default', array('class' => 'error'));
			}
			 $this->redirect(array('controllers'=>'invite','action' => 'index')); 
			 exit();
			}  
		}
	 }
	protected function _sendEmail($from, $to, $subject, $template, $emailType, $theme = null, $viewVars = null) {
	 
		if (is_null($theme)) {
			$theme = $this->theme;
		}
		$success = false;
	 
		try {
			$email = new CakeEmail();
			$email->from($from[1], $from[0]);
			$email->to($to);
			$email->emailFormat('both');
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
	 
 
	
}
