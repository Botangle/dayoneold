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

                <div class="Signup">
                <?php
                if ($default == 2) { // expert view
                    echo $this->element(
                        'add/expert',
                        array(
                            'default' => $default,
                        )
                    );
				}
                else { // student view
                    echo $this->element(
                        'add/student',
                        array(
                            'default' => $default,
                        )
                    );
                }
                ?>
                </div>
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
	function update(value){
	var type = "tutor";
		if(value == 2){
			type="tutor";
		}else if(value == 4){
			type="student";
		}
		location.href= "<?php echo $this->webroot; ?>registration/"+type;
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