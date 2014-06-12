<?php 
App::uses('TestimonialsAppController', 'Testimonials.Controller');
App::uses('Croogo', 'Lib');

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
class TestimonialsController extends TestimonialsAppController {

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
	public $uses = array('Testimonials.Testimonial');
	function beforeFilter(){
	 
		parent::beforeFilter();
//		 $this->Security->validatePost = false;
//		 $this->Security->csrfCheck = false;
//		 $this->Security->unlockedActions = array('index');
		 
		 $this->Auth->allow('index','detail'); 
	}

/**
 * Admin index
 *
 * @return void
 * @access public
 * $searchField : Identify fields for search
 */
	public function admin_index() {  
			$this->set('title_for_layout', __d('croogo', 'Testimonial'));
			$this->Testimonial->recursive = 0;		 
			$this->paginate['Testimonial']['order'] = 'Testimonial.date Desc';
			$this->set('testimonials', $this->paginate()); 
			
			$this->set('displayFields', $this->Testimonial->displayFields());
	}

/**
 * Admin add
 *
 * @return void
 * @access public
 */
	public function admin_add() {
		if (!empty($this->request->data)) {  
			$this->Testimonial->create(); 
			if ($this->Testimonial->save($this->request->data)) {
				$this->Session->setFlash(__d('croogo', 'The Testimonials has been saved'), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__d('croogo', 'The Testimonials could not be saved. Please, try again.'), 'default', array('class' => 'error'));
			}
		}
		 
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
			if ($this->Testimonial->save($this->request->data)) {
				$this->Session->setFlash(__d('croogo', 'The Testimonials has been saved'), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__d('croogo', 'The Testimonials could not be saved. Please, try again.'), 'default', array('class' => 'error'));
			}
		} else {
			$this->request->data = $this->Testimonial->read(null, $id);
		}
		 
		$this->set('editFields', $this->Testimonial->editFields());
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
			$this->Session->setFlash(__d('croogo', 'Invalid id for Testimonials'), 'default', array('class' => 'error'));
			$this->redirect(array('action' => 'index'));
		}
		if ($this->Testimonial->delete($id)) {
			$this->Session->setFlash(__d('croogo', 'Testimonials deleted'), 'default', array('class' => 'success'));
			$this->redirect(array('action' => 'index'));
		}
	}
	public function index(){ 
			$this->Testimonial->recursive = 0;	
			 $name = "";
			 $cond= array('status'=>"1");
			if(!empty($this->request->data)){
			 
				 $name = $this->request->data['id'];
				$cond= array('status'=>"1",'id'=>$name);
			} 
			$Testimonials = $this->Testimonial->find('all',array('conditions'=> $cond));
			$this->set(compact('Testimonials'));
	}
	public function detail(){ 
			$this->Testimonial->recursive = 0;	
			 $name = "";
			 $cond= array('status'=>"1");
			if(!empty($this->request->data)){
			 
				 $name = $this->request->data['id'];
				$cond= array('status'=>"1",'id'=>$name);
			} 
			$Testimonials = $this->Testimonial->find('first',array('conditions'=> $cond));
			$this->set(compact('Testimonials'));
	}
   

}
