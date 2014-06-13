<?php 
$homecls = $howitworksClass = $categoriescls = $topchart = $aboutclas=$reportbug =$logincls= $myaccountcls = $topheadclass="";
/*
if($this->Session->read('Auth.User.id')){ 
	if(($this->Session->read('Auth.User.role_id')==2) && ($this->Session->read('Auth.User.claim_status')==0))
	{
	echo '<div class="topadvheader">';
	echo "Get Cash Prizes and Free Lessons ".$this->Html->link('Click Here','/users/claimoffer','');
	echo "</div>";
	$topheadclass="claimmargin";
	} 
}else { 
	echo '<div class="topadvheader">';
	echo "Get Cash Prizes and Free Lessons ".$this->Html->link('Click Here','/register',''); 
	echo "</div>";
	$topheadclass="claimmargin";
}

*/
if($this->params['controller']=='nodes' && $this->params['action']=='promoted'){
	$homecls = "active";
}else if($this->params['controller']=='categories' && $this->params['action']=='index'){
	$categoriescls = "active";
}else if($this->params['controller']=='nodes' && $this->params->url=='how-it-works'){
    $howitworksClass = "active";
}else if($this->params['controller']=='users' && $this->params['action']=='topchart'){
	$topchart = "active";
}else if($this->params['controller']=='nodes' && $this->params->url=='about'){
	$aboutclas = "active";
}else if($this->params['controller']=='users' && $this->params['action']=='login'){
	$logincls = "active";
}else if($this->params['controller']=='nodes' && $this->params['action']=='reportbug'){
	$reportbug = "active";
}else if($this->params['controller']=='users' && $this->params['action']=='index'){
	$myaccountcls = "active";
}
?>
<div class="navbar navbar-default navbar-fixed-top  cbp-af-header <?php echo $topheadclass; ?>" role="navigation">
  <div class="container">
    <div class="navbar-header">
    <div class="Beta-tag">&nbsp;</div>
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <?php
	  echo $this->Html->link(
		   $this->Html->image(
			'/croogo/img/logo.png'
			),'/'
     ,  
	array('class'=>'navbar-brand','title'=>__('Home'),'escape' => false )
);?>
	  
	  
	  </div>
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav navbar-right">
      <?php //echo $this->Menus->menu('main', array('dropdown' => true)); ?>
        <li class="Home">
		<?php echo $this->Html->link(__('Home'), '/',
		array('class' => 'home '.$homecls.'', 'title' => __('Home')));
		?>
		 </li>
          <li>
              <?php echo $this->Html->link(
                  __('How it Works'),
                  '/how-it-works',
                  array('class' => 'category '.$howitworksClass.'', 'title' =>  __('How it Works'))
              );?>


          </li>
          <li>
		<?php echo $this->Html->link(
    __('Categories'),
    '/categories',
    array('class' => 'category '.$categoriescls.'', 'title' =>  __('Categories'))
);?>
	
		 
	 </li>
        <li><?php echo $this->Html->link(
    __('Top Charts'),
    '/users/topchart',
    array('class' => 'chart '.$topchart.'', 'title' =>  __('Top Charts'))
);?>
 </li>
        <li>
		<?php echo $this->Html->link(
    __('About us'),
    '/about',
    array('class' => 'about '.$aboutclas.'', 'title' =>  __('About us'))
);?>
		
		 </li>
        <li>
		<?php 
		 if($this->Session->read('Auth.User.id')){
		 echo $this->Html->link(
    __('My Account'),	'/users'
     ,
	array('class' => 'myaccount '.$myaccountcls.'', 'title'=>__('My Account') )
);
		 }else{
		echo $this->Html->link(__('Sign in'), '/login',
		array('class' => 'signin '.$logincls.'', 'title' => __('Sign in')));
		}
		
		?>
		 </li>
         <li><?php echo $this->Html->link(
    __('Report a Bug'),
    '/reportbug',
    array('class' => 'Report_Bug '.$reportbug.'', 'title' =>  __('Report a Bug'))
);?> </li>
      </ul>
    </div>
    <!--/.nav-collapse --> 
  </div>
</div>
<?php echo $this->Html->script(array('/croogo/js/cbpAnimatedHeader.min.js','/croogo/js/classie.js','/croogo/js/modernizr.custom.js',)); 
?>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>