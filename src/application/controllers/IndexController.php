<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        
        
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $em = Zend_Registry::get('EntityManager');
        
        $query = $em->createQuery("SELECT p FROM Product p WHERE p.visible = true");
        
        $products = $query->getResult();
        
        $this->view->products = $products;
    }

}

