<?php

/*
|--------------------------------------------------------------------------
| Event Handlers
|--------------------------------------------------------------------------
|
|
*/


/**
 * Authentication related events
 */
Event::listen('auth.logout', function($user){

        // register that this user is online now
        $user->is_online = false;
        $user->save();

        // Add to User log
        $user->logEvent('logout');
    });

/**
 * Note: using user.login since auth.login seems to fire all over the place from Laravel
 */
Event::listen('user.login', function($user){
        // register that this user is online now
        $user->is_online = true;
        $user->save();

        // Add to User log
        $user->logEvent('login');
    });

Event::listen('user.login-attempt-failed', function($user){
        // Add to User log
        $user->logEvent('login-attempt-failed');
    });



/**
 * User events
 */
Event::listen('user.password-change', function($user){
        // Add to User log
        $user->logEvent('password-changed');
    });

Event::listen('user.account-updated', function($user){
        // Add to User log
        $user->logEvent('account-updated');
    });
