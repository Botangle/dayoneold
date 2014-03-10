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


                            <div class="row-fluid Add-Payment-blocks">
                                <div class="span5">
                                    <?php  echo $this->Form->create('Billing',array('class'=>'form-horizontal'));?>
                                    <div class="control-group">
                                        <label class="control-label"
                                               for="postalAddress"><?php echo __("Select Tutor:")?></label>

                                        <div class="controls">
                                            <?php
		   echo $this->
                                            Form->input('studentpayemtn',array('type'=>'hidden','value'=>'studentpayemtn','name'=>'data[Billing][studentpayemtn]'));
                                            echo $this->Form->input('tutor_id', array('class' => 'chzn-select',
                                            'options' => $userInfo, 'label' => false, 'div' => array('class' =>
                                            'formRight noSearch', 'name'=>'fname', 'empty' => '(choose one)'))); ?>

                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label"
                                               for="postalAddress"><?php echo __("Payment Amount")?></label>

                                        <div class="controls">
                                            <input type="text" value="100" name="payamount"/>
                                            <input type="hidden" value="" name="lname"/>
                                        </div>
                                    </div>


                                    <div class="control-group">
                                        <label class="control-label"
                                               for="postalAddress"><?php echo __("Card Type")?></label>

                                        <div class="controls">
                                            <input type="text" value="Visa" name="card"/>

                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label"
                                               for="postalAddress"><?php echo __("Card Number")?></label>

                                        <div class="controls">
                                            <input type="text" value="4012888888881881" name="acc_number"/>

                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label"
                                               for="postalAddress"><?php echo __("Security Code")?></label>

                                        <div class="controls">
                                            <input type="text" value="456" name="card_security_code"/>

                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label"
                                               for="postalAddress"><?php echo __("Expire Month")?></label>

                                        <div class="controls">
                                            <select name="expiration_month">
                                                <?php for($i = 1 ; $i< 13;$i++){
					echo '<option value = "'.$i.'">'.$i.'</option>';
                                                }
                                                ?></select>


                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label"
                                               for="postalAddress"><?php echo __("Expire Year")?></label>

                                        <div class="controls">
                                            <select name="expiration_year">
                                                <?php
				$curyr =  date('Y'); 
				for($i = $curyr; $i< ($curyr+25);$i++){
					echo '<option value = "'.$i.'">'.$i.'</option>';
                                                }
                                                ?></select>


                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label"
                                               for="postalAddress"><?php echo __("Billing Address")?></label>

                                        <div class="controls">
                                            <input type="text" value="63/68" name="bill_addressline1"/>

                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label"
                                               for="postalAddress"><?php echo __("Billing Address2")?></label>

                                        <div class="controls">
                                            <input type="text" value="63/68" name="bill_addressline2"/>

                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="postalAddress"><?php echo __("City")?></label>

                                        <div class="controls">
                                            <input type="text" value="jaipur" name="bill_city"/>

                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label"
                                               for="postalAddress"><?php echo __("State")?></label>

                                        <div class="controls">
                                            <input type="text" value="rajasthan" name="bill_state"/>

                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="postalAddress"><?php echo __("Zip")?></label>

                                        <div class="controls">
                                            <input type="text" value="302020" name="bill_zip"/>

                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label"
                                               for="postalAddress"><?php echo __("Country")?></label>

                                        <div class="controls">
                                            <input type="text" value="india" name="bill_country"/>

                                        </div>
                                    </div>

                                    <div class="row-fluid payment-blocks">
                                        <div class="span5">
                                            &nbsp;
                                        </div>


                                        <div class="span5">
                                            <button type="submit" class="btn btn-primary">Deposit Payment</button>
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