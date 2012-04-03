<?php

class ImportController extends Zend_Controller_Action
{

    public function init()
    {
        
    }

    public function indexAction()
    {
        $import = new Service_Import();
        $import->import();
        
        $this->getResponse()->setBody("Import Complete")->sendResponse();
    }
}

