<?php

$this->extend('/Common/admin_edit');

$this->Html
->addCrumb($this->Html->icon('home'), '/admin')
->addCrumb(__d('croogo', 'Subject'), array('plugin' => 'categories', 'controller' => 'subject', 'action' => 'index'));

if ($this->request->params['action'] == 'admin_edit') {
$this->Html->addCrumb($this->data['Category']['name'], array(
'plugin' => 'categories', 'controller' => 'categories', 'action' => 'edit',
$this->data['Category']['id']
));
}

if ($this->request->params['action'] == 'admin_add') {
$this->Html->addCrumb(__d('croogo', 'Add'), array('plugin' => 'categories','controller' => 'categories', 'action' => 'add'));
}
?>


<?php echo $this->Form->create('Category', array('plugin' => 'categories'),array( 'controller' => 'subject', 'action' => 'addd'));?>

<div class="row-fluid">
    <div class="span8">

        <ul class="nav nav-tabs">
            <?php
			echo $this->Croogo->adminTab(__d('croogo', 'Subject'), '#user-main');
            echo $this->Croogo->adminTabs();
            ?>
        </ul>

        <div class="tab-content">

            <div id="user-main" class="tab-pane">
                <?php
				   
				 echo $this->Form->input('id');
                $this->Form->inputDefaults(array(
                'class' => 'span10',
                'label' => false,
                ));

                echo $this->Form->input('parent_id', array('class' => 'chzn-select', 'options' => $categorylist, 'label'
                => __d('croogo', 'Select Category','')));

                echo $this->Form->input('name', array(
                'label' => __d('croogo', 'Subject Name'),
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
        'class' => false,
        )) .

        $this->Html->endBox();

        echo $this->Croogo->adminBoxes();
        ?>
    </div>

</div>
<?php echo $this->Form->end(); ?>