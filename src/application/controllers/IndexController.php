<?php

class IndexController extends Controller_Default
{

    public function indexAction()
    {
        $user = User::getLoggedUser();
    }
    
    public function catalogAction()
    {
        $this->view->products = $this->em->getRepository('Product')->fetchForHomepage();
    }
    
    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();

        $this->_redirect('/');
    }
}

