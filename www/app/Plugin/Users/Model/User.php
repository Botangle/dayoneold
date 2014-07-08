<?php

App::uses('UsersAppModel', 'Users.Model');
App::uses('AuthComponent', 'Controller/Component');
App::uses('AttachmentBehavior', 'Uploader.Model/Behavior');

/**
 * User
 *
 * @category Model
 * @package  Croogo.Users.Model
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class User extends UsersAppModel {

/**
 * Model name
 *
 * @var string
 * @access public
 */
	public $name = 'User';

/**
 * Order
 *
 * @var string
 * @access public
 */
	public $order = 'User.name ASC';

/**
 * Behaviors used by the Model
 *
 * @var array
 * @access public
 */
	public $actsAs = array(
		'Acl' => array(
			'className' => 'Croogo.CroogoAcl',
			'type' => 'requester',
		),
		'Search.Searchable',
		'Uploader.Attachment' => array(
			'profilepic' => array(
				'tempDir' => TMP,
				'nameCallback' => 'formatFileName',
//				'uploadDir' => 'uploads/profilepic',
//				'finalPath' => '/uploads/profilepic/',
				'overwrite' => true,
				'stopSave' => false,
				'allowEmpty' => true,
				'transforms' => array(
					'resize' => array(
						'class' => 'resize',
						'width' => 250,
						'height' => 250,
						'self' => true,
						'aspect' => true,
					),
					'crop' => array(
						'class' => 'crop',
						'width' => 250,
						'height' => 250,
						'self' => true,
						'aspect' => true,
					)
				),
				'transport' => array(
					'class' => AttachmentBehavior::S3,
					'accessKey' => 'AKIAJWF54OAT34LFKR3Q',
					'secretKey' => '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',
					'bucket' => 'botangleassets',
					'region' => Aws\Common\Enum\Region::US_EAST_1,
					'folder' => 'profilepic/',
				),
			)
		),
		'Uploader.FileValidation' => array(
			'profilepic' => array(
				'type' => 'image',
				'extension' => array(
					'value' => array('gif', 'jpg', 'png', 'jpeg'),
					'error' => 'Incorrect file type. Only image is allowed.',
				),
				'filesize' => array(
					'value' => 5242880,
					'error' => 'Filesize is to high, please reduce it.',
				),
				'required' => false,
			)
		)
	);

/**
 * Model associations: belongsTo
 *
 * @var array
 * @access public
 */
	public $belongsTo = array('Users.Role');

/**
 * Validation
 *
 * @var array
 * @access public
 */
	public $validate = array(
		'username' => array(
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'The username has already been taken.',
				'last' => true,
			),
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank.',
				'last' => true,
			),
			'validAlias' => array(
				'rule' => 'validAlias',
				'message' => 'This field must be alphanumeric',
				'last' => true,
			),
		),
		'email' => array(
			'email' => array(
				'rule' => 'email',
				'message' => 'Please provide a valid email address.',
				'last' => true,
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'Email address already in use.',
				'last' => true,
			),
		),
		'password' => array(
			'rule' => array('minLength', 6),
			'message' => 'Passwords must be at least 6 characters long.',
		),
		'verify_password' => array(
			'rule' => 'validIdentical',
		),
		'name' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank.',
				'last' => true,
			),
			'validName' => array(
				'rule' => 'validName',
				'message' => 'This field must be alphanumeric',
				'last' => true,
			),
		),
		'website' => array(
			'url' => array(
				'rule' => 'url',
				'message' => 'This field must be a valid URL',
				'allowEmpty' => true,
			),
		),
	);

/**
 * Filter search fields
 *
 * @var array
 * @access public
 */
	public $filterArgs = array(
		'chooser' => array('type' => null),
		'name' => array('type' => 'like', 'field' => array('User.name', 'User.username')),
		'role_id' => array('type' => 'value'),
	);

/**
 * Display fields for this model
 *
 * @var array
 */
	protected $_displayFields = array(
		'id',
		'Role.title' => 'Role',
		'username',
		'name',
		'status' => array('type' => 'boolean'),
		'is_featured'=> array('type' => 'boolean'),
		'email',
	);

/**
 * Edit fields for this model
 *
 * @var array
 */
	protected $_editFields = array(
		'role_id',
		'username',
		'name',
		'email',
		'is_featured',
		'status',
	);
	
/**
 * Format the filename a specific way before uploading and attaching.
 * 
 * @access public
 * @param string $name	- The current filename without extension
 * @param array $file	- The $_FILES data
 * @return string
 */
	function formatFileName($name, $file) {
//		$file = pathinfo($name);
//		$name = String::truncate($file['filename'], 20);
//		return uniqid();
		return uniqid();
	}

/**
 * beforeDelete
 *
 * @param boolean $cascade
 * @return boolean
 */
	public function beforeDelete($cascade = true) {
		$this->Role->Behaviors->attach('Croogo.Aliasable');
		$adminRoleId = $this->Role->byAlias('admin');

		$current = AuthComponent::user();
		if (!empty($current['id']) && $current['id'] == $this->id) {
			return false;
		}
		if ($this->field('role_id') == $adminRoleId) {
			$count = $this->find('count', array(
				'conditions' => array(
					'User.id <>' => $this->id,
					'User.role_id' => $adminRoleId,
					'User.status' => true,
				)
			));
			return ($count > 0);
		}
		return true;
	}

/**
 * beforeSave
 *
 * @param array $options
 * @return boolean
 */
	public function beforeSave($options = array()) {
		if (!empty($this->data['User']['password'])) {
			$this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
		}
		return true;
	}

/**
 * _identical
 *
 * @param string $check
 * @return boolean
 * @deprecated Protected validation methods are no longer supported
 */
	protected function _identical($check) {
		return $this->validIdentical($check);
	}

/**
 * validIdentical
 *
 * @param string $check
 * @return boolean
 */
	public function validIdentical($check) {
		if (isset($this->data['User']['password'])) {
			if ($this->data['User']['password'] != $check['verify_password']) {
				return __d('croogo', 'Passwords do not match. Please, try again.');
			}
		}
		return true;
	}

	function getCategoryUserCount($cateogyrid) {
		App::import("Model", "Category");
		$Category = new Category();
		$results = $Category->find('first', array('conditions' => array('parent_id' => $cateogyrid)));

		App::import("Model", "Users.User");
		$User = new User();

		if (!empty($results)) {
			return $User->find('count', array('conditions' => array('subject LIKE' => '%' . $results['Category']['name'] . '%')));
		} else {
			return 0;
		}
	}

}
