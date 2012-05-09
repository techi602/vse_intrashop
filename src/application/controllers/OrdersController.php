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

    public function employeeAction()
    {
        $orderList = $this->ordersService->getUserOrderList($this->loggedEmployee->getId());
        $this->view->userOrderList = $orderList;
        $this->view->warehouseKeeperOrderList = null;

        $this->_helper->ViewRenderer->render('index');
    }

	public function detailAction() {

        $orderId = $this->getRequest()->getParam('id');

        //TODO: řešit jestli to je jeho nebo ne (tzn. bud je warehouse keeper, nebo je ta objednávka jeho)

        $this->view->order = $this->ordersService->getOrderInfo($orderId);
        $this->view->warehouseKeeperView = !!$this->loggedWarehouseKeeper;
    }

    public function cancelAction() {
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
