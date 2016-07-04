<?php

namespace Dashboard\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends \BaseX\Controller\BaseController {

    public function indexAction() {
        
        var_dump($this->getSessionStorage()->isAuthenticate());
        
    }

}
