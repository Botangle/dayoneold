<?php
echo $this->Html->script(array(
			'/croogo/js/bootstrap-rating-input.min',
			));	
			
?>
<style>
.span2.mark.lessonratingdata >  div > span {
    font-size: 25px;
}.span2.mark.lessonratingdata >   div > span {
    font-size: 25px;
}
</style>
<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="left:40%; width:auto; right:20%; height:320px; overflow:hidden; top:25%">

 <div class="PageLeft-Block">
        <p class="FontStyle20 color1"><?php echo __("Would you like to rate")?></p>
		</div>
		  <?php echo $this->Form->create('Review',array('class'=>'form-horizontal')); 
		 echo $this->Form->input('rate_to',array('class'=>'rating','type'=>'hidden','label'=>false,'div'=>false,'value'=>$Lesson['Lesson']['created'])); 
		   echo $this->Form->input('rate_by',array('class'=>'rating','type'=>'hidden','label'=>false,'div'=>false,'value'=>$Lesson['Lesson']['tutor']));
		    echo $this->Form->input('lesson_id',array('class'=>'rating','type'=>'hidden','label'=>false,'div'=>false,'value'=>$Lesson['Lesson']['id']));
		  ?>
		<div class="control-group">  <label class="control-label" for="postalAddress"><?php echo __("Rate here")?></label><div class="controls"> 
			  <div class="span2 mark lessonratingdata"> 
			  <?php echo $this->Form->input('rating',array('class'=>'rating','type'=>'number','label'=>false,'div'=>false));?>
			    </div> 
			</div>
		</div>
		<div class="control-group">
                <label class="control-label" for="postalAddress">Would you like or dislike about:</label>
                <div class="controls">
                 <?php echo $this->Form->textarea('reviews',array('class'=>'textarea','placeholder'=>'type your reviews','rows'=>3,'value'=>''));?>
                </div>
              </div>
         <div class="control-group form-actions">
             <?php 
			echo $this->Form->button('Submit', array('type' => 'submit','class'=>'btn btn-primary')); 
			echo $this->Form->button('Cancel', array('type' => 'reset','class'=>'btn btn-reset', 'data-dismiss'=>'modal','aria-hidden'=>'true','onclick'=>'removebackground()'	));?>
              
            </div>
           <?php echo $this->Form->end();?>
</div>
<script>
jQuery(document).ready(function(){ 
 jQuery('.lessonrating').find('span').click(function(e){
	console.log(jQuery('.lessonrating').find('input').attr('id'));
	console.log(jQuery(this).attr('data-value'));
 })
})
</script>