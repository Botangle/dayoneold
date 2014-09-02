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

Event::listen('user.profilepic-uploaded', function($user){
        // Add to User log
        $user->logEvent('profilepic-uploaded');
    });

Event::listen('user.expert-registration', function($user){
        // Add to User log
        $user->logEvent('expert-registration');
    });

Event::listen('user.student-registration', function($user){
        // Add to User log
        $user->logEvent('student-registration');
    });

Event::listen('user.new-status', function($userStatus){
        // Add to User log
        $userStatus->logEvent('new-status');
    });

Event::listen('user.booked-lesson', function($lesson){
        // Add to User log
        $lesson->logEvent('booked-lesson');
    });

Event::listen('user.amended-lesson', function($lesson){
        // Add to User log
        $lesson->logEvent('amended-lesson');
    });

Event::listen('user.confirmed-lesson', function($lesson){
        // Add to User log
        $lesson->logEvent('confirmed-lesson');
    });

Event::listen('user.reviewed-lesson', function($review){
        // Add to User log
        $review->logEvent('reviewed-lesson');
    });

Event::listen('user.sent-message', function($userMessage){
        // Add to User log
        $userMessage->logEvent('sent-message');
    });
