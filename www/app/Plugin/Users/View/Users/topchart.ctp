<!--Wrapper HomeServices Block Start Here-->
<div id="HomeServices">
	<div class="container">
		<div class=" row-fluid">

			<?php
			$userurlname = "all";
			if (isset($categoryname) && ($categoryname != "")) {
				$userurlname = $categoryname;
			}
			?>
			<div class="span8 Breadcrame"> <?php echo __("Home") ?> // <?php
				if (isset($this->Paginator) && isset($this->request['paging'])):

					echo $this->Paginator->counter(array(
						'format' => __d('croogo', 'Showing {:page} - {:current},  of {:count} total results ')
					));
				endif
				?><!-- (225 online) --></div>
			<div class="span3 pull-right" >
				<!--<label class="checkbox online-checkbox">
				  <input type="checkbox" id="isonline" <?php
				if (isset($online) && ($online != "")) {
					echo "checked='checked'";
				}
				?>>
				<?php echo __("&nbsp;Online Experts") ?></label>-->
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

				<p class="FontStyle20 mar-left15">Filter by category</p>
				<ul>

					<li><a href="<?php echo $this->webroot ?>users/topchart/" title="Messages">All Categories <span class="badge pull-right" id="totalCategory">0</span></a></li>
					<?php
					$totalResult = 0;
					if (!isset($online)) {
						$online = "";
					}
					foreach ($category as $k => $v) {
						?>
						<li><a href="<?php echo $this->webroot ?>users/topchart/<?php echo $v['Category']['id'] ?>/<?php echo $online ?>" title="Lessons"><?php echo $v['Category']['name'] ?><span class="badge pull-right"><?php
									$resultsCount = $this->User->getCategoryusercount($v['Category']['id']);
									echo $resultsCount;
									$totalResult = $resultsCount + $totalResult;
									?></span></a></li>
					<?php } ?>
					<script> jQuery('#totalCategory').html('<?php echo $totalResult ?>');
					</script>

				</ul>

			</div>
			<div class="span9">
				<h2 class="page-title">&nbsp;</h2>
				<div class="StaticPageRight-Block">
					<div class="row-fluid">
						<?php
						$i = 1;
						if (!empty($userlist)) {
							foreach ($userlist as $k => $user) {
								?>
								<div class="span4 search-result-box">
									<div class="search-result-img">
										<a href="<?php echo $this->webroot ?>user/<?php echo $user['User']['username'] ?>">
											<?php
											if (file_exists(WWW_ROOT . DS . 'uploads' . DS . $user['User']['id'] . DS . 'profile' . DS . $user['User']['profilepic']) && $user['User']['profilepic'] != "") {
												?>

												<img src="<?php echo $this->webroot . 'uploads/' . $user['User']['id'] . '/profile/' . $user['User']['profilepic'] ?> "class="img-circle" alt="student" width="242px" height="242px">

											<?php } else { ?>
												<img src="<?php echo $this->webroot ?>images/default.png" class="img-circle" alt="people">
											<?php } ?>
										</a>
									</div>
									<div class="search-result-options">
										<div class="pull-left"><input type="number" name="your_awesome_parameter" id="some_id" class="rating" data-clearable="0" value="<?php echo round($user[0]['rating']) ?>"/></div>
										<div class="search-result-chat pull-right">
											<p class="option-pro">
												<?php
												echo $this->Html->link(
														__(''), '/user/' . $user['User']['username'], array('data-toggle' => 'tooltip', 'title' => __('Profile'))
												);
												?>
											</p>
											<p class="option-msg">
												<?php
												echo $this->Html->link(
														__(''), '/users/messages/' . $user['User']['username'], array('data-toggle' => 'Message', 'title' => __('Message')));
												?>
											</p>

										</div>
									</div>
									<div class="search-result-title">

										<p class="FontStyle20">
											<?php
											echo $this->Html->link(
													ucfirst($user['User']['name']) . ' ' . ucfirst($user['User']['lname']), '/user/' . $user['User']['username']
													, array('title' => __($user['User']['username']))
											);
											?>
										</p>
										<span><?php echo $user['User']['qualification'] ?> </span></div>
									<div class="search-result-details"><?php echo $user['User']['extracurricular_interests'] ?></div>
								</div>

								<?php
								if ($i % 3 == 0) {
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

		<?php echo $this->element('Croogo.getintouch'); ?>

	</div>
	<!-- @end .container --> 
</div>
<!--Wrapper main-content Block End Here--> 
<!--Wrapper main-content1 Block Start Here-->
<!--<div id="main-content1">
  <div class="container">
    <div class="row-fluid">
      <div class="span3 joined-member-box">
        <div class="joined-member"> Joined members
          <p>2,098</p>
        </div>
        <div class="joined-member"> Online members
          <p>2,098</p>
        </div>
      </div>
      <div class="span5 social-updates">
        <div class="twitter-box">
          <p class="title1">Twitter Updates</p>
          <img src="<?php echo $this->webroot ?>images/twitter-update.png" alt="twitter"></div>
        <div class="facebook-box">
          <p class="title1">Likes on Facebook</p>
          <img src="<?php echo $this->webroot ?>images/facebook-like.png" alt="facebook"></div>
      </div>
      <div class="span4 latest-news-box">
        <div class="latest-news-box">
          <p class="title1">Latest News</p>
          <div class="media latest-news1"> <a href="#" class="pull-left"> <img src="<?php echo $this->webroot ?>images/date.png" alt="date"> </a>
            <div class="media-body">
              <h4 class="media-heading">Chemistry Course Starting new sessions</h4>
              <a href="#">Read more</a></div>
          </div>
          <div class="media latest-news1"> <a href="#" class="pull-left"> <img src="<?php echo $this->webroot ?>images/date.png" alt="date"> </a>
            <div class="media-body">
              <h4 class="media-heading">Chemistry Course Starting new sessions</h4>
              <a href="#">Read more</a></div>
          </div>
          <div class="media latest-news1"> <a href="#" class="pull-left"> <img src="<?php echo $this->webroot ?>images/date.png" alt="date"> </a>
            <div class="media-body">
              <h4 class="media-heading">Chemistry Course Starting new sessions</h4>
              <a href="#">Read more</a></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>-->
<!--Wrapper main-content1 Block End Here--> 

<?php
echo $this->Html->script(array(
	'/croogo/js/bootstrap-rating-input.min',
));
?>
<script>
	jQuery("#isonline").click(function(e) {
		if (this.checked) {
			location.href = ('<?php echo $this->webroot ?>/users/topchart/<?php echo $userurlname ?>/online');
		} else {
			location.href = ('<?php echo $this->webroot ?>/users/topchart/<?php echo $userurlname ?>/');
		}
	})
</script>

