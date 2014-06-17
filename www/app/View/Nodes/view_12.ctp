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
                       <a class="" href="/register">
                           <div class="box box-register">
                               <p class="FontStyle22">Register with Botangle</p>
                               <img src="/images/joing-botangle.jpg" width="110" height="110">
                               <p>Create your Botangle profile and connect with top online experts today.  Sign up is quick and easy.  We never share any of your information with third parties.  Get started with just your email!</p>
                           </div>
                       </a>
                   </div>

                   <div class="span3">
                       <a class="" href="/categories">
                           <div class="box box-find-tutor">
                               <p class="FontStyle22">Find Experts Easily</p>
                               <img src="/images/find-tutor-img.jpg" width="110" height="110">
                               <p>No matter what subject you’re interested in Botangle has an expert that fits your needs and availability. Click here to search our community of experts and start learning on your terms today!</p>
                           </div>
                       </a>
                   </div>

                   <div class="span3">
                       <a class="" href="/register">
                           <div class="box box-tools">
                               <p class="FontStyle22">Learning Tools</p>
                               <img src="/images/tools-img.jpg" width="110" height="110">
                               <p>Botangle features an extensive collection of intuitive online learning tools including a virtual white board, video chat and text editor so you can become fully immersed in your subject and learn they way you choose.  Click here to get started!</p>
                           </div>
                       </a>
                   </div>

                   <div class="span3">
                       <a class="" href="/categories">
                           <div class="box box-rate-tutor">
                               <p class="FontStyle22">Rate your Expert!</p>
                               <img src="/images/rate-tutorimg.jpg" width="110" height="110">
                               <p>Tell us about your experiences.  Botangle&rsquo;s community gets bigger and better with great feedback.  Let instructors and the community know what was awesome and how to improve.  Click here to rate your instructor now!</p>
                           </div>
                       </a>
                   </div>
               </div>

               <div class="PageLeft-Block">
                   <p class="FontStyle20">What&rsquo;s important to know?</p>
                   <p>Botangle is an online learning community connecting experts with online learners across the globe.  Now you can take back control of your education and follow your passions on your schedule.  Our experts are here to help you transform your learning experience into real world results.  <a href="/register">Get started on Botangle today!</a></p>
               </div>

               <div class="PageLeft-Block">
                   <p class="FontStyle20">How pricing works</p>
                   <p>Botangle connects online learners with leading experts who set their own per minute and/or per hour rates of which Botangle takes a 30% fee.  Botangle's community is always free to join and is committed to promoting educational access worldwide.</p>
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
