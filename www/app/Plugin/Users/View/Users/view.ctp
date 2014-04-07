<?php
echo $this->element("breadcrame",array('breadcrumbs'=>
        array($user['User']['username']=>$user['User']['username']))
);

$subject = explode(",",$user['User']['subject']);


?>
<script src="<?php echo $this->webroot?>croogo/js/calander/bic_calendar.js"></script>
<!--Wrapper main-content Block Start Here-->
<div id="main-content">
<div class="container">
<div class="row-fluid">
    <div class="span12">
        <h2 class="page-title"></h2>
    </div>
</div>
<div class="row-fluid">
<div class="span9 PageLeft-Block">
<div class="row-fluid">
    <div class="span4">
        <?php

        if(file_exists(WWW_ROOT . DS . 'uploads' . DS . $user['User']['id']. DS . 'profile'. DS  .$user['User']['profilepic']) && $user['User']['profilepic']!=""){ ?>
            <img src="<?php echo $this->webroot. 'uploads/'.$user['User']['id'].'/profile/'.$user['User']['profilepic'] ?> "class="img-circle" alt="student" width="242px" height="242px">
        <?php }else{		 ?>
            <img src="<?php echo $this->webroot?>images/botangle-default-pic.jpg" class="img-circle" alt="student">
        <?php } ?>
    </div>
    <div class="span8">
        <p class="pull-right">Rate : <span class="FontStyle16 color1"><strong>$

                    <?php


                    if(!empty($userRate)){
                        if($userRate['UserRate']['price_type']=='permin')
                        { $userRatePrice='min';} else  { $userRatePrice= $userRate['UserRate']['price_type'];}

                        echo $userRate['UserRate']['rate']."/". $userRatePrice; } else{ echo 0; } ?></strong></span></p>
        <p class="FontStyle28 color1"><?php echo ucfirst($user['User']['name']." ".$user['User']['lname']);?>
            <?php /* if($user['User']['is_online'] == 1){ ?>
                <img src="<?php echo $this->webroot?>images/online.png" alt="online">
            <?php } else {  ?>
                <img src="<?php echo $this->webroot?>images/offline.png" alt="onffline">
            <?php } */ ?>
            <br /><span style="font-size:13px"><?php echo ucfirst($user['User']['username']);?></span>
            <br/>
            <?php if(isset($userstatus[0]['Mystatus']['status_text'])) { ?>
                <span style="font-size:13px"><b>Mood: </b><?php echo(htmlspecialchars($userstatus[0]['Mystatus']['status_text'],ENT_QUOTES)) ;?></span>
            <?php } ?>

        </p>

        <p><?php echo $user['User']['university']?><br>
            <br>
            <?php echo $user['User']['other_experience']?></p>
        <p><?php echo __("Subject:");
            foreach($subject as $k=>$v){
                echo '<span class="tag01">'.$v.'</span> ';
            }
            ?>
        </p>
        <div class="row-fluid">
            <div class="span6"><span class="pull-left">Botangle Star: &nbsp; </span> <input type="number" name="your_awesome_parameter" id="some_id" class="rating" value="<?php echo round($userRating[0]['avg'])?>" readonly="isReadonly"/></div>
            <div class="span3"><span class="color1"><?php echo count($userReviews) ?> <?php echo __("Reviews")?></span></div>
            <div class="span3"><span class="color1"><?php echo $lessonClasscount[0][0]['totalrecords']?> <?php echo __("Classes")?></span></div>
        </div>
        <div class="row-fluid Rate-this-tutor message-tutor">
            <!--<div class="span6"><span class="pull-left">Give your Rating: &nbsp; </span> <input type="number" name="your_awesome_parameter" id="some_id" class="rating" data-clearable="remove"/></div>-->
            <!--<div class="span6"><span class="color1" style="line-height:20px;"><a href="#"><i class=" icon-comment"></i>Place your Review</a></span></div>-->
            <p class="option-msg">
                <?php
                echo $this->Html->link(
                    __(''),	'/users/messages/'.$user['User']['username'],
                    array('data-toggle'=>'Message','title'=>__('Message') ));
                ?>
            </p>

        </div>



        <p>Share:
	  <span class="profile-share">
	 	 <a href="#">
             <img src="<?php echo $this->webroot?>images/fb.png" alt="email">
         </a>
	  </span> 
	  <span class="profile-share">
	   <a href="#">
           <img src="<?php echo $this->webroot?>images/twitter.png" alt="email">
       </a>
	  </span> 
	  <span class="profile-share">
	   <a href="#">
           <img src="<?php echo $this->webroot?>images/mail.png" alt="email">
       </a>
	  </span></p>


    </div>

</div>

<!-- Student Profile tabs-->
<div class="row-fluid">
      <span class="span12 profile-tabs">
            <ul id="myTab" class="nav nav-tabs">
                <li class="active"><a href="#home" data-toggle="tab">Feed</a></li>
                <li class=""><a href="#aboutprofile" data-toggle="tab">About Me</a></li>
                <li class=""><a href="#profile" data-toggle="tab">My Reviews</a></li>


            </ul>
            <div id="myTabContent" class="tab-content">

                <!-- tab 1 -->
                <div class="tab-pane fade active in" id="home">
                    <div class="col-lg-12">
                        <!--timeline start-->
                        <section class="panel">
                            <div class="panel-body">
                                <div class="row-fluid Add-Payment-blocks bottomrow">

                                    <?php if($this->Session->read('Auth.User.role_id')==2 && $this->Session->read('Auth.User.id')==$user['User']['id'])
                                    { ?>
                                    <div class="span12">
                                        <p class="FontStyle20 color1"><?php echo __("What's in Your mind:")?></p>
                                    </div>

                                    <?php $this->request->data = $this->Session->read("Auth.User");
                                    echo $this->Form->create('Users',array('class'=>'form-horizontal','action' => 'mystatus'));
                                    ?>
                                    <div class="control-group"><br/>
                                        <div>
                                            <label class="inline span11">
                                                <?php echo $this->Form->textarea('status_text',array('placeholder'=>"What's in your mind?",'label' => false,'value'=>'','type'=>'text','class'=>'userstatus','maxlength'=>'300'));?>
                                                <div id="textarea_feedback" class="chrremaing"></div>
                                                <?php echo $this->Form->hidden('username',array('value'=>$user['User']['username'],'type'=>'text'));?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row-fluid Add-Payment-blocks">

                                        <div class="span5">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </div>
                                </div>
                                <?php echo $this->Form->end();
                                }?>

                                <div class="timeline1">
                                    <?php $i=1;$j=1;
                                    if(!empty($userstatus))
                                    { ?>
                                        <?php
                                        echo '<div class="left width50">';
                                        foreach($userstatus as $userstatues) {
                                            if($i%2 !=0)
                                            { ?>
                                                <div class="timelinestatus">
                                                    <p class="status"><?php echo $userstatues['Mystatus']['status_text']; ?></p>
                                                    <p class="time"><?php echo date('d M Y | l',strtotime($userstatues['Mystatus']['created']));?>
                                                        <?php echo date('h:i a',strtotime($userstatues['Mystatus']['created']));?></p>
                                                </div>
                                            <?php }
                                            $i++;
                                        }
                                        echo '</div>';
                                        echo '<div class="right width50">';
                                        foreach($userstatus as $userstatues) {
                                            if($j%2 ==0)
                                            { ?>
                                                <div class="timelinestatusright right">
                                                    <p class="status"><?php echo $userstatues['Mystatus']['status_text']; ?></p>
                                                    <p class="time"><?php echo date('d M Y | l',strtotime($userstatues['Mystatus']['created']));?>
                                                        <?php echo date('h:i a',strtotime($userstatues['Mystatus']['created']));?></p>
                                                </div>
                                            <?php }

                                            $j++;
                                        }

                                        echo '</div>';

                                    }



                                    else { echo "No Status"; }
                                    ?>




                                </div>

                                <div class="clearfix">&nbsp;</div>
                            </div>
                        </section>
                        <!--timeline end-->
                    </div>

                </div>



                <div class="tab-pane fade" id="aboutprofile">
                    <div class="student-profile">
                        <a class="pull-left" href="#">
                            <img src="<?php echo $this->webroot?>images/aboutme-img.png" alt="about"> </a>
                        <div class="media-body">
                            <h4 class="media-heading"><?php echo __("Teaching Experience")?></h4>
                            <p><?php echo $user['User']['teaching_experience']?></p>
                        </div></div>

                    <div class="student-profile">
                        <a class="pull-left" href="#">
                            <img src="<?php echo $this->webroot?>images/interests-img.png" alt="about"> </a>
                        <div class="media-body">
                            <h4 class="media-heading"><?php echo __("Extracurricular Interests")?></h4>
                            <p><?php echo $user['User']['extracurricular_interests']?></p>
                        </div></div>
                    <?php if($user['User']['expertise']!=""){ ?>
                        <div class="student-profile">
                            <a class="pull-left" href="#">
                                <img src="<?php echo $this->webroot?>images/subjects.png" alt="subjects"> </a>
                            <div class="media-body">
                                <?php echo $user['User']['expertise']?>

                            </div></div> <?php } ?>
                </div>

                <div class="tab-pane fade" id="profile">
                    <div class="class-timeinfo">
                        Total Classes: <?php

                        echo $lessonClasscount[0][0]['totalrecords']?> &nbsp; &nbsp;   | &nbsp; &nbsp;   Total Time of Classes: <?php echo $lessonClasscount[0][0]['totalduration']?> hours
                    </div>

                    <?php
                    if(!empty($userReviews)) {
                        foreach($userReviews as $k=>$review){

                            ?>
                            <div class="Myclass-list row-fluid">
                                <div class="span2">
                                    <?php

                                    if(file_exists(WWW_ROOT . DS . 'uploads' . DS . $review['User']['id']. DS . 'profile'. DS  .$review['User']['profilepic']) && $review['User']['profilepic']!=""){ ?>
                                        <img src="<?php echo $this->webroot. 'uploads/'.$review['User']['id'].'/profile/'.$review['User']['profilepic'] ?> "class="img-circle" alt="student" width="242px" height="242px">
                                    <?php }else{		 ?>
                                        <img src="<?php echo $this->webroot?>images/people1.jpg" class="img-circle" alt="tutor">
                                    <?php } ?>
                                </div>
                                <div class="span3">
                                    <p class="FontStyle16">Class: <a href="#"><?php echo $review['Lesson']['subject']?></a></p>
                                    <p class="FontStyle11">Student: <strong><?php echo $review['User']['username']?></strong></p>
                                </div>
                                <div class="span5">
                                    <?php echo $review['Review']['reviews']?>
                                </div>
                                <div class="span2">
                                    <p><input type="number" name="your_awesome_parameter" id="some_id" class="rating"   value="<?php echo $review['Review']['rating']?>"/></p>
                                    <!--<button class="btn btn-primary btn-primary3" type="submit">Review</button> 	-->
                                </div>


                            </div>
                        <?php }
                    }else{ ?>
                        <div class="Myclass-list row-fluid">
                            <div class="span2"><?php echo __("No reviews yet")?></div></div>
                    <?php } ?>
                </div>








            </div>
          <script>
              jQuery(function () {
                  jQuery('#myTab a[href="#home"]').tab('show');
              })
          </script>
      </span>
</div>
</div>

<div class="span3 PageRight-Block-Cal PageRight-TopBlock">
    <div class="calendar">

        <script>
            jQuery(function () {

                var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

                var dayNames = ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"];

                var events = [
                    {
                        date: "",
                        title: '',
                        link: '',
                        linkTarget: '',
                        color: '',
                        content: '',
                        class: '',
                        displayMonthController: true,
                        displayYearController: true,
                        nMonths: 6
                    }
                ];

                $('#calendari_lateral1').bic_calendar({
                    //list of events in array
                    events: events,
                    //enable select
                    enableSelect: true,
                    //enable multi-select
                    multiSelect: true,
                    //set day names
                    dayNames: dayNames,
                    //set month names
                    monthNames: monthNames,
                    //show dayNames
                    showDays: true,
                    //show month controller
                    displayMonthController: true,
                    //show year controller
                    displayYearController: true,
                    //set ajax call
                    reqAjax: {
                        type: 'get',
                        url: '<?php echo $this->webroot?>users/calandareventsprofile/<?php echo $user['User']['id']?>'
                    }
                });
            });
        </script>
        <div id="calendari_lateral1"></div>

    </div>





</div>
</div>
<!-- @end .row -->

<div class="row-fluid ">
    <div class="Get-in-Touch offset6">
        <p class="FontStyle20"><strong>Get in touch with us:</strong></p>
    </div>

</div>
<div class="row-fluid ">
    <div class="Social-Boxs Social-Email span3">
        <p class="FontStyle20"><a href="#"> Email Us</a></p>
    </div>

    <div class="Social-Boxs Social-FB span3">
        <p class="FontStyle20"><a href="#"> Facebook Us</a></p>
    </div>

    <div class="Social-Boxs Social-Tweet span3">
        <p class="FontStyle20"><a href="#"> Follow Us</a></p>
    </div>

    <div class="Social-Boxs Social-Linkedin span3">
        <p class="FontStyle20"><a href="#"> LinkedIn</a></p>
    </div>

</div>



</div>
<!-- @end .container -->
</div>


<?php
echo $this->Html->script(array(
        '/croogo/js/bootstrap-rating-input.min',
    ));

?>


<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="left:40%; width:auto; right:20%; height:320px; overflow:hidden; top:25%">
    <?php include('lessoncreate.ctp') ?>
</div>
<?php
echo $this->Html->script(array(
        '/croogo/js/bootstrap-rating-input.min',
    ));

?>
<script type="text/javascript">
    $(document).ready(function() {
        var text_max = 300;
        var charre=text_max - $('#UsersStatusText').val().length;
        $('#textarea_feedback').html(charre + ' characters remaining');

        $('#UsersStatusText').keyup(function() {
            var text_length = $('#UsersStatusText').val().length;
            var text_remaining = text_max - text_length;

            $('#textarea_feedback').html(text_remaining + ' characters remaining');
        });
    });
    function callPopup(){

        var currentclass = jQuery(this).hasClass('reviews')
        var url = Croogo.basePath+'users/createlessons/ajax/<?php echo $user['User']['id']?>';
        jQuery('body').append('<div class="modal-backdrop in"></div>')
        jQuery("#myModal").css({'display':'block','height':'auto','top':'25%','position':'absolute'});

        jQuery('#myModal').css('height',jQuery('.StaticPageRight-Block').outerHeight()+120)
        jQuery('.PageLeft-Block').css({'border-top':0,'box-shadow':'none'}).parent('div.span9').css({width:825+'px'})
        jQuery('label[for="tutorname"]').html('Tutor');
        jQuery('.ui-autocomplete').css({'z-index':'999999'})

        jQuery('button[type="reset"]').click(function(){
            jQuery(".modal-backdrop").remove();
            jQuery("#myModal").css('display','none');
        })

    }
    function removebackground(){
        jQuery(".modal-backdrop").remove();
        jQuery("#myModal").html('').css('display','none');
    }
</script> 
