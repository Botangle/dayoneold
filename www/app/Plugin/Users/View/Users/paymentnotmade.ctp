<?php

echo $this->element("breadcrame", array('breadcrumbs'=>
	array('My Dashboard'=>'My Dashboard'))
);

?>

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
        <h2 class="page-title">Payment Pending</h2>
        <div class="StaticPageRight-Block">
          <div class="PageLeft-Block">
            <div class="control-group">
			           Your older payment is pending. <a href="<?php echo $this->webroot?>users/billing">Click Here</a> to make payment.
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
