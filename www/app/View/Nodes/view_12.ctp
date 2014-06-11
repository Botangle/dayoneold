<?php 

echo $this->element("breadcrame",array('breadcrumbs'=>
	array($node['Node']['title']=>$node['Node']['title']))
	) 
?>
<div id="main-content" class="how-it-works">
  <div class="container">
		 <div class="row-fluid">
      <div class="span12">
        
      </div>
    </div>
				 	 
    						<div class="row-fluid">
   <div class="row-fluid">
<?php $this->Nodes->set($node); ?>

       <div class="span12">
           <h2 class="page-title">How it Works</h2>

           <div class="StaticPageRight-Block">
               <div class="PageLeft-Block">
                   <p class="header text-center">Welcome to Botangle!</p>

                   <div class="video">
                       <iframe src="//player.vimeo.com/video/84438766?title=0&amp;byline=0&amp;portrait=0" width="1100" height="618" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                   </div>
               </div>

               <div class="row-fluid ">
                   <div class="span3">
                       <a class="" href="#">
                           <div class="box box-register">
                               <p class="FontStyle22"> Register with Botangle</p>
                               <img src="/images/joing-botangle.jpg" width="110" height="110">
                               <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut consequat imperdiet nibh quis sodales. </p>
                           </div>
                       </a>
                   </div>

                   <div class="span3">
                       <a class="" href="#">
                           <div class="box box-find-tutor">
                               <p class="FontStyle22"> Easily Find Tutors</p>
                               <img src="/images/find-tutor-img.jpg" width="110" height="110">
                               <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut consequat imperdiet nibh quis sodales. </p>
                           </div>
                       </a>
                   </div>

                   <div class="span3">
                       <a class="" href="#">
                           <div class="box box-tools">
                               <p class="FontStyle22"> Get Lessons with tools</p>
                               <img src="/images/tools-img.jpg" width="110" height="110">
                               <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut consequat imperdiet nibh quis sodales. </p>
                           </div>
                       </a>
                   </div>

                   <div class="span3">
                       <a class="" href="#">
                           <div class="box box-rate-tutor">
                               <p class="FontStyle22">Rate your tutor!</p>
                               <img src="/images/rate-tutorimg.jpg" width="110" height="110">
                               <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut consequat imperdiet nibh quis sodales. </p>
                           </div>
                       </a>
                   </div>
               </div>

               <div class="PageLeft-Block">
                   <p class="FontStyle20">A header here ...</p>
                   <p>And more details on how things work.</p>
               </div>
           </div>
       </div>

       <?php
		 
//		echo $this->Nodes->body();
		 
 ?>

 </div>
 </div>

    <!-- @end .row --> 
    
  </div>
  <!-- @end .container --> 
</div>
