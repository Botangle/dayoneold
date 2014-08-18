<?php
/**
 * _create.php.ctp
 *
 * @author: David Baker <dbaker@acorncomputersolutions.com
 * Date: 7/18/14
 * Time: 11:29 AM
 */
$this->Html->script('https://js.braintreegateway.com/v2/braintree.js', array('inline' => false)); ?>
<?php echo $this->Form->create('Transaction',array(
        'id' => 'TransactionCreateForm',
        'class'=>'form-horizontal',
        'url' => array(
            'plugin'        => 'users',
            'controller'    => 'transactions',
            'action'        => 'create',
        ),
    ));
echo $this->Form->input(
    'type', array(
        'value' => 'buy',
        'type' => 'hidden',
    )
);
?>
<input type="hidden" data-braintree-name="number" value="">
<input type="hidden" data-braintree-name="expiration_date" value="">

<?php if($refill_needed): ?>
    <p>Hmm, we'll need you to purchase some credits before we can notify that expert about your lesson proposal.</p>
<?php else: ?>
    <p>Purchase Botangle credits so you can signup for lessons!</p>
<?php endif; ?>
<p>Purchase securely through PayPal.</p>

<div class="control-group">
    <label class="control-label span2" for="type">Desired Amount</label>
    <div class="controls span5">
        <?php echo $this->Form->input(
            'amount', array(
                'label' => false,
                'value' => '10',
            )
        ); ?>
    </div>
</div>

<div class="control-group">
    <label class="span2"></label>
    <div class="span5">
        <div id="paypal-button"></div>
    </div>
</div>

<div class="row-fluid Add-Payment-blocks">
    <div class="span2"></div>
    <div class="span3">
        <button type="submit" class="btn btn-primary main-button" style="display: none;">Buy Credits</button>
        <p class="muted">Buy credits using PayPal</p>
        <p class="muted"><small>(1 Credit = 1 USD)</small></p>
    </div>
</div>
<?php
// it's handy to be able to short-circuit things if we're in dev mode
/*if(Configure::read('debug') == '2'): ?>
<button type="submit" class="btn btn-primary">Buy Test</button>
<?php endif; */ ?>
<?php echo $this->Form->end();?>

<script>
    function validationFunction() {
        if($("#TransactionCreateForm [name='payment_method_nonce']").val() != '') {
            return true;
        }
        return false;
    }

    // watch for our payment nonce to come back
    // when it does, we want to display our Buy / Sell button
    var payPalWatcherId;
    function track_change() {
        if($("#TransactionCreateForm [name='payment_method_nonce']").val() != '') {
            $('#TransactionCreateForm button.main-button').fadeIn();
            clearInterval(payPalWatcherId);
        }
    }

    $(function() {

        // prevent our form from being submitted by keyboard
        // PayPal needs to be clicked, so we're going to enforce that here for now
        // because we don't allow CC #s at the moment
        $(window).keydown(function(event){
            if( (event.keyCode == 13) && (validationFunction() == false) ) {
                event.preventDefault();
                return false;
            }
        });

        // when our paypal button is clicked, we want to start watching for changes
        // so we can display our submit button
        $('#paypal-button').click(function(e) {
            payPalWatcherId = setInterval(function() { track_change()}, 100);
        });
    });

    braintree.setup('<?php echo $token ?>', "paypal", {
        container: 'paypal-button',
        singleUse: true
    });
</script>
