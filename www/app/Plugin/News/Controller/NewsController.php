<?php
App::uses('NewsAppController', 'News.Controller');
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
class NewsController extends NewsAppController {

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
	public $uses = array('News.News');

	function beforeFilter() {

		parent::beforeFilter();
		$this->Security->validatePost = false;
		$this->Security->csrfCheck = false;
		$this->Security->unlockedActions = array('index');
		
		$this->Auth->allow('index', 'detail');
	}

/**
 * Admin index
 *
 * @return void
 * @access public
 * $searchField : Identify fields for search
 */
	public function admin_index() {
		$this->set('title_for_layout', __d('croogo', 'News'));
		$this->News->recursive = 0;
		/* $this->paginate['New']['conditions'] = ' `New`.`parent_id` IS NULL'; */
		$this->paginate['News']['order'] = 'News.date Desc';
		$this->set('news', $this->paginate());

		$this->set('displayFields', $this->News->displayFields());
	}

/**
 * Admin add
 *
 * @return void
 * @access public
 */
	public function admin_add() {
		if (!empty($this->request->data)) {
			$this->News->create();


			$filename = "";
			if (!empty($this->request->data['News']['image']['tmp_name']) && is_uploaded_file($this->request->data['News']['image']['tmp_name'])) {

				$filename = str_replace(" ", "_", basename($this->request->data['News']['image']['name']));
				$dir = WWW_ROOT . 'uploads' . DS . 'news';

				if (!is_dir($dir)) {
					mkdir($dir, 0777);
				}
				move_uploaded_file(
						$this->data['News']['image']['tmp_name'], $dir . DS . $filename
				);
			}

			if (isset($this->request->data['News']['id']) && $this->request->data['News']['id'] != "") {
				$dir = WWW_ROOT . 'uploads' . DS . 'news' . DS;
				if (file_exists($dir . $this->request->data['image_old']) && $this->request->data['image_old'] != "") {
					unlink($dir . $this->request->data['image_old']);
				}
				if ($filename == "") {
					$filename = $this->request->data['image_old'];
				}
			}
			$this->request->data['News']['image'] = $filename;
			if ($this->News->save($this->request->data)) {
				$this->Session->setFlash(__d('croogo', 'The News has been saved'), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__d('croogo', 'The News could not be saved. Please, try again.'), 'default', array('class' => 'error'));
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

			if (!empty($this->request->data['News']['image']['tmp_name']) && is_uploaded_file($this->request->data['News']['image']['tmp_name'])) {

				$filename = str_replace(" ", "_", basename($this->request->data['News']['image']['name']));
				$dir = WWW_ROOT . 'uploads' . DS . 'news';

				if (!is_dir($dir)) {
					mkdir($dir, 0777);
				}
				echo $dir . $this->data['News']['old_image'];
				die;
				move_uploaded_file(
						$this->data['News']['image']['tmp_name'], $dir . DS . $filename
				);
				if (file_exists($dir . $this->data['News']['old_image'])) {
					unlink($dir . $this->data['News']['old_image']);
				}
			} else {
				$filename = $this->data['News']['old_image'];
			}
			pr($this->request->data);
			die;

			$this->request->data['News']['image'] = $filename;
			if ($this->News->save($this->request->data)) {
				$this->Session->setFlash(__d('croogo', 'The News has been saved'), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__d('croogo', 'The News could not be saved. Please, try again.'), 'default', array('class' => 'error'));
			}
		} else {
			$this->request->data = $this->News->read(null, $id);
		}

		$this->set('editFields', $this->News->editFields());
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
			$this->Session->setFlash(__d('croogo', 'Invalid id for News'), 'default', array('class' => 'error'));
			$this->redirect(array('action' => 'index'));
		}
		if ($this->News->delete($id)) {
			$this->Session->setFlash(__d('croogo', 'News deleted'), 'default', array('class' => 'success'));
			$this->redirect(array('action' => 'index'));
		}
	}

	public function index() {
		$this->News->recursive = 0;
		$name = "";
		$cond = array('status' => "1");
		if (!empty($this->request->data)) {

			$name = $this->request->data['id'];
			$cond = array('status' => "1", 'id' => $name);
		}
		$news = $this->News->find('all', array('conditions' => $cond));
		$this->set(compact('news'));
	}

	public function detail() {
		$this->News->recursive = 0;

		$news = $this->News->find('first', array(
			'conditions' => array(
				'News.status' => '1',
				'News.id' => $this->request->params['id'],
			),
		));
		$this->set(compact('news'));
	}

}
