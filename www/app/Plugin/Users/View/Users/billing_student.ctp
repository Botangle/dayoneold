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
          			<p class="FontStyle20 color1"><?php echo __("Make Payment:")?></p>
          		</div>

<!-- @stripe
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<script type="text/javascript">
  // This identifies your website in the createToken call below
  Stripe.setPublishableKey('pk_test_3e04C7TgDWTpq4appjFwGlOU');
  // ...

	jQuery(function($) {
	  $('#payment-form').submit(function(event) {
	    var $form = $(this);

	    // Disable the submit button to prevent repeated clicks
	    $form.find('button').prop('disabled', true);

	    Stripe.card.createToken($form, stripeResponseHandler);

	    // Prevent the form from submitting with the default action
	    return false;
	  });
	});


	var stripeResponseHandler = function(status, response) {
	  var $form = $('#payment-form');

	  if (response.error) {
	    // Show the errors on the form
	    $form.find('.payment-errors').text(response.error.message);
	    $form.find('button').prop('disabled', false);
	  } else {
	    // token contains id, last4, and card type
	    var token = response.id;
	    // Insert the token into the form so it gets submitted to the server
	    $form.append($('<input type="hidden" name="stripeToken" />').val(token));
	    // and submit
	    $form.get(0).submit();
	  }
	};
</script>

<form action="/users/billing" method="POST">
  <script
    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
    data-key="pk_test_3e04C7TgDWTpq4appjFwGlOU"
    data-image="/square-image.png"
    data-name="Botangle"
    data-description="One Lesson ($20.00)"
    data-amount="2000">
  </script>
</form>

-->
							<div class="row-fluid Add-Payment-blocks">
        				<div class="span5">
									<?php  echo $this->Form->create('Billing',array('class'=>'form-horizontal'));
									 echo $this->Form->input('pagetype',array('value'=>"billing",'type'=>'hidden'));
									?>
									<div class="control-group">
                		<label class="control-label" for="postalAddress"><?php echo __("Select Tutor:")?></label>
                		<div class="controls">
                			<?php
												echo $this->Form->input('student_payment',array('type'=>'hidden','value'=>'student_payment','name'=>'data[Billing][student_payment]'));
												echo $this->Form->input('tutor_id', array('class' => 'chzn-select', 'options' => $userInfo, 'label' => false, 'div' => array('class' => 'formRight noSearch', 'name'=>'fname', 'empty' => '(choose one)')));
											?>
                		</div>
              		</div>

									<div class="control-group">
                		<label class="control-label" for="postalAddress"><?php echo __("Payment Amount")?></label>
                		<div class="controls">
											<input type="text" value="<?php echo $paymentamount?>" name="payamount" />
											<input type="hidden" value="" name="lname" />
                		</div>
              		</div>

			  					<div class="control-group">
                		<label class="control-label" for="postalAddress"><?php echo __("Card Type")?></label>
                		<div class="controls">
                			<select name="card" id="card">
				 	 						<option value="Visa">Visa</option>
												<option value="Mastercard">Master Card</option>
				 							</select>
                		</div>
              		</div>

									<div class="control-group">
                		<label class="control-label" for="postalAddress"><?php echo __("Card Number")?></label>
                		<div class="controls">
											<input type="text" value="4242424242424242" name="acc_number" placeholder="Card No" />
										</div>
									</div>

									<div class="control-group">
										<label class="control-label" for="postalAddress"><?php echo __("Security Code")?></label>
										<div class="controls">
											<input type="text" value="456" name="card_security_code"  palceholder="CVV"/>
										</div>
									</div>

									<div class="control-group">
										<label class="control-label" for="postalAddress"><?php echo __("Expire Month")?></label>
										<div class="controls">
											<select name="expiration_month">
												<?php
													for($i = 1 ; $i< 13;$i++){
														echo '<option value = "'.$i.'">'.$i.'</option>';
													}
												?>
											</select>
                		</div>
              		</div>

									<div class="control-group">
										<label class="control-label" for="postalAddress"><?php echo __("Expire Year")?></label>
										<div class="controls">
											<select name="expiration_year">
												<?php
													$curyr = date('Y');
													for($i = $curyr+1; $i< ($curyr+25);$i++){
														echo '<option value = "'.$i.'">'.$i.'</option>';
													}
												?>
											</select>
                		</div>
              		</div>

									<div class="row-fluid payment-blocks">
										<div class="span6">&nbsp;</div>
											<div class="span5">
												<button type="submit" class="btn btn-primary clsmarginleft">Deposit Payment</button>
											</div>
										</div>
										<?php echo $this->Form->end();?>
          				</div>
          			</div>
							</div>
						</div>
					</div>

      		<!-- Hiding this because I don't think it's needed. Stage for removal -->
					<h2 class="page-title"><?php echo __("Payment Setting")?></h2>
      		<div class="StaticPageRight-Block">
      			<div class="PageLeft-Block">
							<div class="row-fluid Add-Payment-blocks">
								<div class="span12">
									<p class="FontStyle20 color1"><?php echo __("Connect with Stripe:")?></p>
								</div>

								<?php
									$this->request->data = $this->Session->read("Auth.User");
									echo $this->Form->create('User',array('class'=>'form-horizontal'));
									echo $this->Form->input('pagetype',array('value'=>"paymentsettings",'type'=>'hidden'));
								?>

								<div class="control-group">
              		<label class="control-label span5"><?php echo __("Stripe Account Email:")?></label>
              		<div class="controls">
		                <label class="radio inline span4">
		                   <?php echo $this->Form->input('stripe_id',array('placeholder'=>"Account Email",'label' => false,'value'=>$User['User']['stripe_id'],'type'=>'text'));?>
		                </label>
              		</div>
          			</div>

          			<div class="control-group">
              		<label class="control-label span5"><?php echo __("Stripe Secret Key:")?></label>
              		<div class="controls">
                		<label class="radio inline span4">
											<?php echo $this->Form->input('secret_key',array('placeholder'=>"Secret Key",'label' => false,'value'=>$User['User']['secret_key'],'type'=>'text'));?>
                		</label>
              		</div>
          			</div>

		           <div class="control-group">
		              <label class="control-label span5"><?php echo __("Stripe Publisher Key:")?></label>
		              <div class="controls">
		                <label class="radio inline span4">
		                   <?php echo $this->Form->input('public_key',array('placeholder'=>"Publisher Key",'label' => false,'value'=>$User['User']['public_key'],'type'=>'text'));?>
		                </label>
		              </div>
		          	</div>

								<div class="control-group">
              		<label class="control-label span5"><?php echo __("Authorized with stripe")?></label>
              		<div class="controls">
                		<label class="radio inline span4">
											<?php
												$authorize_request_body = array(
												  'response_type' => 'code',
												  'scope'         => 'read_write',
												  'client_id'     => 'ca_3eUUoTUSZsBg8Ly0TA7XjY3noItr8cgC',
													'redirect_uri'  => 'http://app.botangle.dev/users/billing'
												);
												$url = "https://connect.stripe.com/oauth/authorize" . '?' . http_build_query($authorize_request_body);
												echo "<a href='$url' id='connectstripe'>Connect with Stripe</a>";
												echo"<script type='text/javascript' src='js/jquery.min.js'></script>";
												echo "<script>$(document).ready(function() { $('#connectstripe').trigger('click'); });</script>";
											?>
                		</label>
              		</div>
          			</div>

								<div class="row-fluid Add-Payment-blocks">
									<div class="span5"></div>
          					<div class="span5">
          						<button type="submit" class="btn btn-primary">Update</button>
          					</div>
          				</div>

									<?php echo $this->Form->end();?>
								</div>
							</div>
						</div>
					</div>-->
				</div>
    		<!-- @end .row -->
			</div>
			<!-- @end .container -->
		</div>
		<!--Wrapper main-content Block End Here-->
