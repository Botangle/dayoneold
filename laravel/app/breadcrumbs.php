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

Breadcrumbs::register('register.student', function($breadcrumbs) {
        $breadcrumbs->parent('home');

        $breadcrumbs->push('Student Sign Up', route('register.student'));
    });
Breadcrumbs::register('register.expert', function($breadcrumbs) {
        $breadcrumbs->parent('home');

        $breadcrumbs->push('Expert Sign Up', route('register.expert'));
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
        // For some reason, this is getting called twice, and the second time the $user object
        //   isn't being passed through but the username instead. So, this is a workaround for now.
        if(is_object($user)){
            $breadcrumbs->parent('home');
            $breadcrumbs->push(HTML::entities($user->fullname), route('user.profile', $user->username));
        }
    });

Breadcrumbs::register('users.top-chart', function($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push('Top Charts', route('users.top-chart'));
    });
Breadcrumbs::register('users.search', function($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push('Search', route('users.search'));
    });

Breadcrumbs::register('password.remind', function($breadcrumbs) {
        $breadcrumbs->parent('home');

        $breadcrumbs->push('Forgot Password', route('password.remind'));
    });

Breadcrumbs::register('lesson.whiteboard', function($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push('Lessons', route('user.lessons'));
        $breadcrumbs->push('Whiteboard', route('lesson.whiteboard'));
    });

Breadcrumbs::register('transaction.buy', function($breadcrumbs) {
        $breadcrumbs->parent('home');
        if (Auth::user()){
            $user = Auth::user();
            $breadcrumbs->push(HTML::entities($user->fullname), route('user.profile', $user->username));
        }
        $breadcrumbs->push('Buy Credit', route('transaction.buy'));
    });

Breadcrumbs::register('credit.index', function($breadcrumbs) {
        $breadcrumbs->parent('home');
        if (Auth::user()){
            $user = Auth::user();
            $breadcrumbs->push(HTML::entities($user->fullname), route('user.profile', $user->username));
        }
        $breadcrumbs->push('Credits', route('credit.index'));
    });

Breadcrumbs::register('page', function($breadcrumbs, $page) {
    $breadcrumbs->parent('category', $page->category);
    $breadcrumbs->push($page->title, route('page', $page->id));
});

Breadcrumbs::register('news.index', function($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push('News', route('news.index'));
    });

Breadcrumbs::register('news.detail', function($breadcrumbs, $news) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('News', route('news.index'));
    if(is_object($news)){
        $breadcrumbs->push($news->title, route('news.detail'));
    }
});

Breadcrumbs::register('how-it-works', function($breadcrumbs) {
	$breadcrumbs->parent('home');
	$breadcrumbs->push('How It Works', route('how-it-works'));
});

Breadcrumbs::register('about', function($breadcrumbs) {
	$breadcrumbs->parent('home');
	$breadcrumbs->push('About', route('about'));
});

Breadcrumbs::register('reportbug', function($breadcrumbs) {
	$breadcrumbs->parent('home');
	$breadcrumbs->push('Report A Bug', route('reportbug'));
});

Breadcrumbs::register('contact', function($breadcrumbs) {
	$breadcrumbs->parent('home');
	$breadcrumbs->push('Contact Us', route('contact'));
});

Breadcrumbs::register('terms', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Terms of Use and Privacy Policy', route('terms'));
});
