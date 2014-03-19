<!--Wrapper HomeServices Block Start Here-->
 
<?php 
 
echo $this->element("breadcrame",array('breadcrumbs'=>
	array('News'=>'News'))
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
    <?php echo $this->Element('leftinner')?>
      <div class="span9">
      <h2 class="page-title">Updates</h2>
      <div class="StaticPageRight-Block">
      
	  <?php if(!empty($news)){
		foreach($news as $k=>$v){

		?>
		<div class="PageLeft-Block">
      <div class="row-fluid">
        <div class="span3 media-img"> <?php 
						 
		 if(file_exists(WWW_ROOT . DS . 'uploads' . DS .  'news'. DS  .$v['News']['image']) && $v['News']['image']!=""){ ?>
		  <img src="<?php echo $this->webroot. 'uploads/news/'.$v['News']['image'] ?> ">
		<?php }else{		 ?>
		 <img src="images/media-1.jpg" alt="media">
		 <?php } ?>
		 </div>
        <div class="span9 media-text">
        <p class="FontStyle20"><a href="#" ><?php echo $v['News']['title']?></a></p>
        <p><?php echo substr($v['News']['details'],0,500)?> <a href="#">Read More</a></p>
<br>
<p>Posted on:  <?php echo date('M d,Y',strtotime($v['News']['date'])) ?></p></div>
       </div> </div>
       <?php } 
	   }?>
     
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