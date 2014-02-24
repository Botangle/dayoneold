<!--Wrapper HomeServices Block Start Here-->
<style>
.radio {float:left; padding:0px 10px 1px 10px !important; margin-top:0px !important; }
.controls input {float:left;}
</style> 
 
<?php
 
 
 echo $this->Layout->js();
		echo $this->Html->script(array( '/croogo/js/autocomplete/jquery-1.9.1',
			'/croogo/js/autocomplete/jquery.ui.core','/croogo/js/autocomplete/jquery.ui.widget','/croogo/js/autocomplete/jquery.ui.position','/croogo/js/autocomplete/jquery.ui.menu','/croogo/js/autocomplete/jquery.ui.autocomplete',
			));
 
 echo $this->Html->css(array(
			'/croogo/css/autocomplete/themes/base/jquery.ui.all', '/croogo/css/autocomplete/demos', 
		));
echo $this->Html->css(array(
			'/croogo/css/bootstrap-datetimepicker',
			 
		));
echo $this->Html->script(array(
			'/croogo/js/bootstrap-datetimepicker',
			 
			));		
echo $this->element("breadcrame",array('breadcrumbs'=>
	array(__("My Lesson")=>__("My Lesson")))
	);?>
 

<script>
	$(function() {
		 
		function split( val ) {
			return val.split( /,\s*/ );
		}
		function extractLast( term ) { 
			return split( term ).pop();
		}
		var typeid = "";
		$( "#LessonSubject,#LessonTutor" ) 
			// don't navigate away from the field on tab when selecting an item
			.bind( "keydown", function( event ) { typeid = this.id;
				if ( event.keyCode === $.ui.keyCode.TAB &&
						$( this ).data( "ui-autocomplete" ).menu.active ) {
					event.preventDefault();
				}
			})
			.autocomplete({
				source: function( request, response ) {
					if(typeid=='LessonSubject'){
					var url = "/demos/botangle/subject/search";
					///var url = "/botangle/subject/search";
					}if(typeid=='LessonTutor'){
					var url = "/demos/botangle/users/searchstudent";
					//var url = "/botangle/users/searchstudent";
					}
					$.getJSON( url, {
						term: extractLast( request.term )
					}, response );
				},
				search: function() {
					// custom minLength
					var term = extractLast( this.value );
					if ( term.length < 2 ) {
						return false;
					}
				},
				focus: function() {
					// prevent value inserted on focus
					return false;
				},
				select: function( event, ui ) {  
					var terms = split( this.value );
					if(typeid=='LessonTutor'){
						jQuery("#"+typeid+"Value").val(ui.item.id)
					}
					// remove the current input
					terms.pop();
					// add the selected item
					terms.push( ui.item.value );
					// add placeholder to get the comma-and-space at the end
					terms.push( "" );
					this.value = terms.join( " " );
					return false;
				}
			});
	});
	</script>
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
      <h2 class="page-title"><?php echo __("Add New Lesson")?></h2>
      <div class="StaticPageRight-Block">
      <div class="PageLeft-Block">
        <p class="FontStyle20 color1"><?php echo __("Propose Lesson Meeting")?></p>
        
         <?php echo $this->Form->create('Lesson',array('class'=>'form-horizontal'));?>
      
            <div class="control-group">
           
             
              <div class="control-group">
                <label class="control-label" for="tutorname">Student:</label>
                <div class="controls">
                 <?php echo $this->Form->input('tutor',array('class'=>'textbox','placeholder'=>"Student Name",'label' => false));
				  echo $this->Form->hidden('tutor',array('class'=>'textbox','placeholder'=>"Student Name",'label' => false,'id'=>'LessonTutorValue'))
				 ?>
                 
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="lesson-time">Lesson Time:</label>
                <div class="controls">
				<?php echo $this->Form->input('lesson_date',array('class'=>'textbox','placeholder'=>"Tutor",'label' => false,'id'=>'dtp_input2','type'=>'hidden'));?>
				   
				<div class=" input-append date form_date" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="width:47%;">
					
                    <input size="16" type="text" value="" readonly class="textbox2" style="width:57%" />
                    <span class="add-on" style="height:44px"><i class="icon-remove"></i></span>
					<span class="add-on" style="height:44px"><i class="icon-th"></i></span>
             
                </div>
				<?php echo $this->Form->input('lesson_time',array('class'=>'textbox','placeholder'=>"Tutor",'label' => false,'id'=>'dtp_input3','type'=>'hidden'));?>
				   <div class=" input-append date form_time" data-date="" data-date-format="hh:ii" data-link-field="dtp_input3" data-link-format="hh:ii" style="width:33%;">
                    <input size="16" class="textbox2" type="text" value="" readonly>
                    <span class="add-on" style="height:44px"><i class="icon-remove"></i></span>
					<span class="add-on" style="height:44px"><i class="icon-th"></i></span>
					 
                </div>
                    &nbsp; <span>BRST</span> &nbsp; <a href="#">Edit</a> <br>
<span class="marT10 clearfix">In Andrews timezone, this is 11:45 AM on 02/06/2014</span></div>
               </div> 
              
              <div class="control-group">
                <label class="control-label" for="inputEmail">Duration:</label>
                <div class="controls">
                <?php echo $this->Form->input('duration',array('class'=>'textbox','placeholder'=>"Duration",'label' => false));?>
                  
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
                $options = array('Signle lesson' => 'Signle lesson','Daily' => 'Daily','Weekly' => 'Weekly');
			$attributes = array('legend' => false,'checked' => 'Signle lesson','value'=>'Signle lesson',
			'label' => array('class' => 'radio'));
			echo $this->Form->radio('repet', $options, $attributes);?>
                
                </div>
              </div>
              
          <div class="control-group">
                <label class="control-label" for="postalAddress">Note:</label>
                <div class="controls">
                 <?php echo $this->Form->textarea('notes',array('class'=>'textarea','placeholder'=>'type your note','rows'=>3));?>
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
    </div>
    <!-- @end .row --> 
    
 
    
    
    
  </div>
  <!-- @end .container --> 
</div>
<!--Wrapper main-content Block End Here--> 
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