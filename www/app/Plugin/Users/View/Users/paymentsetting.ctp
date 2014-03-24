<!--Wrapper HomeServices Block Start Here-->
<?php echo $this->element("breadcrame",array('breadcrumbs'=>
	array('My Dashboard'=>'Payment Setiings'))
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
      <h2 class="page-title"><?php echo __("Payment Setting")?></h2>
      <div class="StaticPageRight-Block">
      <div class="PageLeft-Block">
      
      <div class="row-fluid Add-Payment-blocks">
        <div class="span12">
          <p class="FontStyle20 color1"><?php echo __("Setup Account:")?></p>
          </div>
          
          <?php 
		 $this->request->data = $this->Session->read("Auth.User");
		 
		 echo $this->Form->create('User',array('class'=>'form-horizontal'));
 		?>
            <div class="control-group">
              <label class="control-label span5"><?php echo __("Strip Account Email:")?></label>
              <div class="controls">
                <label class="radio inline span4">
                   <?php echo $this->Form->input('stripe_id',array('placeholder'=>"Account Email",'label' => false,'value'=>$User['User']['stripe_id'],'type'=>'text'));?>
                </label>
                
              </div>
              
          </div>
          
          <div class="control-group">
              <label class="control-label span5"><?php echo __("Strip Secret Key:")?></label>
              <div class="controls">
                <label class="radio inline span4">
                   <?php echo $this->Form->input('secret_key',array('placeholder'=>"Secret Key",'label' => false,'value'=>$User['User']['secret_key'],'type'=>'text'));?>
                </label>
                
              </div>
          </div>
          
          
           <div class="control-group">
              <label class="control-label span5"><?php echo __("Strip Publisher Key:")?></label>
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
				  'scope' => 'read_write',
				  'client_id' =>'ca_3eUUoTUSZsBg8Ly0TA7XjY3noItr8cgC'
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
        <div class="span5">
          
          </div>
          <div class="span5">
          <button type="submit" class="btn btn-primary">Update</button>
          </div>
          </div>
       <!-- <div class="row-fluid Add-Payment-blocks">
        <div class="span5">
          <p class="FontStyle20 color1">Add Your Payment Method:</p>
          </div>
          <div class="span5">
          <button type="submit" class="btn btn-primary">Add a Payment Account</button>
          </div>
          </div>-->
         <?php echo $this->Form->end();?>

       <!--<div class="row-fluid Add-Payment-blocks">
        <div class="span5">
          <p class="FontStyle20 color1">Total Balance:</p>
          </div>
          <div class="span5">
          <p class="FontStyle20">Total Blanace: $1000</p>
          </div>
          </div>
          
          <div class="row-fluid payment-blocks">
        <div class="span5">
          &nbsp;
          </div>
          <div class="span5">
          <button type="submit" class="btn btn-primary">Withdrawal</button>
          </div>
          </div>
        -->
        
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