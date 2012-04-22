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

}
