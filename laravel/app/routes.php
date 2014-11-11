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
Route::get('/terms', array(
        'as'    => 'terms',
        'uses'  => 'PageController@getTerms',
    ));

/**
 * Registration Controller routes
 */
Route::get('/registration/student', array(
        'as'    => 'register.student',
        'uses'  => 'RegistrationController@getRegisterStudent',
    ));
Route::get('/registration/tutor', array(
        'as'    => 'register.expert',
        'uses'  => 'RegistrationController@getRegisterExpert',
    ));
Route::post('/registration/student', array(
        'as'    => 'register.student',
        'uses'  => 'RegistrationController@postRegisterStudent',
    ));
Route::post('/registration/tutor', array(
        'as'    => 'register.expert',
        'uses'  => 'RegistrationController@postRegisterExpert',
    ));

/**
 * Categories controller
 */
Route::get('/categories/index', array(
    'uses' => 'CategoryController@getIndex',
    'as'    => 'categories.index',
    ));
Route::post('/categories/index', 'CategoryController@getIndex');

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

Route::get('/news', array(
        'as'    => 'news.index',
        'uses'  => 'NewsController@getIndex',
    ));
Route::get('/news/detail/{id}', array(
        'as'    => 'news.detail',
        'uses'  => 'NewsController@getDetail',
    ));

/**
 * Users controller (used for public viewing of group user info)
 */
Route::get('users/search/{searchText?}', array(
        'uses'  => 'UsersController@getSearch',
        'as'    => 'users.search',
    ));
Route::post('users/search', 'UsersController@postSearch');
Route::controller('users', 'UsersController', array(
        'getTopChart'   => 'users.topcharts',
    ));

/**
 * User controller (used for private handling of an individual user account and info)
 * Also used for viewing an individual's profile
 */
Route::get('/forgot', array(
        'as'        => 'user.forgot',
        'uses'      => 'UserController@getForgot',
    ));

Route::get('/user/my-account', array(
        'as'        => 'user.my-account',
        'uses'      => 'UserController@getMyAccount',
    ));
Route::post('/user/my-account', 'UserController@postMyAccount');

Route::get('/user/search', array(
        'as'        => 'user.search',
        'uses'      => 'UserController@search',
    ));

Route::get('/user/lessons', array(
        'as'        => 'user.lessons',
        'uses'      => 'UserController@getLessons',
    ));

Route::post('/user/change-password', array(
        'as'        => 'user.change-password',
        'uses'      => 'UserController@postChangePassword',
    ));

Route::post('/user/status', array(
        'as'        => 'user.status',
        'uses'      => 'UserController@postStatus',
    ));

Route::get('/user/billing', array(
        'as'        => 'user.billing',
        'uses'      => 'UserController@getBilling',
    ));

Route::post('/user/billing', 'UserController@postRateChange');

Route::get('/user/credit', array(
        'as'        => 'user.credit',
        'uses'      => 'UserCreditController@getIndex',
    ));

Route::get('/user/calendarEvents/{id}', array(
        'as'        => 'user.calendar-events',
        'uses'      => 'UserController@getCalendarEvents',
    ));

Route::get('/user/messages/{username?}', array(
        'as'        => 'user.messages',
        'uses'      => 'UserMessageController@index',
    ));

Route::get('/user/timezone', array(
        'as'    => 'user.timezone',
        'uses'  => 'UserController@getTimezoneChange',
    ));
Route::post('/user/timezone', 'UserController@postTimezoneChange');

Route::get('/user/{username}', array(
        'as'        => 'user.profile',
        'uses'      => 'UserController@getView',
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

Route::get('/lesson/{lesson}/whiteboard', array(
        'uses'      => 'LessonController@getWhiteboard',
        'as'        => 'lesson.whiteboard',
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

Route::post('/lesson/{lesson}/updatetimer', array(
        'uses'      => 'LessonController@postUpdateTimer',
        'as'        => 'lesson.updateTimer'
    ));

Route::get('/lesson/{lesson}/payment', array(
        'uses'      => 'LessonController@getPayment',
        'as'        => 'lesson.payment'
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

/**
 * Transaction controller
 */
Route::get('/transaction/buy', array(
        'as'        => 'transaction.buy',
        'uses'      => 'TransactionController@getBuy',
    ));
Route::post('/transaction/buy', 'TransactionController@postBuy');
Route::post('/transaction/sell', array(
        'as'    => 'transaction.sell',
        'uses'  =>'TransactionController@postSell',
    ));

/**
 * UserCredit controller
 */
Route::get('/user/credits', array(
        'as'        => 'credits.index',
        'uses'      => 'UserCreditController@getIndex',
    ));

/**
 * Password reminders controller
 */
Route::get('/password/remind', array(
        'uses' => 'RemindersController@getRemind',
        'as' => 'password.remind'
    ));
Route::get('password/reset/{token}', 'RemindersController@getReset');
Route::post('password/reset/{token}', 'RemindersController@postReset');
Route::controller('password', 'RemindersController');