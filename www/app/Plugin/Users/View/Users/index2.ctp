 
<?php
echo $this->element("breadcrame",array('breadcrumbs'=>
	array('My Dashboard'=>'My Dashboard'))
	);
 
	?>

<style>
.fileinput-exists .fileinput-new, .fileinput-new .fileinput-exists {
    display: none;
}
.btn-file > input {
    cursor: pointer;
    direction: ltr;
    font-size: 23px;
    margin: 0;
    opacity: 0;
    position: absolute;
    right: 0;
    top: 0;
    transform: translate(-300px, 0px) scale(4);
}
input[type="file"] {
    display: block;
}
</style>
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
      <h2 class="page-title">My Account</h2>
      <div class="StaticPageRight-Block">
      <div class="PageLeft-Block">
        <p class="FontStyle20 color1">Update Info</p>
        <?php 
		 $this->request->data = $this->Session->read("Auth.User");
		 
		 echo $this->Form->create('User',array('class'=>'form-horizontal'));
			echo $this->Form->input('id',array('value'=>$this->request->data['id']));
			 
			 
		 ?>
            <div class="control-group">
           
             
              <div class="control-group">
                <label class="control-label" for="firstName">Username:</label>
                <div class="controls">
                  <?php echo $this->Form->input('username',array('class'=>'textbox','placeholder'=>"First Name",'label' => false,'value'=>$this->request->data['username'],'disabled'=>'disabled'));?>
                </div>
              </div>
			  <div class="control-group">
                <label class="control-label" for="firstName">First Name:</label>
                <div class="controls">
                  <?php echo $this->Form->input('name',array('class'=>'textbox','placeholder'=>"First Name",'label' => false,'value'=>$this->request->data['name']));?>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="lastName">Last Name:</label>
                <div class="controls">
                 <?php echo $this->Form->input('lname',array('class'=>'textbox','placeholder'=>"Last Name",'label' => false,'value'=>$this->request->data['lname']));?>
                   
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="inputEmail">Email Address:</label>
                <div class="controls">
                 <?php echo $this->Form->input('email',array('class'=>'textbox','placeholder'=>"test@test.com",'label' => false,'value'=>$this->request->data['email'],'disabled'=>'disabled'));?>
                </div>
              </div>
<?php /*			   <div class="row-fluid"><label class="control-label for="Username2">Select Your Pic</label>
			  <div class="form-group span7 controls">
				
				<?php
				 echo $this->Form->input('profilepic',array( 'type' => 'file','label'=>false));
				 ?>
			  </div>
*/ ?>
			 
			  </div>
			  <?php /*
              <div class="control-group">
                <label class="control-label" for="postalAddress">About Me:</label>
                <div class="controls">
				<?php echo $this->Form->textarea('aboutme',array('class'=>'textarea','placeholder'=>"Type About yourself",'value'=>$this->request->data['aboutme'],'rows'=>3));?>
                  
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="postalAddress">My Interests:</label>
                <div class="controls">
                 
				   <?php echo $this->Form->textarea('extracurricular_interests',array('class'=>'textarea','placeholder'=>"Type your Interests",'value'=>$this->request->data['extracurricular_interests'],'rows'=>3));?>
                </div>
              </div>
              */ ?>
          
            </div>
           
            <div class="control-group form-actions">
             <?php 
			echo $this->Form->button(__('Update Info'), array('type' => 'submit','class'=>'btn btn-primary')); 
			echo $this->Form->button(__('Cancel'), array('type' => 'reset','class'=>'btn btn-reset'));?> 
            </div>
          <?php echo $this->Form->end();?>
       </div>
       
   <div class="PageLeft-Block">
        <p class="FontStyle20 color1"><?php echo __("Change Password")?></p>
           <?php echo $this->Form->create('User',array('class'=>'form-inline form-horizontal',"role"=>"form"));
		     $this->request->data = $this->Session->read("Auth.User"); 
			echo $this->Form->input('id',array('value'=>$this->request->data['id']));
			echo $this->Form->hidden('type',array('value'=>"changepasword",'name'=>'data[User][changepassword]'));
			?>
    
        <div class="row-fluid">
        <div class="form-group span5">
            <label class="sr-only" for="Username2"><?php echo __("Old Password")?></label>
           
			 <?php echo $this->Form->input('password',array('class'=>'form-control textbox1','placeholder'=>"Old Password",'label' => false,'id'=>'old_password','name'=>'data[User][oldpassword]'));?>
          </div>
          
          </div>
          <br>
          <div class="row-fluid">
          <div class="form-group span5">
            <label class="sr-only" for="Username2">New Password</label>
           
			 <?php echo $this->Form->input('password',array('class'=>'form-control textbox1','placeholder'=>"New Password",'label' => false));?>
          </div>

          <div class="form-group span5">
            <label class="sr-only" for="Password2">Confirm Password</label>
			  <?php echo $this->Form->input('verify_password',array('type'=>'password','class'=>'form-control textbox1','placeholder'=>"Confirm Password",'label' => false));?>
            
          </div>
          </div><br>

          <div class="row-fluid">
         <div class="span12">
			<?php
			echo $this->Form->button(__('Update Password'), array('type' => 'submit','class'=>'btn btn-primary')); 
			?>
            
       </div>
      </div>
       <?php echo $this->Form->end();?>
        
        
        
       </div>
       
      </div>
      </div>
    </div>
    <!-- @end .row --> 
    
 
    
    
    
  </div>
  <!-- @end .container --> 
</div>
<!--Wrapper main-content Block End Here--> 