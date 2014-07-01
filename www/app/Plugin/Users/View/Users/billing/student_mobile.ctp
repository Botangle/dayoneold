<!--Wrapper main-content Block Start Here-->
<div id="main-content">
    <div class="container">
        <div class="row-fluid">
            <div class="col-span-md-12">
                <div class="row-fluid Add-Payment-blocks">

                    <?php if (!$needs_payments_setup): ?>
                        <div class="span12">
                            <p class="FontStyle20 color1"><?php echo __("Payments Setup") ?></p>
                            <p>Thanks, you're all setup in our system.  At this point, experts will see your lesson requests
                                and you can start arranging the details on when you meet.  Please let us know if
                                you have any questions or concerns!</p>
                        </div>

                    <?php else: ?>
                        <div class="span12">
                            <p>Congratulations! You're getting close to having your first lesson!  We just need a few more
                                details from you and then we'll notify your expert about your desired time.</p>

                            <p>Please take a moment to fill out your payment information below.  NOTE: we safely and securely
                                store this information with our credit card processing company (Stripe).  It never touches our servers.</p>
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
                                    handleErrors($form, response.error.message)
                                } else {
                                    // token contains id, last4, and card type
                                    var token = response.id;
                                    // Insert the token into the form so it gets submitted to the server
                                    $form.append($('<input type="hidden" name="stripeToken" />').val(token));

                                    // and submit using Ajax so that everything iOS-wise stays loaded
                                    $.post(
                                        $form.attr('action'),
                                        $form.serialize(),
                                        function(results) {
                                            if(results.status == 'error') {
                                                handleErrors($form, response.message)
                                            } else {
                                                WebViewJavascriptBridge.callHandler('getPaymentInfo', "success");
                                            }
                                        }
                                    );
                                }
                            };

                            function handleErrors(form, message)
                            {
                                // Show the errors on the form
                                form.find('.payment-errors').text(message);
                                form.find('button').prop('disabled', false);

                                // let our iOS system know we're staying on the page
                                WebViewJavascriptBridge.callHandler('getPaymentInfo', "failure");
                            }
                        </script>

                        <div class="row-fluid Add-Payment-blocks">
                            <div class="span5">
                                <?php
                                echo $this->Form->create('Billing', array('class' => '', 'role' => 'form'));
                                echo $this->Form->input('pagetype', array('value' => "student_setup", 'type' => 'hidden'));
                                ?>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="name"><?php echo __("Name on the Credit Card") ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" value="<?php echo h($User['User']['name'] . ' ' . $User['User']['lname']) ?>" name="name" placeholder="Your name" data-stripe="name" class="form-control" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="postalAddress"><?php echo __("Credit Card Number") ?></label>
                                    <div class="col-sm-9">
                                        <?php // no name here so the card number never gets sent to our server  ?>
                                        <input type="text" value="" placeholder="Credit Card #" data-stripe="number" class="form-control" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-xs-4">
                                        <?php // no name here so the card number never gets sent to our server  ?>
                                        <input type="text" value="" placeholder="CVV" data-stripe="cvc" class="form-control" />
                                    </div>

                                    <div class="col-xs-4">
                                        <?php // no name here so the card number never gets sent to our server ?>
                                        <select data-stripe="exp-month" class="form-control">
                                            <option value="" selected disabled>Month</option>
                                            <?php
                                            for ($i = 1; $i < 13; $i++) {
                                                echo '<option value = "' . $i . '">' . $i . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>


                                    <div class="col-xs-4">
                                        <?php // no name here so the card number never gets sent to our server ?>
                                        <select data-stripe="exp-year" class="form-control">
                                            <option value="" selected disabled>Year</option>
                                            <?php
                                            $curyr = date('Y');
                                            for ($i = $curyr; $i < ($curyr + 15); $i++) {
                                                echo '<option value = "' . $i . '">' . $i . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-12" style="padding: 1em 0">
                                    <div class="col-xs-3 btn-cancel">
                                        <button type="cancel" class="btn btn-default">Cancel</button>
                                    </div>
                                    <div class="col-xs-9 btn-add">
                                        <button type="submit" class="btn btn-warning btn-block">Add My Card to My Account</button>
                                    </div>
                                </div>

                                <span style="color: red; font-weight: bold" class="payment-errors"></span>

                                <?php echo $this->Form->end(); ?>
                            </div>
                        </div>
                    <?php endif; // if we need payments setup  ?>
                </div>

            </div>
        </div>
        <!-- @end .row -->
    </div>
    <!-- @end .container -->
</div>
<!--Wrapper main-content Block End Here-->

<script>
    function connectWebViewJavascriptBridge(callback) {
        if (window.WebViewJavascriptBridge) {
            callback(WebViewJavascriptBridge)
        } else {
            document.addEventListener('WebViewJavascriptBridgeReady', function() {
                callback(WebViewJavascriptBridge)
            }, false)
        }
    }

    connectWebViewJavascriptBridge(function(bridge) {

        /* Init your app here */
    })

    $('.btn-cancel button').click(function(e) {
        var response = confirm("Cancelling means your lesson will not be scheduled.");
        if(response == true) {
            WebViewJavascriptBridge.callHandler('cancel');
        }
        return false;
    });

</script>