<!--Wrapper HomeServices Block Start Here-->
<div id="HomeServices">
  <div class="container">
    <div class=" row-fluid">
      <div class="span4 Servicebox">
        <div class="service-img"><img src="<?php echo $this->webroot?>images/join-img.png" alt="Join"></div>
        <div class="service-text" onclick="window.location.href=('<?php echo $this->webroot?>register')">
          <h2>Join Botangle</h2>
          <p>lets you connect with one of the<br/> best online tutors the moment you'd like a hand.</p>
        </div>
      </div>
      <div class="span4 Servicebox"  onclick="window.location.href=('<?php echo $this->webroot?>user/search')">
        <div class="service-img"><img src="<?php echo $this->webroot?>images/search-tutor.png" alt="Join"></div>
        <div class="service-text">
          <h2>Search Tutors</h2>
          <p>Work with someone instantly or schedule<br/> a lesson with your preferred tutor at a convenient time. </p>
        </div>
      </div>
      <div class="span4 Servicebox"  onclick="window.location.href=('<?php echo $this->webroot?>user/search')">
        <div class="service-img"><img src="<?php echo $this->webroot?>images/learn-class.png" alt="Join"></div>
        <div class="service-text">
          <h2>Learn in Class</h2>
          <p>You'll be able to chat, use video, upload<br/> documents and write on a shared whiteboard.</p>
        </div>
      </div>
    </div>
  </div>
</div>
<!--Wrapper HomeServices Block End Here--> 
<!--Wrapper HomeQuoteBlock Block Start Here-->
<header id="HomeQuoteBlock">
  <div class="container text-center">
    <div class="QuoteBlock row-fluid">
      <div class="span12"> <span class="left">"</span>
        <p> Hi there, my name is Jack. I'm a college student by day, bike messenger by night, and I'm having trouble with Chem 101. I live in Los Angeles, I used Botangle and now I'm at the top of my class. </p>
        <span class="right">"</span>
        <p class="quote-client"><span>Jack,</span> New York</span> 
      </div>
    </div>
  </div>
</header>
<!--Wrapper HomeQuoteBlock Block End Here-->
<div class="row-fluid Featured-tutors-block">
      <div class="offset1">
        <h2>Featured Tutors</h2>
        <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est aborum.</p>
      </div>
    </div>
    <div class="row-fluid">
	<?php if(isset($users) && !empty($users)){
		foreach($users as $k=>$user){ ?>
      <div class="span4 Tutor-list1">
        <div class="tutor-img"><a href="<?php echo $this->webroot?>user/<?php echo $user['User']['username']?>">
		<?php 
		 
		 if(file_exists(WWW_ROOT .  'uploads' . DS . $user['User']['id']. DS . 'profile'. DS  .$user['User']['profilepic']) && $user['User']['profilepic']!=""){ ?>
		  <img src="<?php echo $this->webroot. 'uploads/'.$user['User']['id'].'/profile/'.$user['User']['profilepic'] ?> "class="img-circle" alt="student" width="242px" height="242px" style="width:196; height:191px;">
		<?php }else{		 ?>
		 <img src="<?php echo $this->webroot?>images/tutor1.jpg" class="img-circle" alt="student">
		 <?php } ?>
		</a>
		
		</div>
        <div class="tutor-title">
          <h3><a href="<?php echo $this->webroot?>user/<?php echo $user['User']['username']?>"><?php echo $user['User']['username']?></a></h3>
          <p><?php echo $user['User']['qualification']?></p>
        </div>
        <div class="tutor-bio">
          <p><?php echo $user['User']['extracurricular_interests']?></p>
          <div class="social">
		  	  <a href="#"><?php echo $this->Html->image('/croogo/img/facebook.png',array('class'=>'img-circle-left')); ?></a>
			  <a href="#"><?php echo $this->Html->image('/croogo/img/twitter.png',array('')); ?></a>
			  <a href="#"><?php echo $this->Html->image('/croogo/img/google.png',array('')); ?></a>
			  <a href="#"><?php echo $this->Html->image('/croogo/img/trumbler.png',array('class'=>'img-circle-right')); ?></a>
		  </div>
        </div>
      </div>
	  <?php }
	  }
	  ?>
    </div> 