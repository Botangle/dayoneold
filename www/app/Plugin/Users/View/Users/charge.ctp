<!--Wrapper HomeServices Block Start Here-->
<?php echo $this->element("breadcrame",array('breadcrumbs'=>
  array('My Dashboard'=>'My Billing'))
  );?>

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
        <h2 class="page-title"><?php echo __("Billing")?></h2>
        <div class="StaticPageRight-Block">
          <div class="PageLeft-Block">
            <div class="row-fluid Add-Payment-blocks">

              <div class="span12">
                <p class="FontStyle20 color1"><?php echo __("Charge completed:")?></p>
              </div>

        </div>
        <!-- @end .row -->
      </div>
      <!-- @end .container -->
    </div>
    <!--Wrapper main-content Block End Here-->
