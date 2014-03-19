<?php
echo $this->Html->css(array(
			'/testimonials/css/liquid-slider',
			 
		));
		echo $this->Layout->js();
		echo $this->Html->script(array(
			'/testimonials/jquery.easing.1.3',
			'/testimonials/jquery.touchSwipe.min.js',
			'/testimonials/jquery.liquid-slider.js',
		 
			));
?>

   <div class="liquid-slider"  id="main-slider">
 
  <?php foreach($result as $k=>$v) { 
    echo '<div class=""> <span class="left">"</span><p>';
  ?>
  <?php echo $v['Testimonial']['details'];
		echo '</p> <span class="right">"</span><p class="quote-client"><span>'.$v['Testimonial']['title'].'</span> </div>';
		} ?>
 
  
  </div>
	<script>
	  $(document).ready(function(){
   $('#main-slider').liquidSlider();
});
		
	</script>