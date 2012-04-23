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
    
    public function confirmAction()
    {
        $quantity = $this->_getParam('quantity');
        
        $variant = $this->em->find('ProductVariant', $this->_getParam('variant'));
        
        $note = $this->_getParam('note', '');
        
        $product = $variant->getProduct();
        
        
        if (!$this->orderService->hasEnoughCredits(User::getLoggedUser(), $product, $quantity)) {
            $this->addErrorMessage('Nedostatek bodů k nákupu');
            $this->_helper->redirector->gotoAction('failed');
        }
        
        $this->orderService->createOrder(User::getLoggedUser(), $variant, $quantity, $note);

        $this->_helper->redirector->gotoAction('success');
    }
    
    public function successAction()
    {
        
    }
    
    public function failedAction()
    {
        
    }
}

