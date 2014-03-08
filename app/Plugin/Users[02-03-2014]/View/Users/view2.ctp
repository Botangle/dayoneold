 <link rel="stylesheet" href="<?php echo $this->webroot?>Croogo/css/autocomplete/bootstrap1.min.css">

 <!--Wrapper HomeServices Block Start Here-->
<?php
echo $this->element("breadcrame",array('breadcrumbs'=>
	array($user['User']['username']=>$user['User']['username']))
	);
	
	?>

<!--Wrapper main-content Block Start Here-->
<div id="main-content">
  <div class="container">
    <div class="row-fluid">
      <div class="span12">
        <h2 class="page-title"></h2>
      </div>
    </div>
    <div class="row-fluid">
      <div class="span9 PageLeft-Block">
       <div class="row-fluid">
         <div class="span4"><?php 
		 
		 if(file_exists(WWW_ROOT .  'uploads' . DS . $user['User']['id']. DS . 'profile'. DS  .$user['User']['profilepic']) && $user['User']['profilepic']!=""){ ?>
		  <img src="<?php echo $this->webroot. 'uploads/'.$user['User']['id'].'/profile/'.$user['User']['profilepic'] ?> "class="img-circle" alt="student" width="242px" height="242px">
		<?php }else{		 ?>
		 <img src="<?php echo $this->webroot?>images/profile-img01.png" class="img-circle" alt="student">
		 <?php } ?></div>
        <div class="span8">
        <p class="FontStyle28 color1"><?php echo $user['User']['username']?>
		<?php if($user['User']['is_online'] == 1){ ?>
		<img src="<?php echo $this->webroot?>images/online.png" alt="online">
		<?php } else { ?>
		<img src="<?php echo $this->webroot?>images/offline.png" alt="onffline">

		<?php } ?></p>
        <p>College: Barnard College, Columbia University, Class of 2013<br>
<br>
English with a Concentration in Theater</p>
      <p>Subject: <span class="tag01">Act</span> <span class="tag01">Pre-Algebra</span> <span class="tag01">Music Theory</span> <span class="tag01">Music</span>  </p> 
      <p>Joined Classes: <span class="color1">20</span></p>
      <p>Share: <span class="profile-share"><img src="<?php echo $this->webroot?>images/fb.png" alt="email"></span> <span class="profile-share"><img src="<?php echo $this->webroot?>images/twitter.png" alt="email"></span> <span class="profile-share"><img src="<?php echo $this->webroot?>images/mail.png" alt="email"></span></p> 
      
 
        </div>
        
        </div>
        
            <!-- Student Profile tabs-->
      <div class="row-fluid">
      <span class="span12 profile-tabs">
            <ul id="myTab" class="nav nav-tabs">
              <li class="active"><a href="#home" data-toggle="tab">About Me</a></li>
              <li class=""><a href="#profile" data-toggle="tab">My Classes</a></li>
              
            </ul>
            <div id="myTabContent" class="tab-content">
              <div class="tab-pane fade active in" id="home"> 
              <div class="student-profile">             
              <a class="pull-left" href="#">
              <img src="<?php echo $this->webroot?>images/aboutme-img.png" alt="about"> </a>
              <div class="media-body">
                <h4 class="media-heading"><?php echo __("About me") ?></h4>
                <p><?php echo $user['User']['aboutme']?></p>
              </div></div>
              
              <div class="student-profile">             
              <a class="pull-left" href="#">
              <img src="<?php echo $this->webroot?>images/interests-img.png" alt="about"> </a>
              <div class="media-body">
                <h4 class="media-heading"><?php echo __("Interests")?></h4>
                <p><?php echo $user['User']['extracurricular_interests']?></p>
              </div></div>
              
            
              </div>
              <div class="tab-pane fade" id="profile">
               <div class="class-timeinfo">
               Total Classes: 14 &nbsp; &nbsp;   | &nbsp; &nbsp;   Total Time of Classes: 200 hours
               </div>
               <div class="Myclass-list row-fluid">
               	<div class="span2">
                 <img src="<?php echo $this->webroot?>images/people1.jpg" class="img-circle" alt="tutor"></div>
               	<div class="span3">
                <p class="FontStyle16">Class: <a href="#">Alzebra 1</a></p>
<p class="FontStyle11">Tutor: <strong>Alexzendar D.</strong></p>
                </div>
               	<div class="span5">
                "I tutored a student one-on-one as a member of the National Honor Society during my senior year of high..."
                </div>
                <div class="span2">
               		<p><input type="number" name="your_awesome_parameter" id="some_id" class="rating" data-clearable="remove"/></p>
				 <button class="btn btn-primary btn-primary3" type="submit">Review</button> 	
                </div>
                
                
               </div>
               <div class="Myclass-list row-fluid">
               	<div class="span2">
                 <img src="<?php echo $this->webroot?>images/people1.jpg" class="img-circle" alt="tutor"></div>
               	<div class="span3">
                <p class="FontStyle16">Class: <a href="#">Alzebra 1</a></p>
<p class="FontStyle11">Tutor: <strong>Alexzendar D.</strong></p>
                </div>
               	<div class="span5">
                "I tutored a student one-on-one as a member of the National Honor Society during my senior year of high..."
                </div>
                <div class="span2">
               		<p><input type="number" name="your_awesome_parameter" id="some_id" class="rating" data-clearable="remove"/></p>
				 <button class="btn btn-primary btn-primary3" type="submit">Review</button> 	
                </div>
                
                
               </div>
               <div class="Myclass-list row-fluid">
               	<div class="span2">
                 <img src="<?php echo $this->webroot?>images/people1.jpg" class="img-circle" alt="tutor"></div>
               	<div class="span3">
                <p class="FontStyle16">Class: <a href="#">Alzebra 1</a></p>
<p class="FontStyle11">Tutor: <strong>Alexzendar D.</strong></p>
                </div>
               	<div class="span5">
                "I tutored a student one-on-one as a member of the National Honor Society during my senior year of high..."
                </div>
                <div class="span2">
               		<p><input type="number" name="your_awesome_parameter" id="some_id" class="rating" data-clearable="remove"/></p>
				 <button class="btn btn-primary btn-primary3" type="submit">Review</button> 	
                </div>
                
                
               </div>
               <div class="Myclass-list row-fluid">
               	<div class="span2">
                 <img src="<?php echo $this->webroot?>images/people1.jpg" class="img-circle" alt="tutor"></div>
               	<div class="span3">
                <p class="FontStyle16">Class: <a href="#">Alzebra 1</a></p>
<p class="FontStyle11">Tutor: <strong>Alexzendar D.</strong></p>
                </div>
               	<div class="span5">
                "I tutored a student one-on-one as a member of the National Honor Society during my senior year of high..."
                </div>
                <div class="span2">
               		<p><input type="number" name="your_awesome_parameter" id="some_id" class="rating" data-clearable="remove"/></p>
				 <button class="btn btn-primary btn-primary3" type="submit">Review</button> 	
                </div>
                
                
               </div>
               
              </div>
              
            </div>
          <script>
			$(function () {
			$('#myTab a[href="#home"]').tab('show');
			})
		</script>
      </span>
      </div>
      </div>
      
      <div class="span3 PageRight-Block">
       <p class="FontStyle20">Emma D. Subjects</p>
        <p class="student-subject"><a href="#">Calculus</a><br>
        Received A's in all single variable and multivariable calculus classes, received a 5 on AP BC Calculus
</p>
<p class="student-subject"><a href="#">Calculus</a><br>
        Received A's in all single variable and multivariable calculus classes, received a 5 on AP BC Calculus
</p>

<p class="student-subject"><a href="#">Calculus</a><br>
        Received A's in all single variable and multivariable calculus classes, received a 5 on AP BC Calculus
</p>

<p class="student-subject"><a href="#">Calculus</a><br>
        Received A's in all single variable and multivariable calculus classes, received a 5 on AP BC Calculus
</p>

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

<!--Wrapper main-content Block End Here--> 
<script src="<?php echo $this->webroot?>Croogo/js/jquery-1.js"></script> 
<script src="<?php echo $this->webroot?>Croogo/js/jquery/bootstrap.js"></script>
<!--Rating JS/ CSS-->
<script src="<?php echo $this->webroot?>Croogo/js/bootstrap-rating-input.min.js"></script>
   <script src="<?php echo $this->webroot?>Croogo/js/autocomplete/jquery.min.js"></script>   
    <script src="<?php echo $this->webroot?>Croogo/js/autocomplete/bootstrap.min.js"></script>