<?php

// Basic
CroogoRouter::connect('/', array(
	'plugin' => 'nodes', 'controller' => 'nodes', 'action' => 'promoted'
));

CroogoRouter::connect('/promoted/*', array(
	'plugin' => 'nodes', 'controller' => 'nodes', 'action' => 'promoted'
));

CroogoRouter::connect('/search/*', array(
	'plugin' => 'nodes', 'controller' => 'nodes', 'action' => 'search'
));

CroogoRouter::connect('/reportbug', array(
	'plugin' => 'nodes', 'controller' => 'nodes', 'action' => 'reportbug',
	 
));

// Content types
CroogoRouter::contentType('blog');
CroogoRouter::contentType('node');
if (Configure::read('Croogo.installed')) {
	CroogoRouter::routableContentTypes();
}

// Page
CroogoRouter::connect('/how-it-works', array(
        'plugin' => 'nodes', 'controller' => 'nodes', 'action' => 'view',
        'type' => 'page', 'slug' => 'how-it-works'
    ));
CroogoRouter::connect('/policies', array(
        'plugin' => 'nodes', 'controller' => 'nodes', 'action' => 'view',
        'type' => 'page', 'slug' => 'policies'
    ));
CroogoRouter::connect('/about', array(
	'plugin' => 'nodes', 'controller' => 'nodes', 'action' => 'view',
	'type' => 'page', 'slug' => 'about'
));
CroogoRouter::connect('/privacy', array(
	'plugin' => 'nodes', 'controller' => 'nodes', 'action' => 'view',
	'type' => 'page', 'slug' => 'privacy'
));
CroogoRouter::connect('/faq', array(
	'plugin' => 'nodes', 'controller' => 'nodes', 'action' => 'view',
	'type' => 'page', 'slug' => 'faq'
));
CroogoRouter::connect('/terms', array(
	'plugin' => 'nodes', 'controller' => 'nodes', 'action' => 'view',
	'type' => 'page', 'slug' => 'terms'
));
CroogoRouter::connect('/page/:slug', array(
	'plugin' => 'nodes', 'controller' => 'nodes', 'action' => 'view',
	'type' => 'page'
));