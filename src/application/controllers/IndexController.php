<?php

class IndexController extends Controller_Default
{

    public function indexAction()
    {
        $this->view->products = $this->em->getRepository('Product')->fetchForHomepage();
    }
}

