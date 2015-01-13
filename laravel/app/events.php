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
        Cache::forget('users-online');
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
        Cache::forget('users-online');
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
Event::listen('user.password-reset-request', function($user){
        // Add to User log
        $user->logEvent('password-reset-request');
    });

Event::listen('user.password-reset-success', function($user){
        // Add to User log
        $user->logEvent('password-reset-success');
    });

Event::listen('user.password-reset-failure', function($user){
        // Add to User log
        $user->logEvent('password-reset-failure');
    });

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
        Cache::forget('users-joined');
        Category::resetUserCountCaches([], explode(", ", $user->subject));
        // Add to User log
        $user->logEvent('expert-registration');
    });

Event::listen('user.student-registration', function($user){
        Cache::forget('users-joined');
        // Add to User log
        $user->logEvent('student-registration');
    });

Event::listen('user.new-status', function($userStatus){
        // Add to User log
        $userStatus->logEvent('new-status');
    });

Event::listen('lesson.created', 'LessonHandler@onCreate');

Event::listen('lesson.updated', 'LessonHandler@onUpdate');

Event::listen('lesson.confirmed', 'LessonHandler@onConfirm');

Event::listen('lesson.reviewed', 'LessonHandler@onReview');

Event::listen('lesson.paid', 'LessonHandler@onPaid');

Event::listen('lesson.payment-failed', 'LessonHandler@onPaymentFailed');

Event::listen('user.sent-message', function($userMessage){
        // Add to User log
        $userMessage->logEvent('sent-message');
    });

Event::listen('user.email-notification-failed', function($userMessage, $error){
        // Add to User log
        $userMessage->logEvent('email-notification-failed', $error);
    });

Event::listen('userMessage.sent', function($userMessage, $recipient, $type){
        // Notify the recipient that they have a message waiting for them
        $recipient->notify($userMessage, $type);
    });

/**
 * Transaction events
 */
Transaction::creating(function(Transaction $transaction){
        $transaction->created = $transaction->freshTimestampString();
    });

Event::listen('transaction.test', 'TransactionHandler@onTest');
Event::listen('transaction.purchase', 'TransactionHandler@onPurchase');
Event::listen('transaction.sale', 'TransactionHandler@onSale');