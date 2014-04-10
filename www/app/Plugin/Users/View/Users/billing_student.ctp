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
                                <p class="FontStyle20 color1"><?php echo __("Setup Payments")?></p>
                                <p>Congratulations! You're getting close to having your first lesson!  We just need a few more
                                    details from you and then we'll notify your tutor about your desired time.</p>

                                <p>Please take a moment to fill out your payment information below.  NOTE: we safely and securely
                                    store this information with our credit card processing company.  It never touches our servers.</p>
                            </div>

                            <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

                            <script type="text/javascript">
                                // This identifies your website in the createToken call below
                                Stripe.setPublishableKey('<?php echo $publishable_key ?>');

                                jQuery(function($) {
                                    $('#BillingBillingForm').submit(function(event) {
                                        var $form = $(this);

                                        // Disable the submit button to prevent repeated clicks
                                        $form.find('button').prop('disabled', true);

                                        Stripe.card.createToken($form, stripeResponseHandler);

                                        // Prevent the form from submitting with the default action
                                        return false;
                                    });
                                });


                                var stripeResponseHandler = function(status, response) {
                                    var $form = $('#BillingBillingForm');

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

                            <div class="row-fluid Add-Payment-blocks">
                                <div class="span5">
                                    <?php  echo $this->Form->create('Billing',array('class'=>'form-horizontal'));
                                    echo $this->Form->input('pagetype',array('value'=>"student_setup",'type'=>'hidden'));
                                    ?>
                                    <div class="control-group">
                                        <label class="control-label" for="name"><?php echo __("Name on the Credit Card")?></label>
                                        <div class="controls">
                                            <input type="text" value="<?php echo h($User['User']['name'] . ' ' . $User['User']['lname'])?>" name="name" placeholder="Your name" data-stripe="name" />
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label" for="postalAddress"><?php echo __("Credit Card Number")?></label>
                                        <div class="controls">
                                            <?php // no name here so the card number never gets sent to our server ?>
                                            <input type="text" value="" placeholder="Credit Card #" data-stripe="number" />
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label" for="postalAddress"><?php echo __("Security Code")?></label>
                                        <div class="controls">
                                            <?php // no name here so the card number never gets sent to our server ?>
                                            <input type="text" value="456" placeholder="CVV" data-stripe="cvc" />
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label" for="postalAddress"><?php echo __("Expire Month")?></label>
                                        <div class="controls">
                                            <?php // no name here so the card number never gets sent to our server ?>
                                            <select data-stripe="exp-month">
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
                                            <?php // no name here so the card number never gets sent to our server ?>
                                            <select data-stripe="exp-year">
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
                                            <button type="submit" class="btn btn-primary clsmarginleft">Add My Card to My Account</button>
                                        </div>
                                    </div>
                                    <?php echo $this->Form->end();?>
                                </div>
                            </div>
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
