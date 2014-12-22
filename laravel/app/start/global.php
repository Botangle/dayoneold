<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/models',
	app_path().'/database/seeds',
    app_path().'/classes/Hashing',

));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

$logFile = 'laravel.log';

Log::useDailyFiles(storage_path().'/logs/'.$logFile);

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/
App::error(function(Exception $exception, $code)
    {
        Log::error($exception);
        switch($code){
            case 401:
            case 403:
                return Response::view('error.unauthorized', [], $code);
        }

        $emailViewData = [
            'exception' => $exception,
            'vars'      => get_defined_vars(),
        ];

        // If we're not in debug mode, send details of the error to all the system admins
        if (!Config::get('app.debug')){
            foreach(Config::get('app.admins') as $adminEmail){
                try {
                    Mail::send('emails.error', $emailViewData, function($message) use($adminEmail){
                            $message->to($adminEmail)
                                ->subject('Botangle Error');
                        });
                } catch(Exception $e){
                    Log::error($exception);
                }
            }
        }

        if (!Config::get('app.debug')){
            return Response::view('error.fatal', [], 500);
        }
    });

App::missing(function($exception)
    {
        Log::error($exception);
        return Response::view('error.missing', [], 404);
    });

App::error(function(Illuminate\Database\Eloquent\ModelNotFoundException $exception, $code)
    {
        Log::error($exception);
        return Response::view('error.missing', [], 404);
    });

App::error(function(\Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException $exception)
    {
        Log::error($exception);
        return Response::view('error.unauthorized', [], 405);
    });


/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(function()
{
    return Response::view('error.maintenance', [], 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

require app_path().'/filters.php';

/*
|--------------------------------------------------------------------------
| Require The Events (Event Handlers) File
|--------------------------------------------------------------------------
|
| Next we will load the events file for the application. This gives us
| a nice separate location to store our event handlers.
|
*/

require app_path().'/events.php';

/*
|--------------------------------------------------------------------------
| Macros
|--------------------------------------------------------------------------
|
*/

HTML::macro('leftmenu_link', function($route, $text) {
    $class = '';
    $routeName = Route::currentRouteName();

    if (isset($routeName)) {
        $class = $routeName == $route ? 'active' : '';
    }

    return '<li>' . HTML::linkRoute($route, $text, [], ['class' => $class, 'title' => $text]) . '</li>';
});