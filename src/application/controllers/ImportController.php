<?php

class ImportController extends Zend_Controller_Action
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        $import = new Service_Import();
        $import->import();

        $this->getResponse()->setBody("Import Complete")->sendResponse();

        //$this->_helper->layout->disableLayout();
        //$this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->redirector->goto('index', 'help');
    }

}

