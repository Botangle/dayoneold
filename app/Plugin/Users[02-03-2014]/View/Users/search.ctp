 <!--Wrapper HomeServices Block Start Here-->
<div id="HomeServices">
  <div class="container">
    <div class=" row-fluid">
      <div class="span8 Breadcrame"> Home // <?php
	  echo $this->Paginator->counter(array(
						'format' => __d('croogo', 'showing {:current} of {:count} Results')
					));
					?>
	   <!--(225 online)--> </div>
      <div class="span3 pull-right" >
        <label class="checkbox online-checkbox">
              <input type="checkbox" id="isonline" <?php if(isset($online) && ($online!="")) { echo "checked='checked'"; } ?>>
          <?php echo __("Online Tutors")?></label>
      </div>
    </div>
  </div>
</div>

<!--Wrapper main-content Block Start Here-->
<div id="main-content">
  <div class="container">
    <div class="row-fluid">
      <div class="span12"> </div>
    </div>
    <div class="row-fluid">
      <div class="span3 LeftMenu-Block">
	    <?php echo $this->Form->create('User');?>
        <div class="Search-filter-Block">
          <p class="FontStyle20">Search by Keywords</p>
          <input type="text" value="" id="keyword" class="textbox1" name="searchvalue">
          <br>
          <p class="FontStyle20">Filter by Experience</p>
          <input type="text" value="" name="Experience_start" id="keyword" class="textbox2" >
          &nbsp; to &nbsp;
          <input type="text" value=""name="Experience_end" id="keyword" class="textbox2" >
          <br>
         <!-- <p class="FontStyle20">Gender</p>
          <label class="radio">
            <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1">
            Male </label>
          <label class="radio">
            <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1">
            Female </label>
          <label class="radio">
            <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1">
            No Preferences </label>
          <br>-->
          <button class="btn btn-primary" type="submit">Search</button>
        </div>
		 <?php echo $this->Form->end();?>
		
      </div>
      <div class="span9">
        <h2 class="page-title">&nbsp;</h2>
        <div class="StaticPageRight-Block">
          <div class="row-fluid">
		  <?php  
				$i = 1;
				if(!empty($users)){ 
					foreach($users as $k=>$user){
	 
					?>
						<div class="span4 search-result-box">
              <div class="search-result-img"><a href="<?php echo $this->webroot?>user/<?php echo $user['User']['username']?>">
			  <?php 
		 
		 if(file_exists(WWW_ROOT . DS . 'uploads' . DS . $user['User']['id']. DS . 'profile'. DS  .$user['User']['profilepic']) && $user['User']['profilepic']!=""){ ?>
		  <img src="<?php echo $this->webroot. 'uploads/'.$user['User']['id'].'/profile/'.$user['User']['profilepic'] ?> "class="img-circle" alt="student" width="242px" height="242px">
		<?php }else{		 ?>
		 <img src="<?php echo $this->webroot?>images/people1.jpg" class="img-circle" alt="people">
		 <?php } ?></a>
			  
			  </div>
              <div class="search-result-title">
                <p class="FontStyle20">
				<?php
		echo $this->Html->link(
    __(ucfirst($user['User']['username'])),	'/user/'.$user['User']['username']
     ,
	array('title'=>__($user['User']['username']) )
);
	   ?>
				 </p>
                <span><?php echo $user['User']['qualification']?> </span></div>
              <div class="search-result-details"><?php echo $user['User']['extracurricular_interests']?></div>
              <div class="search-result-options">
                <div class=" pull-left"><input type="number" name="your_awesome_parameter" id="some_id" class="rating"  value="<?php echo round($user[0]['rating'])?>"/></div>
                 <div class="search-result-chat pull-right">
                  <p class="option-pro">
				  <?php
		echo $this->Html->link(
    __(''),	'/user/'.$user['User']['username'],
	array('data-toggle'=>'tooltip','title'=>__('Profile') )
);
	   ?>
				  </p>
                  <!--<p class="option-chat"><a href="#" data-toggle="tooltip" title="Chat Now">&nbsp;</a></p>-->
                   <p class="option-msg">
				    <?php
		echo $this->Html->link(
    __(''),	'/users/messages/'.$user['User']['username'],
	array('data-toggle'=>'Message','title'=>__('Message') ));
	   ?>
				   </p>
                </div>
              </div>
            </div>
           
					<?php 
					if($i%3==0){ 
						echo '</div>  <div class="row-fluid">';
						}
						$i++;
					}
				} 
			?>
           </div>
         </div>
      </div>
    </div>
    <!-- @end .row -->
    
    <div class="row-fluid ">
      <div class="Get-in-Touch offset6">
        <p class="FontStyle20"><strong>Get in touch with us:</strong></p>
      </div>
    </div>
    <div class="row-fluid ">
      <div class="Social-Boxs Social-Email span3">
        <p class="FontStyle20"><a href="#"> Email Us</a></p>
      </div>
      <div class="Social-Boxs Social-FB span3">
        <p class="FontStyle20"><a href="#"> Facebook Us</a></p>
      </div>
      <div class="Social-Boxs Social-Tweet span3">
        <p class="FontStyle20"><a href="#"> Follow Us</a></p>
      </div>
      <div class="Social-Boxs Social-Linkedin span3">
        <p class="FontStyle20"><a href="#"> LinkedIn</a></p>
      </div>
    </div>
  </div>
  <!-- @end .container --> 
</div>
<!--Wrapper main-content Block End Here--> 

<script src="<?php echo $this->webroot?>Croogo/js/jquery-1.js"></script> 
<script src="<?php echo $this->webroot?>Croogo/js/jquery/bootstrap.js"></script>
<!--Rating JS/ CSS-->
<script src="<?php echo $this->webroot?>Croogo/js/bootstrap-rating-input.min.js"></script>
   <script src="<?php echo $this->webroot?>Croogo/js/autocomplete/jquery.min.js"></script>   
    <script src="<?php echo $this->webroot?>Croogo/js/autocomplete/bootstrap.min.js"></script>
<script type="text/javascript">
$(function () {
    $(".option-pro a").tooltip({
        placement : 'top'
    });
	 $(".option-chat a").tooltip({
        placement : 'top'
    });
	 $(".option-msg a").tooltip({
        placement : 'top'
    });
});

</script>   
<?php
echo $this->Html->script(array(
			'/croogo/js/bootstrap-rating-input.min',
			));	
			
?>
<script>
jQuery("#isonline").click(function(e){
if(this.checked){
 location.href=('<?php echo $this->webroot?>user/search/online');
 }else{
 location.href=('<?php echo $this->webroot?>user/search/');
 }
})
</script>