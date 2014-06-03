<div class="users form">
	<div class="row-fluid">
		<div class=" span6 offset1">
			<h2><?php echo $title_for_layout; ?></h2>
			<?php echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' => 'reset', $username, $key))); ?>
			<fieldset>
				<?php
				echo $this->Form->input('password', array('label' => __d('croogo', 'New password')));
				echo $this->Form->input('verify_password', array('type' => 'password', 'label' => __d('croogo', 'Verify Password')));
				?>
			</fieldset>
			<?php echo $this->Form->button('Submit', array('class' => 'btn btn-primary')); ?>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>