<?php $accountsetting = $index = $accountbilling = $accountmessages = $accountLessons = $accountcalander = $accountinvite ="";
if($this->params['action']=='index' && $this->params['controller']=='users'){
		$index = "active";
	}
if($this->params['action']=='accountsetting'){
		$accountsetting = "active";
	}
	if($this->params['action']=='billing'){
		$accountbilling = "active";
	}
	 if($this->params['action']=='lessons'){
		$accountLessons = "active";
	}
	if($this->params['controller']=='usermessage' && $this->params['action']=='index'){
		$accountmessages = "active";
	}if($this->params['controller']=='users' && $this->params['action']=='calander'){
		$accountcalander = "active";
	}if($this->params['controller']=='users' && $this->params['action']=='invite'){
		$accountinvite = "active";
	}
	?>
	
<div class="span3 LeftMenu-Block">
    <ul>
    	<li><a href="<?php echo $this->webroot?>users/messages" title="Messages" class="<?php echo $accountmessages?>"><?php echo __('Messages')?><span class="badge pull-right"><?php 
		
		echo $this->User->Getunreadmessage($this->Session->read('Auth.User.id')); ?></span></a></li>
        <li>  <a href="<?php echo $this->webroot?>users/lessons" title="Lessons" class="<?php echo $accountLessons?>">Lessons<span class="badge pull-right"><?php 
		
		echo $this->User->Getunreadlesson($this->Session->read('Auth.User') ); ?></span></a></li>
        <li>
		<?php
		echo $this->Html->link(
    __('Billing'),	'/users/billing'
     ,
	array('title'=>__('Billing') ,'class'=>$accountbilling  )
);
	   ?> 
		 </li>
        <li>
		<?php
		echo $this->Html->link(
    __('My Account'),	'/users/'
     ,
	array('title'=>__('My Account') ,'class'=>$index  )
);
	   ?> </li>
        <li>
		<?php
		echo $this->Html->link(
    __('Account Settings'),	'/users/accountsetting'
     ,
	array('title'=>__('Account Settings'),'class'=>$accountsetting )
);
	   ?>
		 </li> 
		 <li>
		<?php
		echo $this->Html->link(
    __('My Calander'),	'/users/mycalander'
     ,
	array('title'=>__('My Calander'),'class'=>$accountcalander )
);
	   ?>
		 </li> 
		  <li>  <a href="<?php echo $this->webroot?>/users/invite" title="Lessons" class="<?php echo $accountinvite?>">Invite Users<span class="badge pull-right"><?php 
		
		echo $this->User->GetInvitesCount($this->Session->read('Auth.User') ); ?></span></a></li>
		  
    </ul>
    
      </div>