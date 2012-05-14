<?php

class OrdersController extends Controller_Default
{

    /** @var Service_Orders */
    private $ordersService;

    /** @var Service_Order */
    private $orderService;

    /** @var Service_Notification */
    private $notificationService;

    public function init()
    {
        parent::init();
        $this->ordersService = new Service_Orders($this->em);
        $this->orderService = new Service_Order($this->em);
        $this->notificationService = new Service_Notification($this->em);
        $this->view->statuses = $this->em->getRepository('OrderStatus')->findAll();
        $this->view->statusId = $this->_getParam('status');
        
    }

    public function indexAction()
    {
        $this->checkPermisssions();
        
        $title = 'Seznam objednávek';

        $orderList = $this->ordersService->getWarehouseKeeperOrderList($this->em->find('OrderStatus', (int)$this->_getParam('status')));
        $this->view->list = $orderList;
        $this->view->warehouser = true;
        $this->view->ask = false;
        
        $this->view->title = $title;
        $this->view->headTitle($title);
        
    }

    public function employeeAction()
    {
        $title = 'Objednávky zaměstnance';
        
        $orderList = $this->ordersService->getUserOrderList($this->loggedEmployee, $this->em->find('OrderStatus', (int)$this->_getParam('status')));
        $this->view->list = $orderList;
        $this->view->ask = true;
        
        $this->view->title = $title;
        $this->view->headTitle($title);

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
        if ($this->loggedWarehouseKeeper && $order->getStatus()->getCode() == OrderStatus::STATUS_PREPARED) {
            $this->view->displayConfirm = true;
        }
        
        if ($this->loggedWarehouseKeeper && $order->getStatus()->getCode() == OrderStatus::STATUS_NEW) {
            $this->view->displayPrepare = true;

            $this->view->enough = $this->orderService->isAvailableOnStock($order);
            $this->view->stock = $this->orderService->getStock($order);
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
            $reason = $this->_getParam('reason');
            $this->orderService->cancelOrder($orderId, $reason);
            $this->addInfoMessage("Objednávka " . $order['customOrderId'] . ' byla stornována');
            $this->notificationService->notifyOrderCancelled($orderId, $reason);
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
    
    public function prepareAction()
    {
        $orderId = $this->getRequest()->getParam('id');
    
        $order = $this->em->find('Order', $orderId);
        $orderInfo = $this->ordersService->getOrderInfo($orderId);
    
        $service = new Service_Order($this->em);
        $service->prepareOrder($order);
        $this->addInfoMessage("Objednávka " . $orderInfo['customOrderId'] . ' byla potvrzena k převzetí');
        $this->notificationService->notifyOrderReady($order);
    
        $this->_helper->redirector->goto('detail', null, null, array('id' => $orderId));
    }
    
    public function confirmAction()
    {
        $orderId = $this->getRequest()->getParam('id');
        
        $order = $this->em->find('Order', $orderId);
        $orderInfo = $this->ordersService->getOrderInfo($orderId);
        
        $service = new Service_Order($this->em);
        $service->confirmOrder($order);
        $this->addInfoMessage("Objednávka " . $orderInfo['customOrderId'] . ' byla potvrzena');
        $this->notificationService->notifyOrderConfirmed($order);
        
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
