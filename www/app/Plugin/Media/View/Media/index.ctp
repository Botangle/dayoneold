<!--Wrapper HomeServices Block Start Here-->
 
<?php 
 
echo $this->element("breadcrame",array('breadcrumbs'=>
	array('Media'=>'Media'))
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
      
	  <?php if(!empty($Media)){
		foreach($Media as $k=>$v){

		?>
		<div class="PageLeft-Block">
      <div class="row-fluid">
        <div class="span3 media-img"> <?php 
						 
		 if(file_exists(WWW_ROOT . DS . 'uploads' . DS .  'media'. DS  .$v['Media']['image']) && $v['Media']['image']!=""){ ?>
		  <img src="<?php echo $this->webroot. 'uploads/media/'.$v['Media']['image'] ?> ">
		<?php }else{		 ?>
		 <img src="<?php echo $this->webroot?>images/media-1.jpg" alt="media">
		 <?php } ?>
		 </div>
        <div class="span9 media-text">
        <p class="FontStyle20"><a href="#" ><?php echo $v['Media']['title']?></a></p>
        <p><?php echo substr($v['Media']['details'],0,500)?> <a href="<?php echo $this->webroot?>media/detail/<?php echo str_replace(" ","-",$v['Media']['title'])?>/<?php echo $v['Media']['id']?>">Read More</a></p>
<br>
<p>Posted on:  <?php echo date('M d,Y',strtotime($v['Media']['date'])) ?></p></div>
       </div> </div>
       <?php } 
	   }?>
     
       </div>
      </div>
    </div>
    <!-- @end .row --> 
    
	<?php echo $this->element('Croogo.getintouch'); ?>
    
  </div>
  <!-- @end .container --> 
</div>
<!--Wrapper main-content Block End Here--> 