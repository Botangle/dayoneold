<?php

App::uses('UsersAppModel', 'Users.Model');

/**
 * RolesUser
 *
 *
 * @category Model
 * @package  Croogo.Users.Model
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class Usermessage extends UsersAppModel
{
    public $name = 'Usermessage';


    public $virtualFields = array(
        'date' => 'MAX(date)',
        'id' => 'MAX( `Usermessage`.`id` )',
        'userids' => '((CASE WHEN Usermessage.send_to = "$this->Auth->user(id)" THEN sent_from ELSE send_to END))',
    );

}
