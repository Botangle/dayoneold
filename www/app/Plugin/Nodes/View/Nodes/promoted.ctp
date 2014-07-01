<!--Wrapper HomeServices Block Start Here-->
<div id="HomeServices">
	<div class="container">
		<div class=" row-fluid">
			<div class="span4 Servicebox">
				<div class="service-img"><img src="<?php echo $this->webroot ?>images/join-img.png" alt="Join"></div>
				<div class="service-text" onclick="window.location.href = ('<?php echo $this->webroot ?>register')">
					<h2>Join Botangle</h2>
					<p>lets you connect with one of the<br/> best online experts the moment you'd like a hand.</p>
				</div>
			</div>
			<div class="span4 Servicebox"  onclick="window.location.href = ('<?php echo $this->webroot ?>user/search')">
				<div class="service-img"><img src="<?php echo $this->webroot ?>images/search-tutor.png" alt="Join"></div>
				<div class="service-text">
					<h2>Search Experts</h2>
					<p>Work with someone instantly or schedule<br/> a lesson with your preferred expert at a convenient time. </p>
				</div>
			</div>
			<div class="span4 Servicebox"  onclick="window.location.href = ('<?php echo $this->webroot ?>user/search')">
				<div class="service-img"><img src="<?php echo $this->webroot ?>images/learn-class.png" alt="Join"></div>
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
				<p> Botangle offers tutoring over video chat. So if you need help, you can get help from anyone, anywhere, for anything. </p>
				<span class="right">"</span>
				<p class="quote-client"><span>Jack,</span> New York</span> 
			</div>
		</div>
	</div>
</header>
<!--Wrapper HomeQuoteBlock Block End Here-->
<div class="row-fluid Featured-tutors-block">
	<center>
        <h2>Featured Experts</h2>
		<!--
		commenting this temporarily
			<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est aborum.</p>
		-->
	</center>
</div>
<div class="row-fluid">
	<?php
	if (isset($users) && !empty($users)) {
		foreach ($users as $k => $user) {
			?>
			<div class="span4 Tutor-list1">
				<div class="tutor-img"><a href="<?php echo $this->webroot ?>user/<?php echo $user['User']['username'] ?>">
						<?php if (!empty($user['User']['profilepic'])) : ?>
							<?php echo $this->Html->image($user['User']['profilepic'], array('class' => 'img-circle', 'alt' => 'student', 'style' => 'width: 195px; height: 195px')); ?>
						<?php else : ?>
							<img src="<?php echo $this->webroot ?>images/tutor1.jpg" class="img-circle" alt="student">
						<?php endif; ?>
					</a>

				</div>
				<div class="tutor-title">
					<h3><a href="<?php echo $this->webroot ?>user/<?php echo $user['User']['username'] ?>"><?php echo ucfirst($user['User']['name']) . ' ' . ucfirst($user['User']['lname']) ?></a></h3>
					<p><?php echo $user['User']['qualification'] ?></p>
				</div>
				<div class="tutor-bio">
					<p><?php echo $user['User']['extracurricular_interests'] ?></p>
					<div class="social">
						<a href="<?php echo !empty($user['User']['link_fb']) ? $user['User']['link_fb'] : '#'; ?>"><?php echo $this->Html->image('/croogo/img/facebook.png', array('class' => 'img-circle-left')); ?></a>
						<a href="<?php echo !empty($user['User']['link_twitter']) ? $user['User']['link_twitter'] : '#'; ?>"><?php echo $this->Html->image('/croogo/img/twitter.png', array('')); ?></a>
						<a href="<?php echo !empty($user['User']['link_googleplus']) ? $user['User']['link_googleplus'] : '#'; ?>"><?php echo $this->Html->image('/croogo/img/google.png', array('')); ?></a>
						<a href="<?php echo !empty($user['User']['link_thumblr']) ? $user['User']['link_thumblr'] : '#'; ?>"><?php echo $this->Html->image('/croogo/img/trumbler.png', array('class' => 'img-circle-right')); ?></a>
					</div>
				</div>
			</div>
			<?php
		}
	}
	?>
</div> 
