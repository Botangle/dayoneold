<!--Wrapper HomeServices Block Start Here-->

<?php
echo $this->element("breadcrame",array('breadcrumbs'=>
	array(__('Password Recovery Confirmation')=>__('Password Recovery Confirmation')))
	);
 
	?>
<!--Wrapper main-content Block Start Here-->
<div id="main-content">
  <div class="container">
    <div class="row-fluid">
      <div class="span12">
        <h2 class="page-title">Botangle Password Recovery</h2>
      </div>
    </div>
    <div class="row-fluid">
      <div class="span9 PageLeft-Block">
        <p class="FontStyle20">Check your email</p>
        <p><?php echo $this->Layout->sessionFlash();?></p><br>

        <div class="Signup">
         
         <form class="form-inline form-horizontal" role="form">
         <div class=" span12 text-center" >
		 <?php 
echo $this->Html->link(__("Return Home"), array('action'=> 'login'), array( 'class' => 'btn btn-primary'))
 
?>
            
       </div>
      
        </form>
 

          
        </div>
      </div>
      <div class="span3 PageRight-Block">
       <p class="FontStyle20">Not a member? Sign Up here</p>
        <p>Get a Free Account for 7 days. Sign Up here.</p><br>
<br>
<button type="submit" class="btn btn-primary">Sign Up</button>
      </div>
    </div>
    <!-- @end .row --> 
    
	<?php echo $this->element('Croogo.getintouch'); ?>
    
  </div>
  <!-- @end .container --> 
</div>
<!--Wrapper main-content Block End Here--> 