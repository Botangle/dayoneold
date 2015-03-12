<?php return array(

	// Zurb Foundation framework markup
	////////////////////////////////////////////////////////////////////

	// Map Former-supported viewports to Foundation 3 equivalents
	'viewports'           => array(
		'large'  => '',
		'medium' => null,
		'small'  => 'mobile-',
		'mini'   => null,
	),
	// Width of labels for horizontal forms expressed as viewport => grid columns
	'labelWidths'         => array(
		'large' => 2,
		'small' => 4,
	),
	// Classes to be applied to wrapped labels in horizontal forms
	'wrappedLabelClasses' => array('right', 'inline'),
	// HTML markup and classes used by Foundation 3 for icons
	'icon'                => array(
		'tag'    => 'i',
		'set'    => null,
		'prefix' => 'fi',
	),
	// CSS for inline validation errors
	// should work for Zurb 2 and 3
	'error_classes'       => array('class' => 'alert-box alert error'),

);
