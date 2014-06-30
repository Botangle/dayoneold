<?php

// if a studentView variable exists, then we're working on displaying this form to a student
// and we want to make all the fields show in that manner
if(isset($studentView)) {
    $studentView = 1;
    $userNameDisplay = 'Expert';

} else {
    // we're displaying this form to a tutor and we want to display all the fields in a consistent manner there
    // PLUS, we want to *name* all the fields in a consistent manner
    $studentView = 0;
    $userNameDisplay = 'Student';
}


echo $this->Html->css(array(
			'/croogo/css/bootstrap-datetimepicker', 
		));
echo $this->Html->script(array(
			'/croogo/js/bootstrap-datetimepicker', 
			));

// these values are unknown if we're dealing with a tutor (who chooses a student name from a list)
// but are already known if a student is suggesting a lesson
$studentName= $readonly= $studentNameId = "";
if(isset($user)){

	$studentName = $user['User']['username'];
	$studentNameId = $user['User']['id'];
	$readonly = "readonly='readonly'";
}

?>

<style>
	.modal-open {
		overflow: visible;
	}
</style>

   <div class="span9">
      <h2 class="page-title"><?php echo __("Add New Lesson")?></h2>
      <div class="StaticPageRight-Block">
      <div class="PageLeft-Block">
        <p class="FontStyle20 color1"><?php echo __("Propose Lesson Meeting")?></p>
        
         <?php echo $this->Form->create('Lesson',array('class'=>'form-horizontal','url' => array('controller' => 'users', 'action' => 'lessons_add')));?>
          <?php echo $this->Form->hidden('student_view', array('value' => $studentView)); ?>
      
            <div class="control-group">

              <div class="control-group">
                <label class="control-label" for="name"><?php echo h($userNameDisplay) ?>:</label>
                <div class="controls">
                 <?php echo $this->Form->input('username',array('class'=>'textbox','placeholder'=>$userNameDisplay . " Name",'label' => false,$readonly,'value'=>$studentName));
				 
				  echo $this->Form->hidden('user_id',array('value'=>$studentNameId));
				 ?>
                 
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="lesson-time">Lesson Time:</label>
                <div class="controls">
				<?php echo $this->Form->input('lesson_date',array('class'=>'textbox','label' => false,'id'=>'dtp_input2','type'=>'hidden'));?>
				   
				<div class=" input-append date form_date" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="width:47%;">
					
                    <input size="16" type="text" value="" readonly class="textbox2" style="width:57%" />
                    <span class="add-on" style="height:44px"><i class="icon-remove"></i></span>
					<span class="add-on" style="height:44px"><i class="icon-th"></i></span>
             
                </div>
				<?php echo $this->Form->input('lesson_time',array('class'=>'textbox','label' => false,'id'=>'dtp_input3','type'=>'hidden'));?>
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
				  <?php for($i = 30; $i < 750; $i = $i + 30){
						$sel = "";

						if(isset($Lesson) && $Lesson['Lesson']['duration']==$i){
							$sel = ' selected="selected"';
						}

                        $durationString = $i / 60;

						$pos = explode(".",$durationString);
						 
						$show = $durationString;
						if(isset($pos[1]) && $pos[1]=='5'){
							  $show = $pos[0].".5";
						}

                      if($i == 30) {
                          echo "<option value='{$i}'{$sel}>30 min</option>";
                      } elseif($i == 60) {
                          echo "<option value='{$i}'{$sel}>1 hour</option>";
                      } else {
                         echo "<option value='{$i}'{$sel}>{$show} hours</option>";
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
                echo $this->Form->radio(
                    'repetition',
                    array(
                        0 => 'Single lesson',
                        1 => 'Daily',
                        2 => 'Weekly',
                    ),
                    array(
                        'legend' => false,
                        'checked' => 0,
                        'value' => 0,
                        'label' => array(
                            'class' => 'radio'
                        )
                    )
                );?>
                
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
		startDate: dd
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