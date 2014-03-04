<script>
  $(document).ready(function() {
 
     $(".Message-lists").niceScroll({cursorborder:"",cursorcolor:"#F38918",boxzoom:true}); // First scrollable DIV
	 
	 var $t = $('.Message-lists');
    $t.animate({"scrollTop": $('.Message-lists')[0].scrollHeight}, "slow");
 });
</script>
<?php 
 echo $this->Layout->js();
		echo $this->Html->script(array(
			'/croogo/js/fileupload',
			'/croogo/js/jquery/bootstrap',
			'/croogo/js/jquery.nicescroll.min',
			));
echo $this->element("breadcrame",array('breadcrumbs'=>
	array(__('My Messages')=>__('My Messages')))
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
    <?php echo $this->Element("myaccountleft") ?> 
      <div class="span9">
     <!-- <h2 class="page-title">Messages <p class="pull-right"><button class="btn btn-primary btn-primary1" type="submit">Send New Messages</button></p></h2> -->
      <div class="StaticPageRight-Block">
      <div class="row-fluid">
      <div class="span4 Message-List-Block">
		<?php
	 
		
		if(!empty($userData)){ 
				foreach($userData as $k=>$v){
				 
			 ?>
			
			
			<a class="" title="jaindeepak" href="/demos/botangle/users/messages/<?php echo $v['User']['username']; ?>">
			
			<div class="Message-row">
			<div class="row-fluid">
        	<div class="span4 sender-img">
			<?php 
		 
		 if(file_exists(WWW_ROOT . DS . 'uploads' . DS . $v['User']['id']. DS . 'profile'. DS  .$v['User']['profilepic']) && $v['User']['profilepic']!=""){ ?>
		  <img src="<?php echo $this->webroot. 'uploads/'.$v['User']['id'].'/profile/'.$v['User']['profilepic'] ?> "class="img-circle" alt="student" width="242px" height="242px">
		<?php }else{		 ?>
		 <img src="<?php echo $this->webroot?>images/thumb-typ1.png" class="img-circle" alt="student">
		 <?php } ?>
			</div>
            <div class="span8 sender-name"> 
			<?php /* echo $this->Html->link(__($v['User']['username']), '/users/messages/'.$v['User']['username'],
		array('class' => '', 'title' => __($v['User']['username']))); */?>
		
		  <?php echo $v['User']['username']; ?>
			 
<br><span class="FontStyle11"><?php echo $this->User->GettimedifferencedayBase($v['Usermessage']['date']);?></span></div>             
            </div>
            </div>
			
		</a>	
		<? }
		}
		?>
			
         
     </div>
	 
	 
        <div class="span8 Message-detail-Block" id="boxscroll">
        <div class="Message-lists">
		<?php
		
		if(!empty($sent_from)){
			foreach($sent_from as $k=>$v){ 
			if($v['Usermessage']['sent_from']==$loginUser){
			?>
			
      <div class="row-fluid"> 
      <div class="span2 sender-img"><?php 
		 
		 if(file_exists(WWW_ROOT . DS . 'uploads' . DS . $v['User']['id']. DS . 'profile'. DS  .$v['User']['profilepic']) && $v['User']['profilepic']!=""){ ?>
		  <img src="<?php echo $this->webroot. 'uploads/'.$v['User']['id'].'/profile/'.$v['User']['profilepic'] ?> "class="img-circle" alt="student" width="242px" height="242px">
		<?php }else{		 ?>
		 <img src="<?php echo $this->webroot?>images/thumb-typ1.png" class="img-circle" alt="student">
		 <?php } ?></div>
       <div class="span10 sender-text">
       <div id="tip-left">&nbsp;</div>
      	<p class="sender-name"><?php echo $v['User']['username']?></p>
        <p class="msg-content">
        <?php echo nl2br($v['Usermessage']['body'])?></p>
        <p class="msg-time"><?php echo $this->User->Gettimedifference($v['Usermessage']['date']);?></p>
      </div>
      
      
      </div>
	 <? } else { ?>
	  <div class="row-fluid">
     
       <div class="span10 sender-text">
       <div id="tip-right">&nbsp;</div>
      	<p class="sender-name"><?php echo $v['User']['username']?></p>
        <p class="msg-content">
        <?php echo nl2br($v['Usermessage']['body'])?></p>
        <p class="msg-time"><?php echo $this->User->Gettimedifference($v['Usermessage']['date']);?></p>
      </div>
       <div class="span2 sender-img">
	   <?php 
		 
		 if(file_exists(WWW_ROOT . DS . 'uploads' . DS . $v['User']['id']. DS . 'profile'. DS  .$v['User']['profilepic']) && $v['User']['profilepic']!=""){ ?>
		  <img src="<?php echo $this->webroot. 'uploads/'.$v['User']['id'].'/profile/'.$v['User']['profilepic'] ?> "class="img-circle" alt="student" width="242px" height="242px">
		<?php }else{		 ?>
		 <img src="<?php echo $this->webroot?>images/thumb-typ1.png" class="img-circle" alt="student">
		 <?php } ?>
		 
	    </div>
      
      </div>
    
	 <?php 	}
			}
		}?>
       </div>
	  <?php echo $this->Form->create('Usermessage',array('class'=>'form-inline form-horizontal',"role"=>"form",'type' => 'file'));
		     $this->request->data = $this->Session->read("Auth.User"); 
			echo $this->Form->input('id',array('value'=>''));
			echo $this->Form->hidden('send_to',array('value'=>$user['User']['id']));
			echo $this->Form->hidden('parent_id',array('value'=>$parentid)); 
			 
			?>
      <div id="Write-msg">
     		 
			 <?php echo $this->Form->textarea('body',array('class'=>'textarea','placeholder'=>"Type Your message",'rows'=>3));?>
           <?php /*   <div style="position:relative;" class="span2 FontStyle11">
		<a class='' href='javascript:;'>
			<span class="glyphicon glyphicon-paperclip"></span>
			Attach file
			<?php
			echo $this->Form->file('attached_files',array('style'=>'position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;'))
			?>
			 
		</a>
		&nbsp;
		<span class='label label-info' id="upload-file-info"></span>
	</div> */ ?>
             <!--<div class="span2 FontStyle11"><a href="#"> <span class="glyphicon glyphicon-paperclip"></span> Attach file</a></div>-->
             <div class="span5 pull-right msg-send-btn"> <?php echo $this->Form->button(__("Send Message"), array('type' => 'submit','class'=>'btn btn-primary')); 
			 ?></div>
      </div>
      <?php echo $this->Form->end();?>
        
      
      </div>
      
      
      </div>
   
        
        
        
        
       </div>
  
  
      </div>
      </div>
    </div>
    <!-- @end .row --> 
    
 
    
    
    
  </div>
  <!-- @end .container --> 