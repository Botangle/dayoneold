<div id="footer">
  <div class="container">
    <div class="row-fluid">
      <div class="span4 fotter-left"> &copy; <?php echo date('Y');?>. All right reserved. botangle.com </div>
      <div class="span5 fotter-right pull-right">
        <ul class="nav nav-pills pull-right">
          <li><a href="#" title="Blog">Blog</a></li>
          <li><a href="#" title="Sitemap">Sitemap</a></li>
          <li>
		   <?php  
	  
	  
	  echo $this->Html->link(
    __('Terms of use'),	'/terms'
     ,
	array('class'=>' active','title'=>__('Terms of use') )
);?>
		  </li>
          <li>
		  <?php  
	  
	  
	  echo $this->Html->link(
    __('Privacy Policy'),	'/privacy'
     ,
	array('class'=>' active','title'=>__('Privacy Policy') )
);?>
		   </li>
		   <li>
		  
		   </li>
        </ul>
      </div>
    </div>
  </div>
</div>
