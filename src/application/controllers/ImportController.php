<?php

class ImportController extends Zend_Controller_Action
{

    public function init()
    {
        
        
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $import = new Service_Import();
        $import->import();
        
        echo "OK";
        
        exit;
    }

    

}

