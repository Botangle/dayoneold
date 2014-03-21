<?php 
$this->extend('/Common/admin_edit');

$this->Html
	->addCrumb($this->Html->icon('home'), '/admin')
	->addCrumb(__d('croogo', 'Testimonials'), array('plugin' => '', 'controller' => 'Testimonials', 'action' => 'index'));

if ($this->request->params['action'] == 'admin_edit') {
	$this->Html->addCrumb($this->data['Testimonial']['title'], array(
		'plugin' => 'Testimonials', 'controller' => 'Testimonials', 'action' => 'edit',
		$this->data['Testimonial']['id']
	));
	
}

if ($this->request->params['action'] == 'admin_add') {
	$this->Html->addCrumb(__d('croogo', 'Add'), array('plugin' => 'Testimonials','controller' => 'Testimonials', 'action' => 'add'));
}
?>
 

<?php echo $this->Form->create('Testimonial', array('plugin' => '', 'controller' => 'Testimonials', 'action' => 'add','type' => 'file'));?>

<div class="row-fluid">
	<div class="span8">

		<ul class="nav nav-tabs">
		<?php
			echo $this->Croogo->adminTab(__d('croogo', 'Testimonials'), '#user-main');
			echo $this->Croogo->adminTabs();
			 
		?>
		</ul>

		<div class="tab-content">

			<div id="user-main" class="tab-pane">
			<?php
				 
				 echo $this->Form->input('id',array('type'=>'hidden'));
				$this->Form->inputDefaults(array(
					'class' => 'span10',
					'label' => false,
				));
				 
				echo $this->Form->input('title', array(
					'label' => __d('croogo', 'Name'),
				));
				echo $this->Form->input('details', array(
					'label' => __d('croogo', 'details'),
					'type' => 'textarea',
				));
				 echo $this->Form->input('date', array(
					'label' => __d('croogo', false),
					'type' => 'hidden',
					'value'=>date('Y-m-d H:i:s')
				));
				 
			?>
			</div>

			<?php echo $this->Croogo->adminTabs(); ?>
		</div>
	</div>

	<div class="span4">
	<?php
		echo $this->Html->beginBox(__d('croogo', 'Publishing')) .
			$this->Form->button(__d('croogo', 'Save'), array('button' => 'default')) .
			$this->Html->link(
			__d('croogo', 'Cancel'), array('action' => 'index'),
			array('button' => 'danger')) .

			$this->Form->input('status', array(
				'label' => __d('croogo', 'Status'),
				'type'=>'checkbox',
				'class' => false,
			)) .

			$this->Html->endBox();

		echo $this->Croogo->adminBoxes();
	?>
	</div>

</div>
<?php echo $this->Form->end(); ?>