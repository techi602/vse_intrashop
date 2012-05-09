<?php

class OrdersController extends Controller_Default
{
    /** @var Service_Orders */
    private $ordersService;

    public function init() {
        parent::init();
        $this->ordersService = new Service_Orders($this->em);
    }

    public function indexAction()
    {
        $orderList = $this->ordersService->getUserOrderList($this->loggedEmployee->getId());
        $this->view->userOrderList = $orderList;
        $this->view->warehouseKeeperOrderList = null;
    }
    
    public function employeeAction()
    {
        $orderList = $this->ordersService->getUserOrderList($this->loggedEmployee->getId());
        $this->view->userOrderList = $orderList;
        $this->view->warehouseKeeperOrderList = null;
        
        $this->_helper->ViewRenderer->render('index');
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
