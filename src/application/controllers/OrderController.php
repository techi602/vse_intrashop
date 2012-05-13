<?php

class OrderController extends Controller_Default
{

    /**
     *
     * @var Service_Order
     */
    private $orderService;

    /** @var Service_Notification */
    private $notificationService;

    public function init()
    {
        parent::init();

        $this->orderService = new Service_Order($this->em);
        $this->notificationService = new Service_Notification();
    }

    public function indexAction()
    {
        $quantity = $this->_getParam('quantity');

        $variant = $this->em->find('ProductVariant', $this->_getParam('variant'));
        $product = $variant->getProduct();

        if ($this->_getParam('back') || $this->_getParam('cancel')) {
            $this->_helper->redirector->goto('index', 'product', null, array('id' => $variant->getProduct()->getId()));
            return;
        }

        if ($this->_getParam('confirm')) {
            $note = $this->_getParam('note', '');

            if (!$this->orderService->hasEnoughCredits(User::getLoggedUser(), $product, $quantity)) {
                $this->addErrorMessage('Nedostatek bodů k nákupu');
                $this->_helper->redirector->goto('failed');
                return;
            }

            $this->orderService->createOrder($this->loggedEmployee, $variant, $quantity, $note);
            $this->notificationService->notifyOrderSuccess($this->loggedEmployee, $variant, $quantity, $note);
            $this->_helper->redirector->goto('success');
            return;
        }

        $this->view->variant = $variant;
        $this->view->product = $product;
        $this->view->totalCredits = $product->getCredits() * $quantity;
        $this->view->quantity = $quantity;
        $this->view->enoughCredits = $this->orderService->hasEnoughCredits(User::getLoggedUser(), $product, $quantity);
    }

    public function successAction()
    {
        
    }

    public function failedAction()
    {
        
    }

}

