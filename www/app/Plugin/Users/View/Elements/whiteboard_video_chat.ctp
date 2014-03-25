<?php
$this->Html->css('whiteboard.css', array('inline' => false));

$tokBoxHelper = $this->Helpers->load('TokBox', Configure::read('TokBox'));
$tokBoxHelper->addHeadItems();

echo $tokBoxHelper->videoInformation();
?>
