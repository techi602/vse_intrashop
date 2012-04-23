<?php

class OrderController extends Controller_Default
{
    /**
     *
     * @var Service_Order
     */
    
    private $orderService;
    
    public function init()
    {
        parent::init();
        
        $this->orderService = new Service_Order($this->em);
    }

    public function indexAction()
    {
        $quantity = $this->_getParam('quantity');
        
        $variant = $this->em->find('ProductVariant', $this->_getParam('variant'));
        
        $product = $variant->getProduct();

        $this->view->variant = $variant;
        $this->view->product = $product;
        
        $this->view->enoughCredits = $this->orderService->hasEnoughCredits(User::getLoggedUser(), $product, $quantity);
    }
}

