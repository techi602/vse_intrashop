<?php

class IndexController extends Controller_Default
{
    public function init()
    {
        
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            
        }
        
        parent::init();
    }

    public function indexAction()
    {
        
    }

    public function catalogAction()
    {
        $this->view->products = $this->em->getRepository('Product')->fetchForHomepage();
    }

}

