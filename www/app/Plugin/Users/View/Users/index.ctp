<?php 
 
 echo $this->Layout->js();
		echo $this->Html->script(array( '/croogo/js/autocomplete/jquery-1.9.1',
			'/croogo/js/autocomplete/jquery.ui.core','/croogo/js/autocomplete/jquery.ui.widget','/croogo/js/autocomplete/jquery.ui.position','/croogo/js/autocomplete/jquery.ui.menu','/croogo/js/autocomplete/jquery.ui.autocomplete',
			));
 
 echo $this->Html->css(array(
			'/croogo/css/autocomplete/themes/base/jquery.ui.all', '/croogo/css/autocomplete/demos', 
		));
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
<script>
	jQuery(function() {
		 
		function split( val ) {
			return val.split( /,\s*/ );
		}
		function extractLast( term ) {
			return split( term ).pop();
		}

		jQuery( "#UserSubject" )
			// don't navigate away from the field on tab when selecting an item
			.bind( "keydown", function( event ) {
				if ( event.keyCode === jQuery.ui.keyCode.TAB &&
						jQuery( this ).data( "ui-autocomplete" ).menu.active ) {
					event.preventDefault();
				}
			})
			.autocomplete({
				source: function( request, response ) {
					$.getJSON( "/botangle/subject/search", {
						term: extractLast( request.term )
					}, response );
				},
				search: function() {
					// custom minLength
					var term = extractLast( this.value );
					if ( term.length < 2 ) {
						return false;
					}
				},
				focus: function() {
					// prevent value inserted on focus
					return false;
				},
				select: function( event, ui ) {
					var terms = split( this.value );
					// remove the current input
					terms.pop();
					// add the selected item
					terms.push( ui.item.value );
					// add placeholder to get the comma-and-space at the end
					terms.push( "" );
					this.value = terms.join( ", " );
					return false;
				}
			});
	});
	</script>
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
      <h2 class="page-title"><?php echo __("My Account")?></h2>
      <div class="StaticPageRight-Block">
      <div class="PageLeft-Block">
        <p class="FontStyle20 color1"><?php echo __("Update Info")?></p>
         <?php 
		 $this->request->data = $this->Session->read("Auth.User");
		 
		 echo $this->Form->create('User',array('class'=>'form-horizontal','type' => 'file'));
			echo $this->Form->input('id',array('value'=>$this->request->data['id']));
			 
			 
		 ?>
		 
            <div class="control-group">
           
              <div class="control-group">
                <label class="control-label" for="postalAddress"><?php echo __("Subject:")?></label>
                <div class="controls">
                  <?php echo $this->Form->textarea('subject',array('class'=>'textarea','placeholder'=>"Teaching Experience",'value'=>$this->request->data['subject'],'rows'=>3));?>
                  <br>
                  <span class="FontStyle11"><em><?php echo __("Separate Subjects with commas")?></em></span> </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="firstName"><?php echo __("Username:")?></label>
                <div class="controls">
                 <?php echo $this->Form->input('username',array('class'=>'textbox','placeholder'=>"First Name",'label' => false,'value'=>$this->request->data['username'],'disabled'=>'disabled'));?>
                </div>
              </div>
			  <div class="control-group">
                <label class="control-label" for="firstName"><?php echo __("First Name:")?></label>
                <div class="controls">
                 <?php echo $this->Form->input('name',array('class'=>'textbox','placeholder'=>"First Name",'label' => false,'value'=>$this->request->data['name']));?>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="lastName">Last Name:</label>
                <div class="controls">
				  <?php echo $this->Form->input('lname',array('class'=>'textbox','placeholder'=>"Last Name",'label' => false,'value'=>$this->request->data['name']));?>
                   
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="inputEmail">Email Address:</label>
                <div class="controls">
                 <?php echo $this->Form->input('email',array('class'=>'textbox','placeholder'=>"test@test.com",'label' => false,'value'=>$this->request->data['email'],'disabled'=>'disabled'));?>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="postalAddress">Qualification:</label>
                <div class="controls">
				  <?php echo $this->Form->textarea('qualification',array('class'=>'textarea','placeholder'=>"type your Qualification",'value'=>$this->request->data['qualification'],'rows'=>3));?>
                 
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="postalAddress">Teaching Experience:</label>
                <div class="controls">
                  <?php echo $this->Form->textarea('teaching_experience',array('class'=>'textarea','placeholder'=>"Teaching Experience",'value'=>$this->request->data['teaching_experience'],'rows'=>3));?>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="postalAddress">Extracurricular Interests:</label>
                <div class="controls">
                   <?php echo $this->Form->textarea('extracurricular_interests',array('class'=>'textarea','placeholder'=>"Extracurricular Interests",'value'=>$this->request->data['extracurricular_interests'],'rows'=>3));?>
                </div>
              </div>
          <div class="control-group">
                <label class="control-label" for="inputEmail"><?php echo __("Other experience:")?></label>
                <div class="controls">
                 <?php echo $this->Form->input('other_experience',array('class'=>'textbox','placeholder'=>"English with a Concentration in Theater",'label' => false,'value'=>$this->request->data['other_experience']));?>
                
                 
                </div>
              </div>
			  <div class="control-group">
                <label class="control-label" for="inputEmail"><?php echo __("University:")?></label>
                <div class="controls">
                 <?php echo $this->Form->input('university',array('class'=>'textbox','placeholder'=>"Barnard/University, Class of 2013",'value'=>$this->request->data['university'],'label' => false));?>
                
                 
                </div>
              </div>
			   <div class="control-group">
                <label class="control-label" for="postalAddress"><?php echo __("Expertise in (Subject)")?>:</label>
                <div class="controls">
                 <?php echo $this->Form->textarea('expertise',array('class'=>'textarea','value'=>$this->request->data['expertise'],'placeholder'=>"Top Subjects"));?>
                 
                </div>
              </div>
<?php /*			   <div class="row-fluid"><label class="control-label for="Username2">Select Your Pic</label>
			  <div class="form-group span7 controls">

				<?php
				 echo $this->Form->input('profilepic',array( 'type' => 'file','label'=>false));
				 ?>
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