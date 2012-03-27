<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        
        $em = Zend_Registry::get('EntityManager');
        //$em = Application_Api_Util_Bootstrap::getResource('Entitymanagerfactory');
        //throw new Exception();
        $user = new User();
        
        $em->persist($user);
        $em->persist(new Order());
        $em->flush();
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }


}

