<?php
/**
 * _create.php.ctp
 *
 * @author: David
 * @adapted for Laravel: Martyn
 * Date: 7/18/14
 * Time: 11:29 AM
 */
?>
{{ Html::script('https://js.braintreegateway.com/v2/braintree.js') }}

<script>
braintree.setup({{ $token }}, "dropin", {
  container: "checkout"
});
</script>

{{ Form::open(array('class'=>'form-horizontal', 'action' => 'TransactionController@postSell')) }}

{{ Form::hidden('type', 'sell') }}

<p>Sell your Botangle credits for cash. Transfer money to your Paypal account.</p>

<div class="control-group">
    <label class="control-label span2" for="type">Paypal Email</label>
    <div class="controls span5">
        {{ Form::text('paypal_email_address', $email) }}
    </div>
</div>

<div class="control-group">
    <label class="control-label span2" for="type">Desired Amount</label>
    <div class="controls span5">
        {{ Form::text('amount', 10) }}
    </div>
</div>

<div class="row-fluid Add-Payment-blocks">
    <div class="span2"></div>
    <div class="span5">
        <button type="submit" class="paypal-button" style="display: block; width: 115px; height: 44px; overflow: hidden; background: url('/images/pay-with-paypal-1.0.0.png'); background-size: 115px 44px; border: 0; text-indent: 200%;
white-space: nowrap;">Sell Credits</button>
        <p class="muted">Send money to your PayPal account.</p>
        <p class="text-info"><small>NOTE: a maximum of 100 credits can be sold each 24 hrs.</small></p>
        <p class="muted"><small>(1 Credit = 1 USD)</small></p>
    </div>
</div>
{{ Form::close() }}
