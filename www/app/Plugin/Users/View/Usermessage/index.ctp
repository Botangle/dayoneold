<script>
    $(document).ready(function () {
        $(".Message-lists").niceScroll({cursorborder: "", cursorcolor: "#F38918", boxzoom: true}); // First scrollable DIV
        var $t = $('.Message-lists');
        $t.animate({"scrollTop": $('.Message-lists')[0].scrollHeight}, "slow");
    });
</script>
<?php
echo $this->Layout->js();
echo $this->Html->script(
    array(
        '/croogo/js/fileupload',
        '/croogo/js/jquery/bootstrap',
        '/croogo/js/jquery.nicescroll.min',
    )
);
echo $this->element(
    "breadcrame",
    array(
        'breadcrumbs' =>
            array(__('My Messages') => __('My Messages'))
    )
);

?>

<div id="main-content">
    <div class="container">
        <div class="row-fluid">
            <div class="span12">

            </div>
        </div>
        <div class="row-fluid">
            <?php echo $this->Element("myaccountleft") ?>
            <div class="span9">
                <div class="StaticPageRight-Block">
                    <div class="row-fluid">
                        <div class="span4 Message-List-Block">
                            <?php
                            if (!empty($userData)) {
                                foreach ($userData as $k => $v) {
                                    ?>
                                    <a class="" title="<?php echo $v['User']['username']; ?>"
                                       href="/users/messages/<?php echo $v['User']['username']; ?>">
                                        <div class="Message-row">
                                            <div class="row-fluid">
                                                <div class="span4 sender-img">
                                                    <?php

                                                    if (file_exists(
                                                            WWW_ROOT . DS . 'uploads' . DS . $v['User']['id'] . DS . 'profile' . DS . $v['User']['profilepic']
                                                        ) && $v['User']['profilepic'] != ""
                                                    ) {
                                                        ?>
                                                        <img
                                                            src="<?php echo $this->webroot . 'uploads/' . $v['User']['id'] . '/profile/' . $v['User']['profilepic'] ?> "
                                                            class="img-circle" alt="student" width="242px"
                                                            height="242px">
                                                    <?php } else { ?>
                                                        <img src="<?php echo $this->webroot ?>images/thumb-typ1.png"
                                                             class="img-circle" alt="student">
                                                    <?php } ?>
                                                </div>
                                                <div class="span8 sender-name">
                                                    <?php echo ucfirst($v['User']['username']); ?>
                                                    <br><span
                                                        class="FontStyle11"><?php echo $this->User->GettimedifferencedayBase(
                                                            $v['Usermessage']['date']
                                                        ); ?></span></div>
                                            </div>
                                        </div>

                                    </a>
                                <?php
                                }
                            }
                            ?>
                        </div>
                        <div class="span8 Message-detail-Block" id="boxscroll">
                            <div class="Message-lists">
                                <?php
                                if (!empty($sent_from)) {
                                    foreach ($sent_from as $k => $v) {
                                        if ($v['Usermessage']['sent_from'] == $loginUser) {
                                            ?>

                                            <div class="row-fluid">
                                                <div class="span2 sender-img"><?php

                                                    if (file_exists(
                                                            WWW_ROOT . DS . 'uploads' . DS . $v['User']['id'] . DS . 'profile' . DS . $v['User']['profilepic']
                                                        ) && $v['User']['profilepic'] != ""
                                                    ) {
                                                        ?>
                                                        <img
                                                            src="<?php echo $this->webroot . 'uploads/' . $v['User']['id'] . '/profile/' . $v['User']['profilepic'] ?> "
                                                            class="img-circle" alt="student" width="242px"
                                                            height="242px">
                                                    <?php } else { ?>
                                                        <img src="<?php echo $this->webroot ?>images/thumb-typ1.png"
                                                             class="img-circle" alt="student">
                                                    <?php } ?></div>
                                                <div class="span10 sender-text">
                                                    <div id="tip-left">&nbsp;</div>
                                                    <p class="sender-name"><?php echo ucfirst(
                                                            $v['User']['username']
                                                        ) ?></p>

                                                    <p class="msg-content">
                                                        <?php echo nl2br($v['Usermessage']['body']) ?></p>

                                                    <p class="msg-time"><?php echo $this->User->Gettimedifference(
                                                            $v['Usermessage']['date']
                                                        ); ?></p>
                                                </div>
                                            </div>
                                        <? } else { ?>
                                            <div class="row-fluid">

                                                <div class="span10 sender-text">
                                                    <div id="tip-right">&nbsp;</div>
                                                    <p class="sender-name"><?php echo ucfirst(
                                                            $v['User']['username']
                                                        ) ?></p>

                                                    <p class="msg-content">
                                                        <?php echo nl2br($v['Usermessage']['body']) ?></p>

                                                    <p class="msg-time"><?php echo $this->User->Gettimedifference(
                                                            $v['Usermessage']['date']
                                                        ); ?></p>
                                                </div>
                                                <div class="span2 sender-img">
                                                    <?php

                                                    if (file_exists(
                                                            WWW_ROOT . DS . 'uploads' . DS . $v['User']['id'] . DS . 'profile' . DS . $v['User']['profilepic']
                                                        ) && $v['User']['profilepic'] != ""
                                                    ) {
                                                        ?>
                                                        <img
                                                            src="<?php echo $this->webroot . 'uploads/' . $v['User']['id'] . '/profile/' . $v['User']['profilepic'] ?> "
                                                            class="img-circle" alt="student" width="242px"
                                                            height="242px">
                                                    <?php } else { ?>
                                                        <img src="<?php echo $this->webroot ?>images/thumb-typ1.png"
                                                             class="img-circle" alt="student">
                                                    <?php } ?>

                                                </div>

                                            </div>

                                        <?php
                                        }
                                    }
                                }?>
                            </div>
                            <?php echo $this->Form->create(
                                'Usermessage',
                                array('class' => 'form-inline form-horizontal', "role" => "form")
                            );
                            $this->request->data = $this->Session->read("Auth.User");
                            echo $this->Form->input('id', array('value' => ''));
                            echo $this->Form->hidden('send_to', array('value' => $user['User']['id']));
                            echo $this->Form->hidden('parent_id', array('value' => $parentid));

                            ?>
                            <div id="Write-msg">

                                <?php echo $this->Form->textarea(
                                    'body',
                                    array('class' => 'textarea', 'placeholder' => "Type Your message", 'rows' => 3)
                                ); ?>


                                <div class="span5 pull-right msg-send-btn"> <?php echo $this->Form->button(
                                        __("Send Message"),
                                        array('type' => 'submit', 'class' => 'btn btn-primary')
                                    );
                                    ?></div>
                            </div>
                            <?php echo $this->Form->end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>