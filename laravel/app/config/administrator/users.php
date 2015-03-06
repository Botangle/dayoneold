<?php
/**
 * users.php
 *
 * @author: David Baker <dbaker@acorncomputersolutions.com
 * Date: 2/27/15
 * Time: 11:41 AM
 */

return array(

	'title' => 'Users',

	'single' => 'user',

	'model' => 'User',

	'permission' => function()
	{
		return Auth::check();
	},

	/**
	 * The display columns
	 */
	'columns' => array(
		'id' => array(
			'title' => 'ID',
		),
		'name' => array(
			'title' => 'First Name',
		),
		'lname' => array(
			'title' => 'Last Name',
		),
		'username' => array(
			'title' => 'Username',
			'sort_field' => 'username'
		),
		'email' => array(
			'title' => 'Email',
		),
		'subject' => [
			'title' => 'Subject',
		],
		'role_admin' => [
			'title'         => 'Role',
			'relationship'  => 'role',
			'select'        => '(:table).title',
		],
//		'num_films' => array(
//			'title' => '# films',
//			'relation' => 'films',
//			'select' => 'COUNT((:table).id)',
//		),
//		'created_at',
	),

	/**
	 * The filter set
	 */
	'filters' => array(
		'id' => array(
			'title' => 'Account ID',
		),
//		'first_name',
		'email' => array(
			'title' => 'Email',
		),
//		'salary' => array(
//			'type' => 'number',
//			'symbol' => '$',
//			'decimals' => 2,
//		),
//		'created_at' => array(
//			'type' => 'datetime'
//		),
	),

	/**
	 * The editable fields
	 */
	'edit_fields' => array(
		'active' => [
			'title' => 'Active',
			'type'  => 'bool',
			'visible' => function($model) {
				return $model->exists; // only visible if the user already exists
			},
		],
		'email' => [
			'title' => 'Email',
		],
		'username' => [
			'title' => 'Username',
		],
		'name' => [
			'title' => 'First Name',
		],
		'lname' => [
			'title' => 'Last Name',
		],
		'password' => [
			'title' => 'Password',
			'type'  => 'password',
		],
		'password_confirmation' => [
			'title' => 'Password confirmation',
			'type'  => 'password',
		],
		'subject' => [
			'title' => 'Subject',
			'value'  => 'Node.js',
		],
		'role' => [
			'title'         => 'Role',
			'type'          => 'relationship',
			'name_field'    => 'title',
		],
		'timezone' => [
			'title'     => 'Default timezone',
			'value'     => 'UTC',
		],
		'timezone_update' => [
			'title'     => 'Update timezone',
			'value'     => 'auto',
		],
		'terms' => [
			'title' => 'Accepted our terms',
			'type'  => 'bool',
		],
//			'relationship'  => 'role',
//		],
//		'salary' => array(
//			'title' => 'Salary',
//			'type' => 'number',
//			'symbol' => '$',
//			'decimals' => 2
//		),
	),

);
