<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| View Storage Paths
	|--------------------------------------------------------------------------
	|
	| Most templating systems load templates from disk. Here you may specify
	| an array of paths that should be checked for your views. Of course
	| the usual Laravel view path has already been registered for you.
	|
	*/

	'paths' => array(__DIR__.'/../views'),

	/*
	|--------------------------------------------------------------------------
	| Pagination View
	|--------------------------------------------------------------------------
	|
	| This view will be used to render the pagination link output, and can
	| be easily customized here to show any view you like. A clean view
	| compatible with Twitter's Bootstrap is given to you by default.
	|
	*/

	'pagination' => 'pagination::slider-3',

    /*
    |--------------------------------------------------------------------------
    | Virtuoso View Composers
    |--------------------------------------------------------------------------
    |
    | View Composers that we register with the Virtuoso system to keep things cleaner
    | Details available here: https://github.com/coderabbi/virtuoso/blob/master/README.md
    |
    */
    'composers' => array (
        '_partials.nav'             => 'Botangle\Composers\MainMenuComposer',
        '_partials.footer-middle'   => 'Botangle\Composers\FooterMiddleComposer',
    ),
);
