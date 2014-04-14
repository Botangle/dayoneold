 
<style>
.span2.mark.lessonratingdata >  div > span {
    font-size: 25px;
}.span2.mark.lessonratingdata >   div > span {
    font-size: 25px;
}
</style>
 

 <div class="PageLeft-Block">
        <p class="FontStyle20 color1"><?php echo __("Would you like to rate")?></p>
		</div>
		  <?php echo $this->Form->create('Review',array('class'=>'form-horizontal','id'=>'reviewForm'));
		  ?>
		<div class="control-group">  <label class="control-label" for="postalAddress"><?php echo __("Rate here")?></label><div class="controls"> 
			  <div class="span2 mark lessonratingdata"> 
			  <?php echo $this->Form->input('rating',array('class'=>'ratingAdd','data-add'=>'true', 'type'=>'number','label'=>false,'div'=>false));?>
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
			echo $this->Form->button('Submit', array('type' => 'button','class'=>'btn btn-primary','id'=>'reviewPost')); 
			echo $this->Form->button('Cancel', array('type' => 'reset','class'=>'btn btn-reset', 'data-dismiss'=>'modal','aria-hidden'=>'true','onclick'=>'removebackground()'	));?>
              
            </div>
           <?php echo $this->Form->end();?>
 
<script>  
	$(".ratingAdd").rating();  
	$("#reviewPost").on('click',
		function()
		{ 
			var formData = $('#reviewForm').serialize();  
			var formUrl = '<?php echo $this->here; ?>';
			$.ajax(
				{ 
					type:'POST',
					data:formData, 
					dataType:"json",
					 
					success:function (data, textStatus) 
					{  	
							if(data.status==0)
							{  
								alert(data.message)
							}
							else
							{
								document.location.reload(true);
							}
			  		}, 
			  	   url:formUrl
				}
			)
		}
	);
</script>