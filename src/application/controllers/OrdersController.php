<?php

class OrdersController extends Controller_Default
{

    /** @var Service_Orders */
    private $ordersService;

    /** @var Service_Order */
    private $orderService;

    public function init()
    {
        parent::init();
        $this->ordersService = new Service_Orders($this->em);
        $this->orderService = new Service_Order($this->em);
    }

    public function indexAction()
    {
        $this->checkPermisssions();

        $orderList = $this->ordersService->getWarehouseKeeperOrderList();
        $this->view->list = $orderList;
        $this->view->warehouser = true;
        $this->view->ask = false;
    }

    public function employeeAction()
    {
        $orderList = $this->ordersService->getUserOrderList($this->loggedEmployee);
        $this->view->list = $orderList;
        $this->view->ask = true;

        $this->_helper->ViewRenderer->render('index');
    }

    public function detailAction()
    {
        $orderId = $this->getRequest()->getParam('id');
        $this->checkPermisssions($orderId);

        $this->view->ask = $this->getRequest()->getParam('employee');

        //TODO: řešit jestli to je jeho nebo ne (tzn. bud je warehouse keeper, nebo je ta objednávka jeho)

        $this->view->order = $this->ordersService->getOrderInfo($orderId);
        $order = $this->em->find('Order', $orderId);
        if ($this->loggedWarehouseKeeper && $order->getStatus()->getCode() == OrderStatus::STATUS_NEW) {
            $this->view->displayConfirm = true;
        }
    }

    public function cancelAction()
    {
        $orderId = $this->getRequest()->getParam('id');
        $order = $this->ordersService->getOrderInfo($orderId);
        $this->checkPermisssions($orderId);

        $asEmployee = !!$this->_getParam('employee');
        $redirect = false;

        if ($asEmployee || $this->_getParam('cancel')) {
            $this->orderService->cancelOrder($orderId, $this->_getParam('reason'));
            $this->addInfoMessage("Objednávka " . $order['customOrderId'] . ' byla stornována');
            $redirect = true;
        } if ($this->_getParam('back')) {
            $redirect = true;
        }

        if ($redirect) {
            if ($asEmployee) {
                $this->_helper->redirector->goto('employee', null, null, array());
            }
            else {
                $this->_helper->redirector->goto('index', null, null, array());
            }
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

    private function checkPermisssions($orderId = null) {
        if ($this->loggedWarehouseKeeper) {
            $allowed = true;
        }
        else {
            $allowed = $this->orderService->isAllowed($this->loggedEmployee->getId(), $orderId);
        }

        if (!$allowed) {
            throw new Exception('Not allowed');
        }
    }
}
