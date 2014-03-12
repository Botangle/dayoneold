 
<?php
echo $this->element("breadcrame",array('breadcrumbs'=>
	array('My Dashboard'=>'My Dashboard'))
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
                 <?php echo $this->Form->input('name',array('class'=>'textbox','placeholder'=>"Last Name",'label' => false,'value'=>$this->request->data['lname']));?>
                   
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="inputEmail">Email Address:</label>
                <div class="controls">
                 <?php echo $this->Form->input('email',array('class'=>'textbox','placeholder'=>"test@test.com",'label' => false,'value'=>$this->request->data['email']));?>
                </div>
              </div>
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
              
          
            </div>
           
            <div class="control-group form-actions">
             <?php 
			echo $this->Form->button(__('Update Info'), array('type' => 'submit','class'=>'btn btn-primary')); 
			echo $this->Form->button(__('Cancel'), array('type' => 'reset','class'=>'btn btn-reset'));?> 
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