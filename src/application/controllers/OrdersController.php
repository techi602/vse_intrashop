<?php

class OrdersController extends Controller_Default
{
    /** @var Service_Orders */
    private $ordersService;

    public function init() {
        parent::init();
        $this->ordersService = new Service_Orders(Zend_Registry::get('EntityManager'));
    }

    public function indexAction()
    {
        $orderList = $this->ordersService->getUserOrderList($this->loggedUser->getId());
        $this->view->userOrderList = $orderList;
        $this->view->warehouseKeeperOrderList = null;
    }

}
