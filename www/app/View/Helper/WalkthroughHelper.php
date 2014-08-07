<?php
App::uses('AppHelper', 'View/Helper');

class WalkthroughHelper extends AppHelper {

    public $helpers = array('Html');

    public function __construct(View $View, $settings = array()) {

        parent::__construct($View, $settings);

        $this->Html->css('/js/hopscotch/css/hopscotch.min.css', array('inline' => false));

        $this->Html->script('/js/hopscotch/js/hopscotch.min.js', array('block' => 'scriptBottom'));
        $this->Html->script('/js/welcome-tour.js', array('block' => 'scriptBottom'));
    }
}