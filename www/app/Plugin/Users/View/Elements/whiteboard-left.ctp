<div class="span3 LeftMenu-Block">
    <?php echo $this->Element("whiteboard_video_chat", array(
        )) ?>

    <?php /* @TODO: put a chat log down this left sidebar long-term, per our mockups */ ?>

    <input type="button" value="Exit / Complete Lesson" class="btn btn-primary" id="exitlesson" onclick="exitLesson('<?php echo $this->Session->read('Auth.User.role_id')?>')" <?php echo $disabled?>/>

</div>
