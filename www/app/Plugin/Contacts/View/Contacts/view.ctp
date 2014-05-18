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
			<?php echo $this->Element('leftinner'); ?>

			<div class="span9">
				<h2 class="page-title">Contact Us</h2>
				<div id="contact-<?php echo $contact['Contact']['id']; ?>" class="PageLeft-Block">
					<p class="FontStyle20 text-center">Send us a Quick Message!</p>

					<div class="contact-body">
						<?php echo $contact['Contact']['body']; ?>
					</div>

					<?php if ($contact['Contact']['message_status']): ?>
						<div class="contact-form">
							<?php
							echo $this->Form->create('Message', array(
								'url' => array(
									'plugin' => 'contacts',
									'controller' => 'contacts',
									'action' => 'view',
									$contact['Contact']['alias'],
								),
								'class' => 'form-inline form-horizontal',
								'inputDefaults' => array(
									'label' => false,
								)
							));
							echo '<div class="row-fluid">';
							echo $this->Form->input('Message.name', array(
								'label' => array('class' => 'sr-only', 'text' => __d('croogo', 'Your name')),
								'div' => array('class' => 'form-group span6'),
								'placeholder' => 'Your Name',
								'class' => 'form-control textbox1',
							));
							echo $this->Form->input('Message.email', array(
								'label' => array('class' => 'sr-only', 'text' => __d('croogo', 'Your email')),
								'div' => array('class' => 'form-group span6'),
								'placeholder' => 'Your Email Address',
								'class' => 'form-control textbox1',
							));
							echo '</div>';

							echo '<div class="row-fluid marT10">';
							echo $this->Form->input('Message.title', array(
								'label' => array('class' => 'sr-only', 'text' => __d('croogo', 'Subject')),
								'div' => array('class' => 'form-group span12'),
								'placeholder' => 'Your Subject',
								'class' => 'form-control textbox1',
							));
							echo '</div>';

							echo '<div class="row-fluid">';
							echo $this->Form->input('Message.body', array(
								'label' => array('class' => 'sr-only', 'text' => __d('croogo', 'Message')),
								'div' => array('class' => 'form-group span12 marT10'),
								'placeholder' => 'Your Message',
								'class' => 'textarea',
							));
							echo '</div>';

							if ($contact['Contact']['message_captcha']):
								echo '<div class="row-fluid marT10">';
								echo $this->Recaptcha->display_form();
								echo '</div>';
							endif;

							echo '<div class="row-fluid marT10">';
							echo $this->Form->submit(__d('croogo', 'Submit'), array(
								'div' => array('class' => 'span12'),
								'class' => 'btn btn-primary',
							));
							echo '</div>';

							echo $this->Form->end();
							?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<!-- @end .row --> 

		<?php echo $this->element('Croogo.getintouch'); ?>

	</div>
	<!-- @end .container --> 
</div>
<!--Wrapper main-content Block End Here--> 

