<?php
App::uses('Component', 'Controller');
class WalkthroughComponent extends Component {

    /**
     * If a view that is passed in here matches one that is being rendered,
     * we'll turn on our walkthrough system
     * @var array
     */
    public $viewFiles = array();

    public function beforeRender(Controller $controller) {

        // check to see if we're supposed to enable our walkthrough system for this view
        // if we are, then we'll fire up our helper, which will in turn kick in our JS / CSS setup
        if(in_array($controller->view, $this->viewFiles)) {
            $controller->helpers[] = 'Walkthrough';
        }
    }
}
