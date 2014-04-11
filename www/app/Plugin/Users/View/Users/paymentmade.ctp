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
        <h2 class="page-title">Payment Made</h2>
        <div class="StaticPageRight-Block">
          <div class="PageLeft-Block">
            <div class="control-group">
                <?php if(isset($lessonPayment) && count($lessonPayment) > 0): ?>
                <p>Thanks!  Your payment for the lesson you just finished has successfully gone through.</p>

                <p>Payment details:</p>
                <p>
                    <b>Amount: $<?php echo h(number_format($lessonPayment['LessonPayment']['payment_amount'])) ?></b><br>
                    Stripe Charge ID: <?php echo h($lessonPayment['LessonPayment']['stripe_charge_id']) ?><br>
                </p>

                <p>Please do let us know if you have any questions!</p>
                <?php else: ?>
                    Whoops, looks like we had a problem, please get in touch with us to resolve your issues.
                <?php endif; ?>
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
