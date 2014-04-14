<!--Wrapper HomeServices Block Start Here-->
  <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
<?php
echo $this->element("breadcrame",array('breadcrumbs'=>
	array(__("My Lesson")=>__("My Lesson")))
	);
	$currentdate = date('Y-m-d');
	?>
<style>
.successnew {
    background: none repeat scroll 0 0 #66CC33;
    border-radius: 20px;
    color: #FFFFFF;
    margin: 10px 0;
    padding: 10px;
    text-align: center;
}
</style>

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
	  <?php  
	  if($this->Session->read('Auth.User.role_id')==2){  ?>
     <h2 class="page-title"><?php  echo __("Lesson");?> <p class="pull-right">
	 <?php
		echo $this->Html->link(
    __('+  Add New Lesson'),	'/users/createlessons'
     ,
	array('title'=>__('+  Add New Lesson') ,'class'=>'btn btn-primary btn-primary3','style'=>'width:125px'  )
);
	   ?> 
	  </p></h2><!----> <?php  } ?>
      <div class="StaticPageRight-Block">
	  
		<div id="newmsgdata"></div>
	  
      <div class="PageLeft-Block">
        <p class="FontStyle20 color1"><?php echo __("Active Lesson Proposal")?></p>
		 
		 <?php 
		 $newMsg = array();
		 $conditionstype = "";
		 if(!empty($activelesson)){  
		foreach($activelesson as $k=>$v){ ?>
        <div class="Lesson-row active">
         <div class="row-fluid">
        	<div class="span1 tutorimg">
			<?php 
		  if($this->Session->read('Auth.User.role_id')==2 && $v['Lesson']['laststatus_tutor']==1){
			$newMsg[] = $v['Lesson']['id'];
			$conditionstype = 'laststatus_tutor';
		  }
		  if($this->Session->read('Auth.User.role_id')==4 && $v['Lesson']['laststatus_student']==1){
			$newMsg[] = $v['Lesson']['id'];
			$conditionstype = 'laststatus_student';
		  }
		 if(file_exists(WWW_ROOT . DS . 'uploads' . DS . $v['User']['id']. DS . 'profile'. DS  .$v['User']['profilepic']) && $v['User']['profilepic']!=""){ ?>
		  <img src="<?php echo $this->webroot. 'uploads/'.$v['User']['id'].'/profile/'.$v['User']['profilepic'] ?> "class="img-circle" alt="student" width="242px" height="242px">
		<?php }else{		 ?>
		 <img src="<?php echo $this->webroot?>images/thumb-typ1.png" class="img-circle" alt="student">
		 <?php } ?> </div>
            <div class="span2 tutor-name"> <a href="#"><?php echo h($v['User']['username']) ?></a></div>
             <div class="span1 date"> <?php echo date('M d',strtotime($v['Lesson']['lesson_date'])) ?></div>
              <div class="span1 time"> <?php 
			 
			  $hr = explode(":",$v['Lesson']['lesson_time']);
			  echo $hr[0].":".$hr[1]  ?></div>
               <div class="span1 mins"> <?php
                   $duration = $v['Lesson']['duration'];
                   if($duration == .5) {
                       $duration = '30 min';
                   } elseif($duration == 1.0) {
                       $duration = '1 hour';
                   } else {
                       $duration .= ' hours';
                   }

                   echo h($duration) ?>
               </div>
                <div class="span2 subject"> <?php echo h($v['Lesson']['subject']) ?>  </div>
				 
               <div class="span2 mark"> 
			   <?php
		echo $this->Html->link(
    __('Change'),	'/users/changelesson/'.$v['Lesson']['id']
     ,
	array('title'=>__('Change') ,'class'=>'btn btn-primary btn-primary3','style'=>'width:125px','data-toggle'=>"modal"  )
);
	   ?>   </div>
			   
                <div class="span2 mark">
                    <?php if($v['Lesson']['is_confirmed'] == 0 &&
                        (
                            ($this->Session->read('Auth.User.role_id')==2 && $v['Lesson']['readlessontutor'] == 0)
                            || ($this->Session->read('Auth.User.role_id')==4 && $v['Lesson']['readlesson'] == 0)
                        )
                    ){ ?>
                        <?php
                        echo $this->Html->link(
                            __('Confirm'),	'/users/confirmedbytutor/'.$v['Lesson']['id']
                            ,
                            array('title'=>__('Confirm') ,'class'=>'btn btn-primary btn-primary3','style'=>'width:125px'  )
                        );
                        ?>

                    <?php } ?>
                </div>
         </div>
        </div>
        <?php }
         }
		?>
        
        
       </div>
	    <div class="PageLeft-Block">
        <p class="FontStyle20 color1"><?php echo __("Upcoming Lessons")?></p>
	  <?php  if(!empty($upcomminglesson)){ ?>
      
		<?php
		foreach($upcomminglesson as $k=>$v){ ?>
        <div class="Lesson-row">
         <div class="row-fluid">
        	<div class="span1 tutorimg"><?php 
		 
		 if(file_exists(WWW_ROOT . DS . 'uploads' . DS . $v['User']['id']. DS . 'profile'. DS  .$v['User']['profilepic']) && $v['User']['profilepic']!=""){ ?>
		  <img src="<?php echo $this->webroot. 'uploads/'.$v['User']['id'].'/profile/'.$v['User']['profilepic'] ?> "class="img-circle" alt="student" width="242px" height="242px">
		<?php }else{		 ?>
		 <img src="<?php echo $this->webroot?>images/thumb-typ1.png" class="img-circle" alt="student">
		 <?php } ?> </div>
            <div class="span2 tutor-name"> <a href="#"><?php echo h($v['User']['username'])?></a></div>
             <div class="span1 date"><?php echo date('M d',strtotime($v['Lesson']['lesson_date'])) ?></div>
              <div class="span1 time"><?php   $hr = explode(":",$v['Lesson']['lesson_time']);
			  echo h($hr[0].":".$hr[1]) ?></div>
               <div class="span1 mins"> <?php echo h($v['Lesson']['duration']) ?> </div>
                <div class="span2 subject"> <?php echo h($v['Lesson']['subject']) ?>  </div>
				
			<?php  
			if($this->Session->read('Auth.User.role_id')==2 && $v['Lesson']['is_confirmed']==0){ 
			echo '<div class="span2 mark"> ';
			echo $this->Html->link(
				__('Change'),	'/users/changelesson/'.$v['Lesson']['id']
				,
				array('title'=>__('Change') ,'class'=>'btn btn-primary btn-primary3','style'=>'width:125px','data-toggle'=>"modal"  )
			);
			echo '</div>';
			echo '<div class="span2 mark">'; 
			echo $this->Html->link(
				__('Confirmed'),	'/users/confirmedbytutor/'.$v['Lesson']['id']
				,
				array('title'=>__('Confirmed') ,'class'=>'btn btn-primary btn-primary3','style'=>'width:125px'  )
			);
			echo ' </div>'; } 
				if($this->Session->read('Auth.User.role_id')==2){ 
					if($v['Lesson']['id'] == $v['Lesson']['parent_id'] || $v['Lesson']['is_confirmed']==1){
						$calss = "disabled='disabled'";
						$url =  "javascript:void(0)";

						if($v['Lesson']['lesson_date'] == $currentdate){
						$calss =  "";
						$url =   $this->webroot.'users/whiteboarddata/'.$v['Lesson']['id'] ;
						}

						?>   
						<a href="<?php echo $url?>" class="btn btn-primary btn-primary3" <?php echo $calss; $url?>>Go To Lesson </a>
				<?php } 
				}else{  echo '<div class="span2 mark"> ';
					echo $this->Html->link(
						__('Change'),	'/users/changelesson/'.$v['Lesson']['id']
						,
						array('title'=>__('Change') ,'class'=>'btn btn-primary btn-primary3','style'=>'width:125px','data-toggle'=>"modal"  )
					);
					echo '</div><div class="span2 mark"> ';

                    $usedisabled = "disabled";
                    $url =  "javascript:void(0)";

                    if($v['Lesson']['lesson_date'] == $currentdate){
                        $usedisabled = "";
                        $url =   $this->webroot.'users/whiteboarddata/'.$v['Lesson']['id'] ;
					}
						echo $this->Html->link(
						__('Go To Lesson'),	$url,
						array('title'=>__('Go To Lesson') ,'class'=>'btn btn-primary btn-primary3','style'=>'width:125px','disabled'=>$usedisabled)
						); echo '</div>';
				} ?>


			</div></div>
		    
	<?php }
	
		} ?>
       </div>
	   <div class="PageLeft-Block">
        <p class="FontStyle20 color1"><?php echo __("Past Lessons")?></p>
       <?php  if(!empty($pastlesson)){ ?>
       
		<?php
		foreach($pastlesson as $k=>$v){ ?>
        <div class="Lesson-row">
         <div class="row-fluid">
        	<div class="span1 tutorimg"><?php 
		 
		 if(file_exists(WWW_ROOT . DS . 'uploads' . DS . $v['User']['id']. DS . 'profile'. DS  .$v['User']['profilepic']) && $v['User']['profilepic']!=""){ ?>
		  <img src="<?php echo $this->webroot. 'uploads/'.$v['User']['id'].'/profile/'.$v['User']['profilepic'] ?> "class="img-circle" alt="student" width="242px" height="242px">
		<?php }else{		 ?>
		 <img src="<?php echo $this->webroot?>images/thumb-typ1.png" class="img-circle" alt="student">
		 <?php } ?> </div>
            <div class="span2 tutor-name"> <a href="#"><?php echo h($v['User']['username']) ?></a></div>
             <div class="span1 date"><?php echo date('M d',strtotime($v['Lesson']['lesson_date'])); ?></div>
              <div class="span1 time"><?php   $hr = explode(":",$v['Lesson']['lesson_time']);
			  echo h($hr[0].":".$hr[1]) ?></div>
               <div class="span1 mins"> <?php echo h($v['Lesson']['duration']) ?> </div>
                <div class="span2 subject"> <?php echo h($v['Lesson']['subject']) ?>  </div>
             <div class="span2 mark lessonrating"> <?php
                 $review = null;
                 if(isset($reviews)&&isset($reviews[$v['Lesson']['id']]))
                 {
                     $review = $reviews[$v['Lesson']['id']];
                 }

                 if(!isset($review)){
                     if($this->Session->read('Auth.User.role_id')==4){
                         echo $this->Html->link(
                             __('Reviews'),	'javascript:void(0)'
                             ,
                             array('title'=>__('Reviews') ,'class'=>'btn btn-primary btn-primary3 reviews','data-url'=>'/users/lessonreviews/'.$v['Lesson']['id'],'style'=>'width:125px','data-toggle'=>"modal"   ) );
                     }
                 } else {
                     ?>
                     <p>Rating: <input type="number"  id="<?php echo $v['Lesson']['id'] ?>" value="<?php echo $review['Review']['rating'] ?>" class="rating" /></p>
                 <?php } ?>
             </div>

             <div class="span2 mark"> <button class="btn btn-primary btn-primary3" type="submit">Go To Lesson</button></div>
            </div>
            </div>
        <?php }
		} ?>
		 
         
       </div>
		 
      </div>
      </div>
    </div>
    <!-- @end .row --> 
    
 
    
    
    
  </div>
  <!-- @end .container --> 
</div>
<!--Wrapper main-content Block End Here--> 

<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="left:40%; width:auto; right:20%; height:320px; overflow:hidden; top:25%"></div>
<?php
if(!empty($newMsg)){
	$this->User->updateUserReadLesson($newMsg,$conditionstype); 
	echo '<input type="text" value=1 id="newmsg" />' ;
}
echo $this->Html->script(array(
			'/croogo/js/bootstrap-rating-input.min',
			));	
?>
  
   
<script type="text/javascript">
jQuery(document).ready(function(){ 
	if(jQuery('#newmsg').length>0){
		jQuery('#newmsgdata').addClass('successnew').html('You have New Lesson.');
	}
})
function updateRating(){  
 jQuery('.lessonratingdata').find('span').click(function(e){
	console.log(jQuery('.lessonratingdata').find('input').attr('id'));
	console.log(jQuery(this).attr('data-value'));
 })
 }
jQuery('[data-toggle="modal"]').click(function(e) {
 
    
   var currentclass = jQuery(this).hasClass('reviews')
  var url = jQuery(this).attr('data-url'); 
  jQuery.get(url, function(data) {   
   
	 jQuery('body').append('<div class="modal-backdrop in"></div>')
	 jQuery("#myModal").html(data).css({'display':'block','height':'auto','top':'25%','position':'absolute'});
	  jQuery('#myModal').css('height',jQuery('.StaticPageRight-Block').outerHeight()+300)
	 jQuery('.PageLeft-Block').css({'border-top':0,'box-shadow':'none'}).parent('div.span9').css({width:825+'px'})
	 
	  jQuery('.btn-reset').click(function(e){
		removebackground();
	  })
	 
	  
  });
});
function removebackground(){ 
 jQuery(".modal-backdrop").remove();
 jQuery("#myModal").html('').css('display','none');
}
 
</script>