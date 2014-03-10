<?php

			echo $this->Html->css(array(
'/croogo/css/calander/calendar',

));
echo $this->element("breadcrame",array('breadcrumbs'=>
array('My Dashboard'=>'My Dashboard'))
);

?>
<style type="text/css">
    .btn-twitter {
        padding-left: 30px;
        background: rgba(0, 0, 0, 0) url(https://platform.twitter.com/widgets/images/btn.27237bab4db188ca749164efd38861b0.png) -20px 6px no-repeat;
        background-position: -20px 11px !important;
    }

    .btn-twitter:hover {
        background-position: -20px -18px !important;
    }
</style>
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
                <h2 class="page-title"><?php echo __("My Calander")?></h2>

                <div class="StaticPageRight-Block">
                    <div class="PageLeft-Block">
                        <div class="My-Calendar">
                            <div class="page-header">

                                <div class="Cal-arrow-box form-inline">
                                    <div class="btn-group Calendar-header">
                                        <button class="btn Cal-Left-btn" data-calendar-nav="prev"><< Prev</button>
                                        <!--<button class="btn Cal-middle-btn" data-calendar-nav="today">Today</button>-->
                                        <button class="btn Cal-Right-btn" data-calendar-nav="next">Next >></button>
                                    </div>

                                </div>

                                <h3></h3>

                            </div>

                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- @end .row -->


    </div>
    <!-- @end .container -->
</div>
<!--Wrapper main-content Block End Here-->
<script src="http://bootstrap-calendar.azurewebsites.net/components/underscore/underscore-min.js"></script>
<?php
 echo $this->Layout->js();
echo $this->Html->script(array(


'/croogo/js/calander/calendar',
'/croogo/js/calander/app',
));
?>