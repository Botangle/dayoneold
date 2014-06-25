<?php
/**
 * AccountCreationForm.php
 *
 * @author: David Baker <dbaker@acorncomputersolutions.com
 * Date: 6/25/14
 * Time: 6:26 AM
 */

App::uses('AppFormModel', 'Model');

class StudentAccountCreationForm extends AppFormModel {

    protected $_schema = array(
        'email' => array('type' => 'string' , 'null' => false, 'default' => '', 'length' => '100'),
        'username' => array('type' => 'string' , 'null' => false, 'default' => '', 'length' => '60'),
        'firstname' => array('type' => 'string' , 'null' => false, 'default' => '', 'length' => '50'),
        'lastname' => array('type' => 'string' , 'null' => false, 'default' => '', 'length' => '50'),
        'password' => array('type' => 'string' , 'null' => false, 'default' => '', 'length' => '100'),
    );

    public $useTable = false;

    public $validate = array(
        'email' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'required' => true,
            ),
            'email' => array(
                'rule' => 'email',
                'message' => 'Please provide a valid email address.',
                'last' => true,
            ),
            'isUnique' => array(
                'rule' => 'isUnique', // @TODO: work out how this check will work given that we don't have a DB model now
                'message' => 'Email address already in use.',
                'required' => true,
                'last' => true,
            ),
        ),
        'username' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'This field cannot be left blank.',
//                'required' => true,
//                'last' => true,
            ),
            'isUnique' => array(
                'rule' => 'isUnique', // @TODO: work out how this check will work given that we don't have a DB model now
                'message' => 'The username has already been taken.',
//                'last' => true,
            ),
            'validAlias' => array(
                'rule' => 'validAlias',
                'message' => 'This field must be alphanumeric',
                'required' => true,
                'last' => true,
            ),
        ),
        'password' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'A password is required.',
                'required' => true,
//                'last' => true,
            ),
            'notEmpty' => array(
                'rule' => array('minLength', 6),
                'message' => 'Passwords must be at least 6 characters long.',
                'required' => true,
//                'last' => true,
            ),
        ),
        'verify_password' => array(
            'rule' => 'validIdentical',
        ),
        'firstname' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'This field cannot be left blank.',
                'required' => true,
                'last' => true,
            ),
            'validName' => array(
                'rule' => 'validName',
                'message' => 'This field must be alphanumeric',
                'last' => true,
            ),
        ),
        'lastname' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'This field cannot be left blank.',
                'required' => true,
                'last' => true,
            ),
            'validName' => array(
                'rule' => 'validName',
                'message' => 'This field must be alphanumeric',
                'last' => true,
            ),
        ),
    );

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
}