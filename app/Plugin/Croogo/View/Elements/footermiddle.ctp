<div id="main-content1">
  <div class="container">
    <div class="row-fluid">
      <div class="span3 joined-member-box">
        <div class="joined-member"> Joined members
          <p>470</p>
        </div>
        <div class="joined-member"> Online members
          <p>152</p>
        </div>
      </div>
      <div class="span5 social-updates">
        <div class="twitter-box">
          <p class="title1">Twitter Updates</p>
          <?php echo $this->Html->image('/croogo/img/twitter-update.png');?></div>
        <div class="facebook-box">
          <p class="title1">Likes on Facebook</p>
          <?php echo $this->Html->image('/croogo/img/facebook-like.png'); ?></div>
      </div>
<?php

$news = $this->Custom->getNewsList();
 
 ?>	  
      <div class="span4 latest-news-box">
        <div class="latest-news-box">
          <p class="title1"><?php echo __("Latest News")?></p>
		  <?php if(!empty($news)){  
			foreach($news as $k=>$v){ ?>
          <div class="media latest-news1"> <?php /*?><a href="#" class="pull-left"><?php echo $this->Html->image('/croogo/img/date.png"'); ?></a><?php */
		  
		 
		  $date = date('M',strtotime($v['News']['date']));
		  $day = date('d',strtotime($v['News']['date']));
		  ?>
          <div class="pull-left media-date">
         			 <div class="date"><?php echo $day?></div>
             		 <div class="month"><?php echo $date?></div>
          </div>
             
            <div class="media-body">
              <h4 class="media-heading1">
			  
			  
			  <?php echo $v['News']['title']?></h4>
              <a href="<?php echo $this->webroot?>news/detail/<?php echo str_replace(" ","-",$v['News']['title'])?>/<?php echo $v['News']['id']?>">Read more</a></div>
          </div>
		  <?php } 
		  }?>
         <!-- <div class="media latest-news1"> <a href="#" class="pull-left"><?php echo $this->Html->image('/croogo/img/date.png'); ?></a>
            <div class="media-body">
              <h4 class="media-heading">Chemistry Course Starting new sessions</h4>
              <a href="#">Read more</a></div>
          </div>
          <div class="media latest-news1"> <a href="#" class="pull-left"><?php //echo $this->Html->image('/croogo/img/date.png');?> </a>
            <div class="media-body">
              <h4 class="media-heading">Chemistry Course Starting new sessions</h4>
              <a href="#">Read more</a></div>
          </div>-->
        </div>
      </div>
    </div>
  </div>
</div>