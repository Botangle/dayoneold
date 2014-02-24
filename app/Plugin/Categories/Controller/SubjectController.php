<?php 
App::uses('CategoriesAppController', 'Categories.Controller');
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
class SubjectController extends CategoriesAppController {

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
	public $uses = array('Categories.Category');
	function beforeFilter(){
	 
		parent::beforeFilter();
		 $this->Security->validatePost = false;
		 $this->Security->csrfCheck = false;
		 
		 $this->Security->unlockedActions = array('index','search');
		 $this->Auth->allow('index','search'); 
	}

/**
 * Admin index
 *
 * @return void
 * @access public
 * $searchField : Identify fields for search
 */
	public function admin_index() {   
			$this->set('title_for_layout', __d('croogo', 'Categories'));
			$this->Category->recursive = 0;		 
			$this->paginate['Category']['order'] = 'Category.name ASC';
			$this->paginate['Category']['conditions'] = ' `Category`.`parent_id` IS NOT NULL'; 
			$this->set('categories', $this->paginate());   
			$fields = $this->Category->displayFields();
			$fields['parent_id'] = array('label'=>'parent_id','sort'=>1);
			  
			$this->set('displayFields', $fields);
		  		 
	}

/**
 * Admin add
 *
 * @return void
 * @access public
 */
	public function admin_add() {
		if (!empty($this->request->data)) {
			$this->Category->create();
		 
			if ($this->Category->save($this->request->data)) {
				$this->Session->setFlash(__d('croogo', 'The Category has been saved'), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__d('croogo', 'The Category could not be saved. Please, try again.'), 'default', array('class' => 'error'));
			}
		}
		
		 $this->set('categorylist', $this->Category->find('list',array('conditions'=>array('status'=>'1','parent_id'=> NULL))));
		 /*$log = $this->Category->getDataSource()->getLog(false, false);
debug($log); */
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
			if ($this->Category->save($this->request->data)) {
				$this->Session->setFlash(__d('croogo', 'The Category has been saved'), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__d('croogo', 'The Category could not be saved. Please, try again.'), 'default', array('class' => 'error'));
			}
		} else {
			$this->request->data = $this->Category->read(null, $id);
		}
		 
		$this->set('editFields', $this->Category->editFields());
		$this->set('categorylist', $this->Category->find('list',array('conditions'=>array('status'=>'1','parent_id'=> NULL))));
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
			$this->Session->setFlash(__d('croogo', 'Invalid id for Category'), 'default', array('class' => 'error'));
			$this->redirect(array('action' => 'index'));
		}
		if ($this->Category->delete($id)) {
			$this->Session->setFlash(__d('croogo', 'Category deleted'), 'default', array('class' => 'success'));
			$this->redirect(array('action' => 'index'));
		}
	}
	public function index(){
			$this->Category->recursive = 0;	
			 $name = "";
			 $cond= array('status'=>"1");
			if(!empty($this->request->data)){
			 
				$name = $this->request->data['search'];
				$cond= array('status'=>"1",'name LIKE '=>"%$name%");
			}
			
			$c = $this->Category->find('all',array('conditions'=> $cond));
 
			$results = "";
			$arrayAlphabets = "";
			for($i=65;$i<=90;$i++){
				$arrayAlphabets[] = chr($i);
			} 
			foreach($c as $k=>$v){
				 $char = substr($v['Category']['name'],0,1);
				$results['Category'][strtoupper($char)][] = $v['Category']['name']; 
			}			
			  
			$this->set('categories',$results);
			
	}
	public function search(){
		$this->Category->recursive = 0;	
		$cond= array('status'=>"1"); 
		$c = $this->Category->find('list',array('conditions'=> $cond),array('fields'=>array('id','name')));
		$result = array();
		foreach ($c as $key=>$value) {  
			 
				array_push($result, array("id"=>$key, "label"=>$value, "value" => strip_tags($value)));
			 
		}
		echo json_encode($result);
		 $this->autoRender = false;
		   $this->layouts =  false;
	}
	public function search2(){
	$this->Category->recursive = 0;	
	$cond= array('status'=>"1"); 
	if(!empty($this->request->query)){
			 
				$name = $this->request->query['term'];
				$cond= array('status'=>"1",'name LIKE '=>"$name%",
'parent_id !='=>'NULL');
			}
		$c = $this->Category->find('list',array('conditions'=> $cond),array('fields'=>array('id','name')));
			$q = strtolower($this->request->query['term']);  
		$result = array();
		 
foreach ($c as $key=>$value) {  
	if (strpos(strtolower($value), $q) !== false) {
		array_push($result, array("id"=>$key, "label"=>$value, "value" => strip_tags($value)));
	}
	if (count($result) > 11)
		break;
	
}	  echo json_encode($result);
			/*$log = $this->Category->getDataSource()->getLog(false, false);
debug($log);*/
		  $this->autoRender = false;
		   $this->layouts =  false;
		   
		  
	}
   

}
