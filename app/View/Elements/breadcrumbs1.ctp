<div class="container">
    <div class=" row-fluid">
      <div class="span12 Breadcrame"> <?php  echo $this->Html->link(
    __('Home'),	
    array(
        'controller' => '/',
        'action' => '',
        'full_base' => true,		
    ) ,
	array('class'=>'home active','title'=>__('Home') )
);?> <?php 
	  
	  foreach($breadcrumbs as $v){ 
		
		echo "// ".$v;
	  }?> </div>
    </div>
  </div>