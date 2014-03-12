<style>
.radio {float:left; padding:0px 10px 1px 10px !important; margin-top:0px !important; }
.controls input {float:left;}
</style> 
<?php 
echo $this->Html->css(array(
			'/croogo/css/bootstrap-datetimepicker',
			 
		));
echo $this->Html->script(array(
			'/croogo/js/bootstrap-datetimepicker',
			));
 	
			?>
    <div class="StaticPageRight-Block">
  <div class="PageLeft-Block">
        <p class="FontStyle20 color1"><?php echo __("Update Lesson Meeting")?></p>
        
         <?php echo $this->Form->create('Lesson',array('class'=>'form-horizontal'));
		  
		 if($Lesson['Lesson']['parent_id'] == 0){
			$Lesson['Lesson']['parent_id'] =  $Lesson['Lesson']['id'];
		 }
		 
		 echo $this->Form->input('parent_id',array('class'=>'textbox','placeholder'=>"Student Name",'label' => false,'value'=>$Lesson['Lesson']['parent_id'],'type'=>'hidden'));
		 ?>
      
            <div class="control-group">
           
             
              <div class="control-group">
                <label class="control-label" for="tutorname">Student:</label>
                <div class="controls">
                 <?php echo $this->Form->input('tutor',array('class'=>'textbox','placeholder'=>"Student Name",'label' => false,'disabled'=>'disabled','value'=>$Lesson['User']['username']));
				  echo $this->Form->hidden('tutor',array('class'=>'textbox','placeholder'=>"Student Name",'label' => false,'id'=>'LessonTutorValue','value'=>$Lesson['User']['id']))
				 ?>
                 
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="lesson-time">Lesson Time:</label>
                <div class="controls">
				<?php echo $this->Form->input('lesson_date',array('class'=>'textbox','placeholder'=>"Tutor",'label' => false,'id'=>'dtp_input2','type'=>'hidden','value'=>$Lesson['Lesson']['lesson_date']));?>
				   
				<div class=" input-append date form_date" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="width:47%;"  >
					
                    <input size="16" type="text" value="<?php echo $Lesson['Lesson']['lesson_date'] ?>" readonly class="textbox2" style="width:57%" />
                    <span class="add-on" style="height:44px"><i class="icon-remove"></i></span>
					<span class="add-on" style="height:44px"><i class="icon-th"></i></span>
             
                </div>
				<?php echo $this->Form->input('lesson_time',array('class'=>'textbox','placeholder'=>"Tutor",'label' => false,'id'=>'dtp_input3','type'=>'hidden','value'=>$Lesson['Lesson']['lesson_time']));?>
				   <div class=" input-append date form_time" data-date="" data-date-format="hh:ii" data-link-field="dtp_input3" data-link-format="hh:ii" style="width:33%;">
                    <input size="16" class="textbox2" type="text" value="<?php echo  $Lesson['Lesson']['lesson_time']?>" readonly>
                    <span class="add-on" style="height:44px"><i class="icon-remove"></i></span>
					<span class="add-on" style="height:44px"><i class="icon-th"></i></span>
					 
                </div>
                    &nbsp; <span>BRST</span> &nbsp; <a href="#">Edit</a> <br>
<span class="marT10 clearfix">In Andrews timezone, this is 11:45 AM on 02/06/2014</span></div>
               </div> 
              
              <div class="control-group">
                <label class="control-label" for="inputEmail">Duration:</label>
                <div class="controls"> 
                  <select name="data[Lesson][duration]" id="Lessonduration">
				  <?php for($i=0;$i<24;$i=$i+.5){
						$sel = "";
						if($Lesson['Lesson']['duration']==$i){
							$sel = 'selected="selected"';
						}
				  ?>
					<option value="<?php echo $i?>" <?php echo $sel?> ><?php echo $i?></option>
				  <?php } ?>
				  </select>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="subject">Subject:</label>
                <div class="controls">
                 <?php echo $this->Form->input('subject',array('class'=>'textbox','placeholder'=>"Subject",'label' => false,'value'=>$Lesson['Lesson']['subject']));?>
               
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="repeats">Repeats:</label>
                <div class="controls">
                <?php 
                $options = array('Signle lesson' => 'Signle lesson','Daily' => 'Daily','Weekly' => 'Weekly');
			$attributes = array('legend' => false,'checked' => $Lesson['Lesson']['repet'],'value'=>'Signle lesson',
			'label' => array('class' => 'radio'));
			echo $this->Form->radio('repet', $options, $attributes);?>
                
                </div>
              </div>
              
          <div class="control-group">
                <label class="control-label" for="postalAddress">Note:</label>
                <div class="controls">
                 <?php echo $this->Form->textarea('notes',array('class'=>'textarea','placeholder'=>'type your note','rows'=>3,'value'=>$Lesson['Lesson']['notes']));?>
                </div>
              </div>
            </div>
           
            <div class="control-group form-actions">
             <?php 
			echo $this->Form->button('Submit', array('type' => 'submit','class'=>'btn btn-primary')); 
			echo $this->Form->button('Cancel', array('type' => 'reset','class'=>'btn btn-reset', 'data-dismiss'=>'modal','aria-hidden'=>'true'	));?>
              
            </div>
           <?php echo $this->Form->end(); ?>
       </div>
        </div>
       
<script type="text/javascript">
//jQuery(".modal-backdrop").remove().append('<div class="modal-backdrop in"></div>')
jQuery('[data-dismiss="modal"]').click(function(e) {
	jQuery(".modal-backdrop").remove();
})
  
  
  </script>
  <script>
var currentdate = new Date();
var y = currentdate.getFullYear();
var m = currentdate.getMonth()+1;
var d = currentdate.getDate();
dd = y+"-"+m+"-"+d;
jQuery('.form_date').datetimepicker({
        language:  'en',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0,
		startDate: dd,
    });
	jQuery('.form_time').datetimepicker({
        language:  'en',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 1,
		minView: 0,
		maxView: 1,
		forceParse: 0
    });
</script>
