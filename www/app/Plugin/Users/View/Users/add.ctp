<?php
echo $this->element("breadcrame", array('breadcrumbs' =>
	array('Registration' => 'Sign Up'))
);
$default = "2";
if ($this->Session->check("type")) {
	if ($this->Session->read("type") == 'tutor') {
		$default = "2";
	} else if ($this->Session->read("type") == 'student') {
		$default = "4";
	}
}
?>

<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script>
	jQuery(function() {
		function split(val) {
			return val.split(/,\s*/);
		}
		function extractLast(term) {
			return split(term).pop();
		}
		jQuery("#UserSubject").autocomplete({
			minLength: 0,
			source: function(request, response) {
// delegate back to autocomplete, but extract the last term
				response($.ui.autocomplete.filter(
						datasubject, extractLast(request.term)));
			},
			focus: function() {
// prevent value inserted on focus
				return false;
			},
			select: function(event, ui) {
				var terms = split(this.value);
				// remove the current input
				terms.pop();
				// add the selected item
				terms.push(ui.item.value);
				// add placeholder to get the comma-and-space at the end
				terms.push("");
				this.value = terms.join(", ");
				return false;
			}
		});
	});

</script>
<style>
	.security {background-image:url(images/password-security.jpg)}
	.weak{height:10px; width:30px}
	.weak1{height:10px; width:60px}
	.Good{height:10px; width:120px}
	.Strong{height:10px; width:260px}

</style>
<div id="main-content">
	<div class="container">
		<div class="row-fluid">
			<div class="span12">
				<h2 class="page-title"><?php echo __("Botangle Sign Up") ?></h2>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span9 PageLeft-Block">
				<p class="FontStyle20"><?php echo __("Create your Botangle Account") ?></p>
				<p><?php echo __("It only takes a few minutes to register with Botangle and you get amazing features! Fill out the information below!") ?> </p>

				<?php if ($default == 2) { ?>
					<div class="Signup"> 
						<?php echo $this->Form->create('User', array('class' => 'form-horizontal', 'type' => 'file')); ?>
						<div class="control-group">
							<label class="control-label">I am a...:</label>
							<div class="controls">

								<?php
								$default = "2";
								if ($this->Session->check("type")) {
									if ($this->Session->read("type") == 'tutor') {
										$default = "2";
									} else if ($this->Session->read("type") == 'student') {
										$default = "4";
									}
								}


								$options = array('2' => 'Expert', '4' => 'Student');
								$attributes = array('legend' => false, 'checked' => $default, 'value' => $default,
									'onclick' => 'update(this.value)',
									'label' => array(
										'class' => 'radio inline', 'style' => 'padding-left:1px;padding-right:10px'));
								echo $this->Form->radio('role_id', $options, $attributes);
								?>
							</div>

							<div class="control-group">
								<label class="control-label" for="postalAddress">Subject:</label>
								<div class="controls">
									<?php echo $this->Form->textarea('subject', array('class' => 'textarea', 'placeholder' => 'Type Your Subjects', 'rows' => 3)); ?>

									<br>
									<span class="FontStyle11"><em><?php echo __("Separate Subjects with commas") ?></em></span> </div>
							</div>
							
							<div class="row-fluid">
								<div class="control-group">
									<label class="control-label" for="UserProfilepic">Upload Your Pic</label>
									<div class="form-group span7 controls">
										<?php
										echo $this->Form->file('profilepic', array('label' => false));
										?>
									</div>
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="firstName"><?php echo __("Username:") ?></label>
								<div class="controls">
									<?php echo $this->Form->input('username', array('class' => 'textbox', 'placeholder' => "Username", 'label' => false)); ?>
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="firstName"><?php echo __("First Name:") ?></label>
								<div class="controls">
									<?php echo $this->Form->input('name', array('class' => 'textbox', 'placeholder' => "First Name", 'label' => false)); ?>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="lastName"><?php echo __("Last Name:") ?></label>
								<div class="controls">
									<?php echo $this->Form->input('lname', array('class' => 'textbox', 'placeholder' => "Last Name", 'label' => false)); ?>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="inputEmail"><?php echo __("Email Address:") ?></label>
								<div class="controls">
									<?php echo $this->Form->input('email', array('class' => 'textbox', 'placeholder' => "email@email.com", 'label' => false)); ?>


								</div>
							</div>
							<div id="signupTuter">
								<div class="control-group">
									<label class="control-label" for="postalAddress"><?php echo __("Qualification:") ?></label>
									<div class="controls">
										<?php echo $this->Form->textarea('qualification', array('class' => 'textarea', 'placeholder' => "Type Your Qualification")); ?>

									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="postalAddress"><?php echo __("Teaching Experience:") ?></label>
									<div class="controls">
										<?php echo $this->Form->textarea('teaching_experience', array('class' => 'textarea', 'placeholder' => "Teaching Experience")); ?>

									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="postalAddress"><?php echo __("Extracurricular Interests:") ?></label>
									<div class="controls">
										<?php echo $this->Form->textarea('extracurricular_interests', array('class' => 'textarea', 'placeholder' => "Extracurricular Interests")); ?>

									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="inputEmail"><?php echo __("Other experience:") ?></label>
									<div class="controls">
										<?php echo $this->Form->input('other_experience', array('class' => 'textbox', 'placeholder' => "English with a Concentration in Theater", 'label' => false)); ?>


									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="inputEmail"><?php echo __("University:") ?></label>
									<div class="controls">
										<?php echo $this->Form->input('university', array('class' => 'textarea', 'rows' => 2,'placeholder' => "Barnard/University, Class of 2013", 'label' => false)); ?>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="postalAddress"><?php echo __("Expertise in (Subject)") ?>:</label>
									<div class="controls">
										<?php echo $this->Form->textarea('expertise', array('class' => 'textarea', 'placeholder' => "Top Subjects")); ?>

									</div>
								</div>
							</div>

							<p><strong><?php echo __("Account Information:") ?></strong></p>
							<div class="control-group">
								<label class="control-label" for="inputPassword"><?php echo __("Password:") ?></label>
								<div class="controls">
									<?php echo $this->Form->input('password', array('class' => 'textbox', 'placeholder' => "Password", 'label' => false)); ?></div>
								<div class="controls">
									<div class="password-security" id="result" style="width:269px; height:10px;">
										<div class="security"></div>
										<?php echo __("Level of Security") ?></div>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="confirmPassword"><?php echo __("Confirm Password:") ?></label>
								<div class="controls">
									<?php echo $this->Form->input('verify_password', array('type' => 'password', 'class' => 'textbox', 'placeholder' => "Confirm Password", 'label' => false)); ?>
								</div>
							</div>

						</div>
						<div class="control-group">
							<div class="controls">
								<label class="checkbox">

									<?php echo $this->Form->checkbox('terms', array('hiddenField' => false)); ?>
									<label><?php echo __("&nbsp;I agree with Botangle's <a href='/demos/botangle/privacy'>Terms of Use and Privacy Policy.</a>") ?>.</label></label>
							</div>
						</div>
						<div class="control-group form-actions">
							<?php
							echo $this->Form->button('Create My Account', array('type' => 'submit', 'class' => 'btn btn-primary'));
							echo $this->Form->button('Reset', array('type' => 'reset', 'class' => 'btn btn-reset'));
							?>

						</div>
						<?php echo $this->Form->end(); ?>
					</div>
				<?php } else if ($default == 4) { ?>
					<div class="Signup">

						<?php echo $this->Form->create('User', array('class' => 'form-horizontal')); ?>
						<div class="control-group">
							<label class="control-label"><?php echo __("I am a...:") ?></label>
							<div class="controls">
								<?php
								$default = "2";
								if ($this->Session->check("type")) {
									if ($this->Session->read("type") == 'tutor') {
										$default = "2";
									} else if ($this->Session->read("type") == 'student') {
										$default = "4";
									}
								}


								$options = array('2' => 'Expert', '4' => ' Student');
								$attributes = array('legend' => false, 'checked' => $default, 'value' => $default,
									'onclick' => 'update(this.value)',
									'label' => array(
										'class' => 'radio inline', 'style' => 'padding-left:1px;padding-right:10px'));
								echo $this->Form->radio('role_id', $options, $attributes);
								?>
							</div>
							<div class="control-group">
								<label class="control-label" for="inputEmail"><?php echo __("Email Address:") ?></label>
								<div class="controls">
									<?php echo $this->Form->input('email', array('class' => 'textbox', 'placeholder' => "email@email.com", 'label' => false)); ?>
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="firstName"><?php echo __("Username:") ?></label>
								<div class="controls">
									<?php echo $this->Form->input('username', array('class' => 'textbox', 'placeholder' => "Username", 'label' => false)); ?>
								</div>

								<div class="control-group">
									<label class="control-label" for="firstName"><?php echo __("First Name:") ?></label>
									<div class="controls">
										<?php echo $this->Form->input('name', array('class' => 'textbox', 'placeholder' => "First Name", 'label' => false)); ?>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="lastName"><?php echo __("Last Name:") ?></label>
									<div class="controls">
										<?php echo $this->Form->input('lname', array('class' => 'textbox', 'placeholder' => "Last Name", 'label' => false)); ?>
									</div>
								</div>


								<p><strong><?php echo __("Account Information:") ?></strong></p>
								<div class="control-group">
									<label class="control-label" for="inputPassword"><?php echo __("Password:") ?></label>
									<div class="controls">
										<?php echo $this->Form->input('password', array('class' => 'textbox', 'placeholder' => "Password", 'label' => false)); ?>
									</div>
									<div class="controls">
										<div class="password-security" id="result" style="width:269px; height:10px;">
											<div class="security"></div>
											<?php echo __("Level of Security") ?></div>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="confirmPassword"><?php echo __("Confirm Password:") ?></label>
									<div class="controls">
										<?php echo $this->Form->input('verify_password', array('type' => 'password', 'class' => 'textbox', 'placeholder' => "Confirm Password", 'label' => false)); ?>
									</div>
								</div>
							</div>
							<div class="control-group">
								<div class="controls">
									<label class="checkbox termcls">

										<?php echo $this->Form->checkbox('terms', array('hiddenField' => false)); ?>
										<label><?php echo __("&nbsp;I agree with Botangle's <a href='/demos/botangle/privacy'>Terms of Use and Privacy Policy.</a>.") ?></label></label>
								</div>
							</div>
							<div class="control-group form-actions">
								<?php
								echo $this->Form->button('Create My Account', array('type' => 'submit', 'class' => 'btn btn-primary'));
								echo $this->Form->button('Reset', array('type' => 'reset', 'class' => 'btn btn-reset'));
								?>
							</div>
							<?php echo $this->Form->end(); ?>
						</div>
					</div>
				<?php } ?>
			</div>
			<div class="span3 PageRight-Block">
				<p class="FontStyle20"><?php echo __("Already a member?") ?> <?php echo __("Sign In here") ?></p>
				<p><?php echo __("Click here to sign In in the Botangle !") ?> </p><br>
				<br>
				<?php
				echo $this->Html->link(__("Sign In"), array('action' => 'login'), array('class' => 'btn btn-primary'))
				/*
				  <button type="submit" class="btn btn-primary">Sign In</button> */
				?>
			</div>
		</div>

		<?php echo $this->element('Croogo.getintouch'); ?>
		<!-- @end .container --> 
	</div>

<script>
	var $j = jQuery.noConflict();
	function update(value){
	var type = "tutor";
		if(value == 2){
			type="tutor";
		}else if(value == 4){
			type="student";
		}
		location.href= "' . $this->webroot . 'registration/"+type;
	};
	jQuery(document).ready(function(){  
	jQuery(".btn-reset").click(function(){
			jQuery(".security").removeClass().addClass("security");
		});
		 jQuery("#UserPassword").keyup(function(){
		 
			jQuery(".security").addClass(checkStrength(jQuery("#UserPassword").val()));
		});
		function checkStrength(password){
			var strength = 0;
			if (password.length < 6) {
				return "weak";			
			}
			if (password.length > 7) strength += 1;
			if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/))  strength += 1;
			if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/))  strength += 1;
			if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/))  strength += 1;
			if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1;
			if (strength < 2 ){
				return "weak1";
			}
			else if (strength == 2){
				return "Good";
			}
			else{
				return "Strong";
			}
		}
	});
</script>