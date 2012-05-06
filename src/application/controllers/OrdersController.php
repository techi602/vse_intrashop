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
        if ($this->loggedWarehouseKeeper) {
            $orderList = $this->ordersService->getWarehouseKeeperOrderList();
            $this->view->warehouseKeeperOrderList = $orderList;
            $this->view->userOrderList = null;
        }
        else {
            $orderList = $this->ordersService->getUserOrderList($this->loggedEmployee->getId());
            $this->view->userOrderList = $orderList;
            $this->view->warehouseKeeperOrderList = null;
        }
    }

	public function detailAction() {
		//TODO: řešit jestli to je jeho nebo ne (tzn. bud je warehouse keeper, nebo je ta objednávka jeho)
		$orderId = $this->getRequest()->getParam('id');
		$this->view->order = $this->ordersService->getOrderInfo($orderId);
        $this->view->warehouseKeeperView = !!$this->loggedWarehouseKeeper;
	}


	public function cancelAction() {
		//TODO: řešit jestli to je jeho nebo ne
		$orderId = $this->getRequest()->getParam('id');
		$this->ordersService->cancelOrder($orderId);
		$this->_helper->redirector->goto('index');
	}

    public function addCancelNote() {
        //TODO
    }
}
