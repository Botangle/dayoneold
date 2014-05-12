<?php 
echo $this->Html->css(array(
			'/croogo/css/bootstrap-datetimepicker', 
		));
echo $this->Html->script(array(
			'/croogo/js/bootstrap-datetimepicker', 
			));		
$studetnanem= $readonly= $studetnanemid = "";
if(isset($user)){

	$studetnanem = $user['User']['username'];
	$studetnanemid = $user['User']['id'];
	$readonly = "readonly='readonly'";
}

?>



   <div class="span9">
      <h2 class="page-title"><?php echo __("Add New Lesson")?></h2>
      <div class="StaticPageRight-Block">
      <div class="PageLeft-Block">
        <p class="FontStyle20 color1"><?php echo __("Propose Lesson Meeting")?></p>
        
         <?php echo $this->Form->create('Lesson',array('class'=>'form-horizontal','url' => array('controller' => 'users', 'action' => 'lessons_add')));?>
      
            <div class="control-group">
           
		  
             
              <div class="control-group">
                <label class="control-label" for="tutorname">Student:</label>
                <div class="controls">
                 <?php echo $this->Form->input('tutorname',array('class'=>'textbox','placeholder'=>"Student Name",'label' => false,$readonly,'value'=>$studetnanem));
				 
				  echo $this->Form->hidden('tutor',array('class'=>'textbox','placeholder'=>"Student Name",'label' => false,$readonly,'value'=>$studetnanemid));
				//  echo $this->Form->hidden('tutor',array('class'=>'textbox','placeholder'=>"Student Name",'label' => false,'id'=>'LessonTutorValue'))
				 ?>
                 
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="lesson-time">Lesson Time:</label>
                <div class="controls">
				<?php echo $this->Form->input('lesson_date',array('class'=>'textbox','placeholder'=>"Expert",'label' => false,'id'=>'dtp_input2','type'=>'hidden'));?>
				   
				<div class=" input-append date form_date" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="width:47%;">
					
                    <input size="16" type="text" value="" readonly class="textbox2" style="width:57%" />
                    <span class="add-on" style="height:44px"><i class="icon-remove"></i></span>
					<span class="add-on" style="height:44px"><i class="icon-th"></i></span>
             
                </div>
				<?php echo $this->Form->input('lesson_time',array('class'=>'textbox','placeholder'=>"Expert",'label' => false,'id'=>'dtp_input3','type'=>'hidden'));?>
				   <div class=" input-append date form_time" data-date="" data-date-format="hh:ii" data-link-field="dtp_input3" data-link-format="hh:ii" style="width:33%;">
                    <input size="16" class="textbox2" type="text" value="" readonly>
                    <span class="add-on" style="height:44px"><i class="icon-remove"></i></span>
					<span class="add-on" style="height:44px"><i class="icon-th"></i></span>
					 
                </div>
                    &nbsp; <!--<span>BRST</span>--> &nbsp; <!--<a href="#">Edit</a>--> <br>
<!--<span class="marT10 clearfix">In Andrews timezone, this is 11:45 AM on 02/06/2014</span>--></div>
               </div> 
              
              <div class="control-group">
                <label class="control-label" for="inputEmail">Duration:</label>
                <div class="controls">
                <?php //echo $this->Form->input('duration',array('class'=>'textbox','placeholder'=>"Duration",'label' => false));?>
				 
                  <select name="data[Lesson][duration]" id="Lessonduration">
                      <option value="-1">-- Please choose --</option>
				  <?php for($i=.5;$i<12.5;$i=$i+.5){
						$sel = "";
						if(isset($Lesson) && $Lesson['Lesson']['duration']==$i){
							$sel = 'selected="selected"';
						}
						$pos = explode(".",$i);
						 
						$show=$i;
						if(isset($pos[1]) && $pos[1]=='5'){
							  $show = $pos[0].".5";
						}

                      if($i == .5) {
                          echo '<option value="' . $i .'" ' . $sel . '>30 min</option>';
                      } elseif($i == 1.0) {
                          echo '<option value="' . $i .'" ' . $sel . '>1 hour</option>';
                      } else {
                         echo '<option value="' . $i .'" ' . $sel . '>' . $show . ' hours</option>';
                      }
				  } ?>
				  </select>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="subject">Subject:</label>
                <div class="controls">
                 <?php echo $this->Form->input('subject',array('class'=>'textbox','placeholder'=>"Subject",'label' => false));?>
               
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="repeats">Repeats:</label>
                <div class="controls">
                <?php 
                $options = array('Single lesson' => 'Single lesson','Daily' => 'Daily','Weekly' => 'Weekly');
			$attributes = array('legend' => false,'checked' => 'Single lesson','value'=>'Single lesson',
			'label' => array('class' => 'radio'));
			echo $this->Form->radio('repet', $options, $attributes);?>
                
                </div>
              </div>
              
          <div class="control-group">
                <label class="control-label" for="postalAddress">Note:</label>
                <div class="controls">
                 <?php echo $this->Form->textarea('notes',array('class'=>'textarea','placeholder'=>'Type Your Note','rows'=>3,'required'=>false));?>
                </div>
              </div>
            </div>
           
            <div class="control-group form-actions">
             <?php 
			echo $this->Form->button('Submit', array('type' => 'submit','class'=>'btn btn-primary')); 
			echo $this->Form->button('Cancel', array('type' => 'reset','class'=>'btn btn-reset'));?>
             
            </div>
           <?php echo $this->Form->end();?>
       </div>
       
  
      </div>
      </div>
  
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