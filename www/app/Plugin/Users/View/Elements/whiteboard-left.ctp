<div class="span3 LeftMenu-Block">
    <?php echo $this->Element("whiteboard_video_chat") ?>

    <?php /* @TODO: put a chat log down this left sidebar long-term, per our mockups */ ?>

    <input type="button" value="Exit / Complete Lesson" class="btn btn-primary" id="exitlesson" onclick="exitLesson('<?php echo $this->Session->read('Auth.User.role_id')?>')" <?php echo $disabled?>/>

    <?php if($role_id == 4): ?>
    <div class="price-area" style="display: none">You will pay $<span></span> when you finish.</div>
    <?php else: ?>
    <div class="price-area" style="display: none">You will be paid $<span></span> when you finish.</div>
    <?php endif; ?>

</div>
