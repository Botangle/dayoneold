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
 * News controller (used for viewing info about our news items)
 */
Route::controller('news', 'NewsController');

/**
 * Users controller (used for public viewing of group user info)
 */
Route::controller('users', 'UsersController');

/**
 * User controller (used for private handling of an individual user account and info)
 * Also used for viewing an individual's profile
 */
Route::controller('user', 'UserController');