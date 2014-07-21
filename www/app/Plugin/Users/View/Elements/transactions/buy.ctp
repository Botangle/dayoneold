<?php
/**
 * _create.php.ctp
 *
 * @author: David Baker <dbaker@acorncomputersolutions.com
 * Date: 7/18/14
 * Time: 11:29 AM
 */
echo $this->Form->create('Transaction',array(
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
<p>Purchase Botangle credits so you can signup for lessons!</p>

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

<div class="row-fluid Add-Payment-blocks">
    <div class="span2"></div>
    <div class="span5">
        <button type="submit" class="btn btn-primary">Buy Credits</button>
    </div>
</div>

<?php echo $this->Form->end();?>
