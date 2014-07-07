<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/

/**
 * Pages controller
 */
Route::get('/', 'PageController@getIndex');
Route::get('/about', 'PageController@getAbout');
Route::get('/contact', 'PageController@getContact');
Route::get('/reportbug', 'PageController@getReportbug');
Route::get('/how-it-works', 'PageController@getHowItWorks');

Route::get('/how-it-works', 'PageController@getHowItWorks');

Route::get('/registration/student', 'RegistrationController@getRegisterStudent');
Route::get('/registration/tutor', 'RegistrationController@getRegisterExpert');

/**
 * Categories controller
 */
Route::controller('categories', 'CategoryController');

/**
 * Users controller (used for public viewing of user info)
 */
Route::controller('users', 'UsersController');

/**
 * User controller (used for private handling of an individual user account and info)
 */
Route::controller('user', 'UserController');