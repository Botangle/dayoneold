<div id="HomeServices">
  <div class="container">
    <div class=" row-fluid">
      <div class="span12 Breadcrame"> <?php  
	  
	  
	  echo $this->Html->link(
    __('Home'),	'/'
     ,
	array('class'=>'home active','title'=>__('Home') )
);?> <?php 
	  
	  foreach($breadcrumbs as $v){ 
		
		echo "// ".ucwords(strtolower($v));
	  }?> </div>
    </div>
  </div>
</div>
<div id="main-content">
  <div class="container">
<?php 	    
 
echo $this->Layout->sessionFlash();
?>
</div></div>

