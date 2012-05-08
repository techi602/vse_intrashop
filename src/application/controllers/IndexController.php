<?php

class IndexController extends Controller_Default
{

    public function indexAction()
    {
        
    }
    
    public function catalogAction()
    {
        $this->view->products = $this->em->getRepository('Product')->fetchForHomepage();
    }
}

