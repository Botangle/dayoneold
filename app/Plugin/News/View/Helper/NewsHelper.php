<?php App::uses('Helper', 'View/Helper');

class NewsHelper extends AppHelper
{
    public $helpers = array(
        'Html',
        'Form',
        'Session',
        'Js',
        'Croogo.Layout',
    );

    public function getNewsList($id)
    {
        App::import("Model", "News");
        $model = new News();

        return $model->find('all', array('conditions' => array('status' => '1'), array('order' => 'date desc'));


    }


}
