<?php
$this->Html->css('whiteboard.css', array('inline' => false));

$openTokHelper = $this->Helpers->load('OpenTok');
$openTokHelper->addHeadItems();

echo $openTokHelper->videoInformation($opentok_api_key, $opentok_session_id, $opentok_token);
?>
<div class="video-chat">
    <?php if($this->Session->read('Auth.User.role_id')==4): ?>
        <div id="videoChatBox">Your tutor</div>
        <div id="small-stream">You</div>
    <?php else: ?>
        <div id="videoChatBox">You</div>
        <div id="small-stream">Your tutor</div>
    <?php endif;?>
</div>

