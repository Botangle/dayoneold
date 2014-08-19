<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/

/**
 * Pages controller
 */
Route::get('/', array(
        'as' => 'home',
        'uses' => 'PageController@getIndex',
    ));
Route::get('/about', array(
        'as'        => 'about',
        'uses'      => 'PageController@getAbout',
    ));
Route::get('/contact', array(
        'as'        => 'contact',
        'uses'      => 'PageController@getContact',
    ));
Route::post('/contact','PageController@getContactForm');
Route::get('/reportbug', array(
        'as'        => 'reportbug',
        'uses'      => 'PageController@getReportbug',
    ));
Route::post('/reportbug','PageController@getReportBugForm');
Route::get('/how-it-works', array(
        'as'        => 'how-it-works',
        'uses'      => 'PageController@getHowItWorks',
    ));

Route::get('/registration/student', array(
        'as'    => 'register.student',
        'uses'  => 'RegistrationController@getRegisterStudent',
    ));
Route::get('/registration/tutor', array(
        'as'    => 'register.expert',
        'uses'  => 'RegistrationController@getRegisterExpert',
    ));

/**
 * Categories controller
 */
Route::controller('categories', 'CategoryController', array(
        'getIndex' => 'categories.index',
    ));

/**
 * Login/logout
 */
Route::get('/login', array(
        'as'        => 'login',
        'uses'      => 'UserController@getLogin',
    ));
Route::post('/login', array(
        'uses'      => 'UserController@postLogin',
    ));

Route::group(array('before' => 'auth'), function(){
        Route::get('logout', array('http', 'as' => 'logout', function(){
                Auth::logout();
                return Redirect::home()
                    ->with('flash_notice', 'You are successfully logged out.');
            }));
    });

/**
 * News controller (used for viewing info about our news items)
 */
//Route::controller('news', 'NewsController');

Route::get('/news/detail/{id}', array(
        'as'    => 'news.detail',
        'uses'  => 'NewsController@getDetail',
    ));

/**
 * Users controller (used for public viewing of group user info)
 */
Route::controller('users', 'UsersController', array(
        'getTopChart'   => 'users.topcharts',
    ));

/**
 * User controller (used for private handling of an individual user account and info)
 * Also used for viewing an individual's profile
 */
Route::get('/user/forgot', array(
        'as'        => 'user.forgot',
        'uses'      => 'UserController@getForgot',
    ));

Route::get('/user/my-account', array(
        'as'        => 'user.my-account',
        'uses'      => 'UserController@getMyAccount',
    ));

Route::get('/user/search', array(
        'as'        => 'user.search',
        'uses'      => 'UserController@search',
    ));

Route::get('/user/lessons', array(
        'as'        => 'user.lessons',
        'uses'      => 'UserController@getLessons',
    ));

Route::get('/user/calendarEvents/{id}', array(
        'as'        => 'user.calendar-events',
        'uses'      => 'UserController@getCalendarEvents',
    ));

Route::get('/user/messages/{username?}', array(
        'as'        => 'user.messages',
        'uses'      => 'UserMessageController@index',
    ));

Route::get('/user/{username}', array(
        'as'        => 'user.profile',
        'uses'      => 'UserController@getView',
    ));

Route::controller('user', 'UserController', array(
        'getBilling'      => 'user.billing',
        'postChangePassword'    => 'user.change-password',
        'postStatus'      => 'user.status',
    ));

Route::get('/subject/search', array(
        'as'        => 'subject.search',
        'uses'      => 'SubjectController@search',
    ));


/**
 * Lessons controller
 */
Route::model('lesson', 'Lesson');
Route::model('user', 'User');

Route::get('/lesson/create/{expertId}', array(
        'as'        => 'lesson.create-with-expert',
        'uses'      => 'LessonController@createWithExpert',
    ));

Route::get('/lesson/{lesson}/edit', array(
       'uses'      => 'LessonController@getEdit',
    ));

Route::get('/lesson/{lesson}/review', array(
        'uses'      => 'LessonController@getReview',
    ));

Route::get('/lesson/{lesson}/confirm', array(
        'uses'      => 'LessonController@getConfirm',
    ));

Route::controller('lesson', 'LessonController', array(
        'postCreate'    => 'lesson.create',
        'postEdit'      => 'lesson.edit',
        'postReview'    => 'lesson.review',
    ));

/**
 * UserMessage controller
 */

Route::post('/user-message/create', array(
        'as'    => 'user-message.create',
        'uses'  => 'UserMessageController@postCreate',
    ));

