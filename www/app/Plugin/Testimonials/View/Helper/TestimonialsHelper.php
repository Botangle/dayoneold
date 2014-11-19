<?php

App::uses('Helper', 'View/Helper');

class TestimonialsHelper extends AppHelper {
  public $helpers = array(
  		'Html',
  		'Form',
  		'Session',
  		'Js',
  		'Croogo.Layout',
  );

  public function getTestimonialList() {
    App::import("Model", "Testimonials.Testimonial");

    $model  = new Testimonial();
  	$result = $model->find('all', array('conditions'=>array('status'=>'1'), array('order'=>'date desc')));

    echo $this->_View->element('Testimonials.home', array('result'=>$result));
	}
}
