<?php

$accountsetting = $index = $accountbilling = $accountmessages = $accountLessons = $accountcalander = $accountinvite = $paymentsetting = "";

if ( $this->params['action'] == 'index' && $this->params['controller'] == 'users') {
		$index = "active";
}

if ( $this->params['action'] == 'accountsetting' ) {
		$accountsetting = "active";
}

if ( $this->params['action'] == 'billing' ) {
		$accountbilling = "active";
}

if ( $this->params['action'] == 'lessons' ) {
		$accountLessons = "active";
}

if ( $this->params['controller'] == 'usermessage' && $this->params['action'] == 'index' ) {
		$accountmessages = "active";
}

if( $this->params['controller'] == 'users' && $this->params['action'] == 'calander' ) {
		$accountcalander = "active";
}

if ( $this->params['controller'] == 'users' && $this->params['action'] == 'invite' ) {
		$accountinvite = "active";
}

if ( $this->params['controller'] == 'users' && $this->params['action'] == 'paymentsetting' ) {
	$paymentsetting = "active";
}

?>

<div class="span3 LeftMenu-Block">
	<ul>
        <?php if ($this->Session->read('Auth.User.id') && $this->Session->read('Auth.User.role_id') == 2) : ?>
            <li>
                <a href="<?php echo $this->webroot ?>user/<?php echo $this->Session->read('Auth.User.username'); ?>"
                   title="Messages"><?php echo __('My Profile') ?></a>
            </li>
        <?php endif; ?>
        <li>
			<a href="<?php echo $this->webroot ?>users/messages" title="Messages" class="<?php echo $accountmessages ?>">
				<?php echo __('Messages')?>
				<span class="badge pull-right">
					<?php
						echo $this->User->Getunreadmessage($this->Session->read('Auth.User.id'));
					?>
				</span>
			</a>
		</li>
	  <li>
			<a href="<?php echo $this->webroot ?>users/lessons" title="Lessons" class="<?php echo $accountLessons?>">
				Lessons
				<span class="badge pull-right">
					<?php
						echo $this->User->Getunreadlesson($this->Session->read('Auth.User') );
					?>
				</span>
			</a>
		</li>
    <li>
			<?php
				echo $this->Html->link(
					__('Billing'),	'/users/billing',
					array('title'=>__('Billing') ,'class'=>$accountbilling)
				);
 			?>
	  </li>
    <li>
			<?php
				echo $this->Html->link(
					__('My Account'),	'/users/',
					array('title'=>__('My Account') ,'class'=>$index  )
				);
 			?>
		</li>
    <li>
			<?php /*
				echo $this->Html->link(
					__('Account Settings'),	'/users/accountsetting',
					array('title'=>__('Account Settings'),'class'=>$accountsetting )
				); */
			?>
		</li>
		<li>
			<?php
				echo $this->Html->link(
					__('My Calender'),	'/users/mycalander',
					array('title'=>__('My Calender'),'class'=>$accountcalander )
				);
 			?>
		</li>
<?php /*  	<li>
			<a href="<?php echo $this->webroot ?>users/invite" title="Lessons" class="<?php echo $accountinvite ?>">
				Invite Users
				<span class="badge pull-right">
					<?php
						echo $this->User->GetInvitesCount($this->Session->read('Auth.User') );
					?>
				</span>
			</a>
		</li> */?>
<?php /*		<li>
			<?php
				echo $this->Html->link(
					__('Payment Setting'),	'/users/paymentsetting',
					array('title'=>__('Payment Setting'),'class'=>$paymentsetting )
				);
 			?>
		</li> */ ?>
	</ul>
</div>
