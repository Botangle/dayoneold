<?php

// Users
CroogoRouter::connect('/registration/:type', array('plugin' => 'users', 'controller' => 'users', 'action' => 'registration'), array('pass' => array('type')));
CroogoRouter::connect('/register', array('plugin' => 'users', 'controller' => 'users', 'action' => 'add'));
CroogoRouter::connect('/login', array('plugin' => 'users', 'controller' => 'users', 'action' => 'login'));
CroogoRouter::connect('/logout', array('plugin' => 'users', 'controller' => 'users', 'action' => 'logout'));
CroogoRouter::connect('/users/accountsetting', array('plugin' => 'users', 'controller' => 'users', 'action' => 'accountsetting'));
CroogoRouter::connect('/user/search', array('plugin' => 'users', 'controller' => 'users', 'action' => 'search'));
CroogoRouter::connect('/user/search/:online', array('plugin' => 'users', 'controller' => 'users', 'action' => 'search'), array('pass' => array('online')));
CroogoRouter::connect('/users/lessons', array('plugin' => 'users', 'controller' => 'users', 'action' => 'lessons'));
CroogoRouter::connect('/users/createlessons', array('plugin' => 'users', 'controller' => 'users', 'action' => 'lessons_add'));
CroogoRouter::connect('/users/users/createlessons', array('plugin' => 'users', 'controller' => 'users', 'action' => 'lessons_add'));
CroogoRouter::connect('/users/createlessons/:type/:tutorname', array('plugin' => 'users', 'controller' => 'users', 'action' => 'lessons_add'),array('pass'=>array('type','tutorname')));
CroogoRouter::connect('/users/searchstudent', array('plugin' => 'users', 'controller' => 'users', 'action' => 'searchstudent'));
CroogoRouter::connect('/users/updateremaining/', array('plugin' => 'users', 'controller' => 'users', 'action' => 'updateremaining'));
CroogoRouter::connect('/users/paymentmade/', array('plugin' => 'users', 'controller' => 'users', 'action' => 'paymentmade'));
CroogoRouter::connect('/users/changelesson/:lessonid', array('plugin' => 'users', 'controller' => 'users', 'action' => 'changelesson'),array('pass'=>array('lessonid')));
CroogoRouter::connect('/users/lessonreviews/:lessonid', array('plugin' => 'users', 'controller' => 'users', 'action' => 'lessonreviews'),array('pass'=>array('lessonid')));
CroogoRouter::connect('/users/confirmedbytutor/:lessonid', array('plugin' => 'users', 'controller' => 'users', 'action' => 'confirmedbytutor'),array('pass'=>array('lessonid')));
CroogoRouter::connect('/users/mycalander', array('plugin' => 'users', 'controller' => 'users', 'action' => 'mycalander'));
CroogoRouter::connect('/users/calandarevents', array('plugin' => 'users', 'controller' => 'users', 'action' => 'calandarevents'));
CroogoRouter::connect('/users/whiteboarddata/:lessonid', array('plugin' => 'users', 'controller' => 'users', 'action' => 'whiteboarddata'),array('pass'=>array('lessonid')));
CroogoRouter::connect('/users/calandareventsprofile/:userid', array('plugin' => 'users', 'controller' => 'users', 'action' => 'calandareventsprofile'),array('pass'=>'userid'));
CroogoRouter::connect('/forgot', array('plugin' => 'users', 'controller' => 'users', 'action' => 'forgot'));
CroogoRouter::connect('/passwordrecovery', array('plugin' => 'users', 'controller' => 'users', 'action' => 'passwordrecovery'));
CroogoRouter::connect('/user/:username', array(
	'plugin' => 'users', 'controller' => 'users', 'action' => 'view'), array('pass' => array('username')
));
CroogoRouter::connect('/user/id/:id', array(
        'plugin' => 'users', 'controller' => 'users', 'action' => 'viewId'), array('pass' => array('id')
    ));
CroogoRouter::connect('/users/topchart', array(
	'plugin' => 'users',
	'controller' => 'users',
	'action' => 'topchart')
);
CroogoRouter::connect('/users/topchart/:categoryname',
	array(
		'plugin' => 'users',
		'controller' => 'users',
		'action' => 'topchart'
	),
	array('pass' => array('categoryname'))
);
CroogoRouter::connect('/users/topchart/:categoryname/:online',
	array(
		'plugin' => 'users',
		'controller' => 'users',
		'action' => 'topchart'
	),
	array('pass' => array('categoryname','online'))
);

/**
 * User Messaging and Inviting
 */
CroogoRouter::connect('/users/messages/:username', array('plugin' => 'users', 'controller' => 'usermessage', 'action' => 'index'), array('pass' => array('username')));
CroogoRouter::connect('/users/messages', array('plugin' => 'users', 'controller' => 'usermessage', 'action' => 'index'));
CroogoRouter::connect('/users/joinuser/:id', array('plugin' => 'users', 'controller' => 'users', 'action' => 'joinuser'),array('pass'=>array('id')));
CroogoRouter::connect('/users/invite', array('plugin' => 'users', 'controller' => 'invite', 'action' => 'index'));

/**
 * Billing and payments
 */
CroogoRouter::connect('/users/billing', array(
	'plugin' => 'users',
	'controller' => 'users',
	'action' => 'billing')
);
CroogoRouter::connect('/users/paymentnotmade', array(
	'plugin' => 'users',
	'controller' => 'users',
	'action' => 'paymentnotmade')
);
CroogoRouter::connect('/users/claimoffer', array(
	'plugin' => 'users',
	'controller' => 'users',
	'action' => 'claimoffer')
);
CroogoRouter::connect('/users/paymentsetting', array(
	'plugin' => 'users',
	'controller' => 'users',
	'action' => 'paymentsetting')
);

/**
 * @TODO: work out how to get out of Croogo's ACL system being so tightly bound in to everything :-/
 * It's what forced us to go this route instead of having our own nice, clean plugin for the API
 *
 * API v1 routing
 *
 * We use manual .xml extensions on our routes, along with a 'ext' => 'xml' addition to the route parameter
 * so that we don't have to worry about people trying to sniff through our app and potentially hacking in somewhere
 * where they shouldn't be ...
 */
Router::connect('/api/v1/login.xml', array('plugin' => 'users', 'controller' => 'users', 'action' => 'login', 'ext' => 'xml'));
Router::connect(
    '/api/v1/user/:username.xml',
    array(
        'plugin' => 'users',
        'controller' => 'users',
        'action' => 'view',
        'ext' => 'xml'
    ),
    array(
        'pass' => array(
            'username',
        ),
    )
);
Router::connect(
    '/api/v1/users/topchart.xml',
    array(
        'plugin'        => 'users',
        'controller'    => 'users',
        'action'        => 'topchart',
        'ext'           => 'xml',
    )
);
//CroogoRouter::connect('/users/topchart/:categoryname',
//    array(
//        'plugin' => 'users',
//        'controller' => 'users',
//        'action' => 'topchart'
//    ),
//    array('pass' => array('categoryname'))
//);
//CroogoRouter::connect('/users/topchart/:categoryname/:online',
//    array(
//        'plugin' => 'users',
//        'controller' => 'users',
//        'action' => 'topchart'
//    ),
//    array('pass' => array('categoryname','online'))
//);