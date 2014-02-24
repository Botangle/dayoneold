<?php

 
class CategoryHelper extends AppHelper {
 public $helpers = array(
		'Html',
		'Form',
		'Session',
		'Js',
		'Croogo.Layout',
	);
 public function getParentName($id){
	App::import("Model", "Category");  
	$model = new Category();  
 
		   return $model->find('first',array('conditions'=>array('status'=>'1','id'=>$id)));
		    
		  
	}
	 

}
