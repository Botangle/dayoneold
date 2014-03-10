<!--Wrapper HomeServices Block Start Here-->

<?php
echo $this->element("breadcrame",array('breadcrumbs'=>
array(__("Whiteboard")=>__("Whiteboard")))
);?>
<script src="<?php echo $this->webroot?>croogo/js/countdown.js" type="text/javascript"></script>


<!--Wrapper main-content Block Start Here-->
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
                    <div class="PageLeft-Block">

                        <div class="Lesson-row active">
                            <div class="row-fluid">
                                <?php
			 $remainingduration = 	$lesson['Lesson']['remainingduration'];
			 $twiddlaid = $lesson['Lesson']['twiddlameetingid'];
			echo "<BR>". $timeduration = $lesson['Lesson']['duration'] * 60 * 60;
                                echo "<BR>". $timeduration = $timeduration - $remainingduration;
                                if($timeduration <= 0 ){ ?>
                                <form method="get"
                                      action="<?php echo $this->webroot?>users/paymentmade/?tutor=<?php echo $lesson['Lesson']['created']?>&lessonid=<?php echo $lesson['Lesson']['id']?>">
                                    <input type="hidden" name="tutor"
                                           value="<?php echo $lesson['Lesson']['created']?>"/>
                                    <input type="hidden" name="lessonid" value="<?php echo $lesson['Lesson']['id']?>"/>
                                    <button type="submit">Make Payment</button>
                                </form>
                                <?php }else{
			  if($this->Session->read('Auth.User.role_id')==4){ ?>
                                <iframe src="http://www.twiddla.com/api/start.aspx?sessionid=<?php echo $twiddlaid?>&controltype=2&loginusername=deepakjain&password=123456789"
                                        frameborder="0" width="617" height="600"
                                        style="border:solid 1px #555;"></iframe>
                                <?php } else {?>
                                <iframe src="http://www.twiddla.com/api/start.aspx?sessionid=<?php echo $twiddlaid?>&controltype=1&loginusername=deepakjain&password=123456789&guestname=deep"
                                        frameborder="0" width="617" height="600"
                                        style="border:solid 1px #555;"></iframe>
                                <?php }
			}?>
                            </div>
                        </div>


                        <script type="application/javascript">
                            var remainingtime =
                            <
                            ? php echo
                            $timeduration ?
                            >
                            ;
                            var myCountdown1 = new Countdown({
                                time: < ? php echo
                            $timeduration ?
                            >, // 86400 seconds = 1 day
                            width:300,
                                    height
                            :
                            60,
                                    rangeHi
                            :
                            "hour",
                                    style
                            :
                            "flip"	// <- no comma on last item!
                            })
                            ;
                            if (remainingtime > 0) {
                                var lesson = setInterval(function () {
                                    jQuery.post(Croogo.basePath + "users/updateremaining/?time=1&lessonid=<?php echo $lesson['Lesson']['id']?>", function (e, v) {
                                        console.log(e)
                                        var donetime = eval('(' + e + ')')

                                        if (donetime.totaltime >= remainingtime) {
                                            clearInterval(lesson);
                                            alert("Lesson duration complete. Please Make payment");
                                            location.href = (Croogo.basePath + 'users/paymentmade/?tutor=<?php echo $lesson['
                                            Lesson
                                            ']['
                                            created
                                            ']?>&lessonid=<?php echo $lesson['
                                            Lesson
                                            ']['
                                            id
                                            ']?>'
                                        )
                                            ;
                                        }
                                    })
                                }, 60000)
                            }
                            ;
                        </script>

                    </div>


                </div>
            </div>
        </div>
        <!-- @end .row -->


    </div>
    <!-- @end .container -->
</div>
<!--Wrapper main-content Block End Here--> 