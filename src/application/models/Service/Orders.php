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
        ");
        $query->setParameter(1, $employeeId);

        $orders = array();

        foreach ($query->getArrayResult() as $order) {
            $orders[] = array(
                'id' => $order['id'],
                'productName' => $order['productVariant']['product']['name'],
                'orderInserted' => $order['inserted'],
                'orderCredits' => $order['credits'],
                'orderStatusName' => $order['status']['name']
            );
        }

        return $orders;
    }
}
