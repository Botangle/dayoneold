<?php
/**
 * create.ctp
 *
 * @author: David Baker <dbaker@acorncomputersolutions.com
 * Date: 7/18/14
 * Time: 12:00 PM
 */
?>

<!--Wrapper HomeServices Block Start Here-->
<?php echo $this->element("breadcrame",array('breadcrumbs'=>
        array('Credits'=>'Credits'))
);
?>

<!--Wrapper main-content Block Start Here-->
<div id="main-content">
    <div class="container">
        <div class="row-fluid">
            <div class="span12"></div>
        </div>
        <div class="row-fluid">
            <?php echo $this->Element("myaccountleft") ?>
            <div class="span9">
                <h2 class="page-title">
                    <?php echo __("Purchase Credits"); ?>
                </h2>


<div class="StaticPageRight-Block">
    <div class="PageLeft-Block">
        <?php include('_create.php'); ?>
    </div>
</div>




            </div>
        </div><!-- @end .row -->
    </div><!-- @end .container -->
</div><!--Wrapper main-content Block End Here-->