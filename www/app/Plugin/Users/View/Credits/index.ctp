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
                    <?php echo __("Credits"); ?>

                    <?php if($balance > 0): ?>
                    <p class="pull-right">
                        <?php
                        echo $this->Html->link(
                            __('+  Add Credits'),
                            '/credits/create'
                            ,
                            array(
                                'title' => __('+  Add Credits'),
                                'class' => 'btn btn-primary btn-primary3',
                            )
                        );
                        ?>
                    </p>
                    <?php endif; ?>
                </h2>

                <?php if($balance == 0): ?>
                <div class="StaticPageRight-Block">
                    <div class="PageLeft-Block">
                        <div class="span12">
                            <p class="FontStyle20 color1">
                                <?php echo __("Purchase Credits")?>
                            </p>
                        </div>
                        <p>Looks like you don't have any pre-purchased Botangle credits.  We'll need you to add some before you can schedule a lesson.</p>
                        <?php include('_create.php'); ?>
                    </div>
                </div>
                <?php else: ?>
                <div class="StaticPageRight-Block">
                    <div class="PageLeft-Block">
                        <div class="span12">
                            <p class="FontStyle20 color1">
                                <?php echo __("Transactions")?>
                            </p>
                        </div>
                        <p>A list of transactions goes here</p>
                    </div>
                </div>

                <div class="StaticPageRight-Block">
                    <div class="PageLeft-Block">
                        <div class="span12">
                            <p class="FontStyle20 color1">
                                <?php echo __("Sell Credits")?>
                            </p>
                        </div>
                        <p>Sell your Botangle credits for cash.</p>
                        <p>Please note, there is a maximum limit of 100 credits sold each day. (make this small)</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div><!-- @end .row -->
    </div><!-- @end .container -->
</div><!--Wrapper main-content Block End Here-->