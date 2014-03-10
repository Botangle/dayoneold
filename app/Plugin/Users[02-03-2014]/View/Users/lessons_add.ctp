<!--Wrapper HomeServices Block Start Here-->
<style>
    .radio {
        float: left;
        padding: 0px 10px 1px 10px !important;
        margin-top: 0px !important;
    }

    .controls input {
        float: left;
    }
</style>

<?php 
echo $this->element("breadcrame",array('breadcrumbs'=>
array(__("My Lesson")=>__("My Lesson")))
);?>


<script>
    var url = "/botangle/users/searchstudent";

    jQuery(function () {
        $j("#LessonTutor").autocomplete({
            minLength: 2,
            source: url = Croogo.basePath + "users/searchstudent/",
            focus: function (event, ui) {
                $j("#LessonTutor").val(ui.item.label);
                return false;
            },
            select: function (event, ui) {
                $j("#LessonTutor").val(ui.item.label);
                return false;
            }
        })
    })


</script>
<!--Wrapper main-content Block Start Here-->
<div id="main-content">
    <div class="container">
        <div class="row-fluid">
            <div class="span12">

            </div>
        </div>
        <div class="row-fluid">
            <?php echo $this->Element("myaccountleft") ?>
            <?php include_once("lessoncreate.ctp") ?>
        </div>
        <!-- @end .row -->


    </div>
    <!-- @end .container -->
</div>
<!--Wrapper main-content Block End Here--> 