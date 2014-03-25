<?php
$this->Html->css('whiteboard.css', array('inline' => false));

$tokBoxHelper = $this->Helpers->load('TokBox', Configure::read('TokBox'));
$tokBoxHelper->addHeadItems();

echo $tokBoxHelper->videoInformation();
?>
<div class="video-chat">
    <div id="videoChatBox">Your tutor</div>
    <div id="small-stream">Me</div>
</div>

