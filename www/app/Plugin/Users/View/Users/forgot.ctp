
<!--Wrapper HomeServices Block Start Here-->
<div id="HomeServices">
  <div class="container">
    <div class=" row-fluid">
      <div class="span12 Breadcrame"> Home // Forgot Password</div>
    </div>
  </div>
</div>

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
      <p class="FontStyle20">Did you forget your username or password?</p>
        <p class="">No problem, just fill in your email address below and we'll send you an email to reset your password!</p><br>

        <div class="Signup">
         <?php echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' => 'forgot'),'class'=>'form-inline form-horizontal','role'=>'form'));?>
      
         <div class=" span8" >
		 <?php
			echo $this->Form->input('email', array('class'=>'form-control textbox1','placeholder'=>'Your email: e.g. email@email.com','label'=>false ));
			?>
         
         </div>
         <div class=" span3" >
           <button type="submit" class="btn btn-primary">Reset my password</button>
       </div>
      <?php echo $this->Form->end();?>
        

<br>
<br>
<br><br>


          
        </div>
      </div>
      <div class="span3 PageRight-Block">
       <p class="FontStyle20">Not a member? Sign Up here</p>
        <p>Get a Free Account. Sign Up here.</p><br>
<br>
<?php 
echo $this->Html->link(__("Sign Up"), array('action'=> 'registration','tutor'), array( 'class' => 'btn btn-primary'))
/*
<button type="submit" class="btn btn-primary">Sign Up</button> */
?>
      </div>
    </div>
    <!-- @end .row --> 
    
	<?php echo $this->element('Croogo.getintouch'); ?>
    
  </div>
  <!-- @end .container --> 
</div>
<!--Wrapper main-content Block End Here--> 