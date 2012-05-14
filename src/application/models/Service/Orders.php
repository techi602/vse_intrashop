<?php

class Service_Orders
{

    /** @var Doctrine\ORM\EntityManager */
    private $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function getUserOrderList(Employee $employee, OrderStatus $status = null)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select(array('o', 'p', 's', 'v', 'e'));
        $qb->from('Order', 'o');
        $qb->join('o.employee', 'e');
        $qb->join('o.productVariant', 'v');
        $qb->join('v.product', 'p');
        $qb->join('o.status', 's');
        $qb->where('e = ?1');
        $qb->orderBy('o.id', 'DESC');
        $qb->setParameter(1, $employee);
        
        if (!is_null($status)) {
            
            $qb->andWhere('s = ?2');
            $qb->setParameter(2, $status);
        }
        
        $query = $qb->getQuery();
        
/*        
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
        */
        //$query->setParameter(1, $employee->getId());

        $orders = array();

        foreach ($query->getResult() as $order) {
            $orders[] = array(
                'productName' => $order->getProductVariant()->getProduct()->getName(),
                'orderInserted' => $order->getInserted(),
                'orderCredits' => $order->getCredits(),
                'orderStatusName' => $order->getStatus()->getName(),
                'orderId' => $order->getId(),
                'orderCancellable' => $order->isCancellable()
            );
        }

        return $orders;
    }

    public function getWarehouseKeeperOrderList(OrderStatus $status = null)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select(array('o', 'p', 's', 'v', 'e'));
        $qb->from('Order', 'o');
        $qb->join('o.employee', 'e');
        $qb->join('o.productVariant', 'v');
        $qb->join('v.product', 'p');
        $qb->join('o.status', 's');
        $qb->orderBy('o.id', 'DESC');
        
        if (!is_null($status)) {
            $qb->andWhere('s = ?1');
            $qb->setParameter(1, $status);
        }
        
        $query = $qb->getQuery();
        /*
        $query = $this->em->createQuery("
            SELECT o, p, s, v
            FROM Order o
            JOIN o.employee e
            JOIN o.productVariant v
            JOIN v.product p
            JOIN o.status s

            ORDER BY o.id DESC        ");
*/
        $orders = array();

        foreach ($query->getResult() as $order) {
            $orders[] = array(
                'productName' => $order->getProductVariant()->getProduct()->getName(),
                'orderInserted' => $order->getInserted(),
                'orderCredits' => $order->getCredits(),
                'orderStatusName' => $order->getStatus()->getName(),
                'orderId' => $order->getId(),
                'orderCancellable' => $order->isCancellable(),
                'orderEmployeeName' => $order->getEmployee()->getName()
            );
        }

        return $orders;
    }

    public function getOrderInfo($orderId)
    {
        $order = $this->em->find('Order', $orderId);

        return array(
            'customOrderId' => $order->getOrderId(),
            'productName' => $order->getProductVariant()->getProduct()->getName(),
            'productDescription' => $order->getProductVariant()->getProduct()->getDescription(),
            'orderInserted' => $order->getInserted(),
            'statusChanged' => $order->getStatusChanged(),
            'orderCredits' => $order->getCredits(),
            'orderStatusName' => $order->getStatus()->getName(),
            'orderId' => $order->getId(),
            'employeeName' => $order->getEmployee()->getName(),
            'orderAmount' => $order->getAmount(),
            'orderCancellable' => $order->isCancellable(),
            'stornoReason' => $order->getStornoReason()
        );
    }
}
