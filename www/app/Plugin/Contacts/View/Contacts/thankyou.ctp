<!--Wrapper HomeServices Block Start Here-->

<?php
echo $this->element("breadcrame", array('breadcrumbs' =>
	array('Contacts' => 'Contact us'))
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
			<?php echo $this->Element('leftinner') ?>
			<div class="span9">
				<br />
				<p class="text-center lead">Your message has been received. Thank you.</p>
			</div>
		</div>
		<!-- @end .row --> 

		<?php echo $this->element('Croogo.getintouch'); ?>

	</div>
	<!-- @end .container --> 
</div>
<!--Wrapper main-content Block End Here--> 

