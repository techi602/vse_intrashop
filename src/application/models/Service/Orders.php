<?php

class Service_Orders
{
    /** @var Doctrine\ORM\EntityManager */
    private $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function getUserOrderList($employeeId)
    {
        $query = $this->em->createQuery("
            SELECT o, p, s, v
            FROM Order o
            JOIN o.employee e
            JOIN o.productVariant v
            JOIN v.product p
            JOIN o.status s
            WHERE e.id = ?1
            ORDER BY o.id DESC
        ");
        $query->setParameter(1, $employeeId);

        $orders = array();

        foreach ($query->getResult() as $order) {
            $orders[] = array(
                'productName' => $order->getProductVariant()->getProduct()->getName(),
                'orderInserted' =>  $order->getInserted(),
                'orderCredits' => $order->getCredits(),
                'orderStatusName' => $order->getStatus()->getName(),
                'orderId' => $order->getId(),
				'orderCancellable' => $order->isCancellable()
            );
        }

        return $orders;
    }

    public function getWarehouseKeeperOrderList()
    {
        $query = $this->em->createQuery("
            SELECT o, p, s, v
            FROM Order o
            JOIN o.employee e
            JOIN o.productVariant v
            JOIN v.product p
            JOIN o.status s
            ORDER BY o.id DESC
        ");

        $orders = array();

        foreach ($query->getResult() as $order) {
            $orders[] = array(
                'productName' => $order->getProductVariant()->getProduct()->getName(),
                'orderInserted' =>  $order->getInserted(),
                'orderCredits' => $order->getCredits(),
                'orderStatusName' => $order->getStatus()->getName(),
                'orderId' => $order->getId(),
				'orderCancellable' => $order->isCancellable(),
                'orderEmployeeName' => $order->getEmployee()->getName()
            );
        }

        return $orders;
    }

	public function getOrderInfo($orderId) {
		$order = $this->em->find('Order', $orderId);

		return array(
			'productName' => $order->getProductVariant()->getProduct()->getName(),
			'productDescription' => $order->getProductVariant()->getProduct()->getDescription(),
			'orderInserted' => $order->getInserted(),
			'orderCredits' => $order->getCredits(),
			'orderStatusName' => $order->getStatus()->getName(),
			'orderId' => $order->getId(),
			'employeeName' => $order->getEmployee()->getName(),
			'orderAmount' => $order->getAmount(),
			'orderCancellable' => $order->isCancellable()
		);
		die();
	}

	public function cancelOrder($orderId) {
		$order = $this->em->find('Order', $orderId);

		if ($order->getStatus()->getCode() !== OrderStatus::STATUS_NEW) {
			throw new \Exception();
		}

		$employee = $order->getEmployee();
		$canceledOrderStatus = $this->em->getRepository('OrderStatus')->findOneBy(array('code' => OrderStatus::STATUS_STORNO));

		$orderCredits = $order->getCredits();
		$productVariantOrderedAmount = $order->getAmount();
		$productVariant = $order->getProductVariant();

		$employee->setBalance($employee->getBalance() + $orderCredits);
		$productVariant->setQuantity($productVariant->getQuantity() + $productVariantOrderedAmount);
		$order->setStatus($canceledOrderStatus);

		$this->em->beginTransaction();
		$this->em->persist($employee);
		$this->em->persist($productVariant);
		$this->em->persist($order);
		$this->em->commit();
	
		$this->em->flush();
	}
}
