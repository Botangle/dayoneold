<?php
/**
 * routes.php
 *
 * @author: David Baker <dbaker@acorncomputersolutions.com
 * Date: 3/5/15
 * Time: 6:09 PM
 */

Route::get('/backdoor/login', array(
	'as'        => 'backdoor_login',
	'uses'      => 'DayOne\Admin\AdminController@getLogin',
));
Route::post('/backdoor/login', array(
	'uses'      => 'DayOne\Admin\AdminController@postLogin',
));
Route::group(array('before' => 'auth'), function(){
	Route::get('/backdoor/logout', array('as' => 'backdoor_logout', function(){
		Auth::logout();
		return Redirect::home()->with('flash_notice', 'You are successfully logged out.');
	}));
});
