<?php
/**
 * breadcrumbs.php
 *
 * @author: David Baker <dbaker@acorncomputersolutions.com
 * Date: 7/7/14
 * Time: 2:49 PM
 */

Breadcrumbs::register('home', function($breadcrumbs) {
        $breadcrumbs->push('Home', route('home'));
    });

Breadcrumbs::register('blog', function($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push('Blog', route('blog'));
    });

Breadcrumbs::register('categories.index', function($breadcrumbs) {
        $breadcrumbs->parent('home');

        $breadcrumbs->push('Popular Categories', route('categories.index'));
    });

Breadcrumbs::register('login', function($breadcrumbs) {
        $breadcrumbs->parent('home');

        $breadcrumbs->push('Sign In', route('login'));
    });

Breadcrumbs::register('user.billing', function($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push('Billing', route('user.billing'));
    });
Breadcrumbs::register('user.lessons', function($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push('Lessons', route('user.lessons'));
    });
Breadcrumbs::register('user.messages', function($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push('My Messages', route('user.messages'));
    });
Breadcrumbs::register('user.my-account', function($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push('My Account', route('user.my-account'));
    });
Breadcrumbs::register('user.profile', function($breadcrumbs, $user) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push(HTML::entities($user), route('user.profile', array($user)));
    });

Breadcrumbs::register('page', function($breadcrumbs, $page) {
        $breadcrumbs->parent('category', $page->category);
        $breadcrumbs->push($page->title, route('page', $page->id));
    });
	
Breadcrumbs::register('news.detail', function($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push('News', route('news.detail'));
    });

Breadcrumbs::register('how-it-works', function($breadcrumbs) {
	$breadcrumbs->parent('home');
	$breadcrumbs->push('How It Works', route('how-it-works'));
});