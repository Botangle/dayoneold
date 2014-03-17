  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <a class="navbar-brand" href="#"><img src="<?php echo $this->webroot?>images/logo.png" alt="Botangle"></a> </div>
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav navbar-right">
        <li class="Home">
		<?php  echo $this->Html->link(
    __('Home'),	
    array(
        'controller' => '/',
        'action' => '',
        'full_base' => true,		
    ) ,
	array('class'=>'home active','title'=>__('Home') )
);?>
	</li>
        <li>
		<?php  echo $this->Html->link(
    __('Categories'),	
    array(
        'controller' => 'dashboards',
        'action' => 'index',
        'full_base' => true,		
    ) ,
	array('class'=>'category','title'=>__('Categories') )
);?>
		</li>
        <li>
		<?php  echo $this->Html->link(
    __('Top Charts'),	
    array(
        'controller' => 'dashboards',
        'action' => 'index',
        'full_base' => true,		
    ) ,
	array('class'=>'chart','title'=>__('Top Charts') )
);?>
		</li>
        <li>
		<?php  echo $this->Html->link(
    __('About us'),	
    array(
        'controller' => 'dashboards',
        'action' => 'index',
        'full_base' => true,		
    ) ,
	array('class'=>'about','title'=>__('About us') )
);?>
	
		</li>
        <li>
		<?php  echo $this->Html->link(
    __('Sign in'),	
    array(
        'controller' => 'dashboards',
        'action' => 'index',
        'full_base' => true,		
    ) ,
	array('class'=>'signin','title'=>__('Sign in') )
);?>
		</li>
      </ul>
    </div>
    <!--/.nav-collapse --> 
  </div>