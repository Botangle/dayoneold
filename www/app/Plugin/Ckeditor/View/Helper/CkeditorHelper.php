<?php

App::uses('AppHelper', 'View/Helper');

/**
 * Ckeditor Helper
 *
 * PHP version 5
 *
 * @category Ckeditor.Helper
 * @package  Ckeditor.View.Helper
 * @version  1.5
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class CkeditorHelper extends AppHelper {

/**
 * Other helpers used by this helper
 *
 * @var array
 * @access public
 */
	public $helpers = array(
		'Html',
		'Js',
	);

/**
 * Actions
 *
 * Format: ControllerName/action_name => settings
 *
 * @var array
 */
	public $actions = array();

/**
 * beforeRender
 *
 * @param string $viewFile
 * @return void
 */
	public function beforeRender($viewFile) {
		$this->Html->script('/ckeditor/js/wysiwyg', array('inline' => false));

		if (is_array(Configure::read('Wysiwyg.actions'))) {
			$this->actions = Hash::merge($this->actions, Configure::read('Wysiwyg.actions'));
		}
		$action = Inflector::camelize($this->params['controller']) . '/' . $this->params['action'];
		if (Configure::read('Writing.wysiwyg') && isset($this->actions[$action])) {
			$this->Html->script('/ckeditor/js/ckeditor', array(
				'inline' => false,
			));

			$ckeditorActions = Configure::read('Wysiwyg.actions');
			if (!isset($ckeditorActions[$action])) {
				return;
			}
			$actionItems = $ckeditorActions[$action];
			$out = null;
			foreach ($actionItems as $actionItem) {
				$element = $actionItem['elements'];
				unset($actionItem['elements']);
				$config = empty($actionItem) ? '{}' : $this->Js->object($actionItem);
				$out .= sprintf(
					'Croogo.Wysiwyg.Ckeditor.setup("%s", %s);',
					$element, $config
				);
			}
			$this->Js->buffer($out);
		}
	}
}
