<?php 
App::uses('MediaAppController', 'Media.Controller');
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
class MediaController extends MediaAppController {

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
	public $uses = array('Media.Media');
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
			$this->set('title_for_layout', __d('croogo', 'Media'));
			$this->Media->recursive = 0;		 
			/*$this->paginate['New']['conditions'] = ' `New`.`parent_id` IS NULL';*/
			$this->paginate['Media']['order'] = 'Media.date Desc'; 
			$this->set('media', $this->paginate());   
			
			$this->set('displayFields', $this->Media->displayFields());
		  
	}

/**
 * Admin add
 *
 * @return void
 * @access public
 */
	public function admin_add() {
		if (!empty($this->request->data)) {
			$this->Media->create();
			 
			 		 
 $filename = "";
if (!empty($this->request->data['Media']['image']['tmp_name']) && is_uploaded_file($this->request->data['Media']['image']['tmp_name'])) {
				 
	$filename = str_replace(" ","_",basename($this->request->data['Media']['image']['name']));
	 $dir = 	WWW_ROOT .'uploads' . DS . 'media' ; 
	 
				if(!is_dir($dir)){ 
					mkdir($dir,0777); 
				} 
				move_uploaded_file(
					$this->data['Media']['image']['tmp_name'],
					$dir. DS . $filename
				); 
			}
			 
			 if(isset( $this->request->data['Media']['id']) &&  $this->request->data['Media']['id']!=""){
			  $dir = 	WWW_ROOT .'uploads' . DS . 'media'.DS ; 
				if(file_exists($dir.$this->request->data['image_old']) && $this->request->data['image_old']!=""){
					unlink($dir.$this->request->data['image_old']);
				}
				if($filename==""){
					$filename = $this->request->data['image_old'];
				}
				 
			 }
			 $this->request->data['Media']['image'] = $filename;
			if ($this->Media->save($this->request->data)) {
				$this->Session->setFlash(__d('croogo', 'The Media has been saved'), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__d('croogo', 'The Media could not be saved. Please, try again.'), 'default', array('class' => 'error'));
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
		 
if (!empty($this->request->data['Media']['image']['tmp_name']) && is_uploaded_file($this->request->data['Media']['image']['tmp_name'])) {
				 
	$filename = str_replace(" ","_",basename($this->request->data['Media']['image']['name']));
	 $dir = 	WWW_ROOT .'uploads' . DS . 'media' ; 
	 
				if(!is_dir($dir)){ 
					mkdir($dir,0777); 
				}
				   $dir.$this->data['Media']['old_image'];  
				move_uploaded_file(
					$this->data['Media']['image']['tmp_name'],
					$dir. DS . $filename
				);
				if(file_exists($dir.$this->data['Media']['old_image'])){
					unlink($dir.$this->data['Media']['old_image']);
				}
				 
			}else{
				$filename = $this->data['Media']['old_image'];
			}
		  
			 $this->request->data['Media']['image'] = $filename;
			if ($this->Media->save($this->request->data)) {
				$this->Session->setFlash(__d('croogo', 'The Media has been saved'), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__d('croogo', 'The Media could not be saved. Please, try again.'), 'default', array('class' => 'error'));
			}
		} else {
			$this->request->data = $this->Media->read(null, $id);
		}
		 
		$this->set('editFields', $this->Media->editFields());
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
			$this->Session->setFlash(__d('croogo', 'Invalid id for Media'), 'default', array('class' => 'error'));
			$this->redirect(array('action' => 'index'));
		}
		if ($this->Media->delete($id)) {
			$this->Session->setFlash(__d('croogo', 'Media deleted'), 'default', array('class' => 'success'));
			$this->redirect(array('action' => 'index'));
		}
	}
	public function index(){ 
			$this->Media->recursive = 0;	
			 $name = "";
			 $cond= array('status'=>"1");
			if(!empty($this->request->data)){
			 
				 $name = $this->request->data['id'];
				$cond= array('status'=>"1",'id'=>$name);
			} 
			$Media = $this->Media->find('all',array('conditions'=> $cond));
			$this->set(compact('Media'));
	}
	public function detail(){ 
			$this->Media->recursive = 0;	
			 $name = "";
			 $cond= array('status'=>"1");
			if(!empty($this->request->data)){
			 
				 $name = $this->request->data['id'];
				$cond= array('status'=>"1",'id'=>$name);
			} 
			$Media = $this->Media->find('first',array('conditions'=> $cond));
			$this->set(compact('Media'));
	}
   

}
