<?php

class OrdersController extends Controller_Default
{

    /** @var Service_Orders */
    private $ordersService;

    public function init()
    {
        parent::init();
        $this->ordersService = new Service_Orders($this->em);
        
        
    }

    public function indexAction()
    {
        if (!$this->loggedWarehouseKeeper) {
            throw new Zend_Acl_Exception("Access denied");
        }
        
        $orderList = $this->ordersService->getWarehouseKeeperOrderList();
        $this->view->list = $orderList;
        $this->view->warehouser = true;
    }

    public function employeeAction()
    {
        $orderList = $this->ordersService->getUserOrderList($this->loggedEmployee);
        $this->view->list = $orderList;

        $this->_helper->ViewRenderer->render('index');
    }

    public function detailAction()
    {

        $orderId = $this->getRequest()->getParam('id');

        //TODO: řešit jestli to je jeho nebo ne (tzn. bud je warehouse keeper, nebo je ta objednávka jeho)

        $this->view->order = $this->ordersService->getOrderInfo($orderId);
        $order = $this->em->find('Order', $orderId);
        if ($this->loggedWarehouseKeeper && $order->getStatus()->getCode() == OrderStatus::STATUS_NEW) {
            $this->view->displayConfirm = true;
        }
    }

    public function cancelAction()
    {
        //TODO: řešit jestli to je jeho nebo ne
        $orderId = $this->getRequest()->getParam('id');
        $order = $this->ordersService->getOrderInfo($orderId);


        if ($this->_getParam('cancel')) {
            $this->ordersService->cancelOrder($orderId, $this->_getParam('reason'));
            $this->addInfoMessage("Objednávka " . $order['customOrderId'] . ' byla stornována');
            $this->_helper->redirector->goto('detail', null, null, array('id' => $orderId));
        } if ($this->_getParam('back')) {
            $this->_helper->redirector->goto('detail', null, null, array('id' => $orderId));
        }

        $this->view->order = $order;
    }
    
    public function confirmAction()
    {
        $orderId = $this->getRequest()->getParam('id');
        
        $order = $this->em->find('Order', $orderId);
        $orderInfo = $this->ordersService->getOrderInfo($orderId);
        
        $service = new Service_Order($this->em);
        $service->confirmOrder($order);
        $this->addInfoMessage("Objednávka " . $orderInfo['customOrderId'] . ' byla potvrzena');
        
        $this->_helper->redirector->goto('detail', null, null, array('id' => $orderId));
    }

    public function editAction()
    {
        $id = $this->_getParam('id');

        if (empty($id)) {
            throw new InvalidArgumentException("Order ID is missing");
        }

        $order = $this->em->find('Order', $id);

        $this->view->headTitle('Objedánávka ' . $order->getOrderId());

        $this->view->order = $order;
    }

}
