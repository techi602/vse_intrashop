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
        $title = 'Katalog produktů';
        
        $this->view->products = $this->em->getRepository('Product')->fetchForHomepage();
        $this->view->title = $title;
        $this->view->headTitle($title);
    }

}

