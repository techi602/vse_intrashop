<?php

class Service_Order
{

    /** @var Doctrine\ORM\EntityManager */
    private $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Kontroluje, zda-li ma zamestnanec dostatecny pocet bodu
     * 
     * @param Employee $employee
     * @param Product $product
     * @param integer $quantity
     * @return boolean
     * @throws InvalidArgumentException 
     */
    public function hasEnoughCredits(Employee $employee, Product $product, $quantity)
    {
        if (!is_numeric($quantity) || $quantity < 1) {
            throw new InvalidArgumentException('Quantity must be > 0');
        }

        $balance = $employee->getBalance();

        $totalCredits = $product->getCredits() * $quantity;

        return $balance >= $totalCredits;
    }
    
    public function isAvailableOnStock(Order $order)
    {
        $service = new Service_ProductVariant($this->em);
        
        return $service->isAvailableOnStock($order->getProductVariant(), $order->getAmount());
    }
    
    public function getStock(Order $order)
    {
        $service = new Service_ProductVariant($this->em);
        
        return $service->getQuantity($order->getProductVariant());
    }
    

    /**
     * Vytvori obednavku
     * 
     * @param Employee $employee
     * @param ProductVariant $variant
     * @param integer $quantity
     * @param string $note
     * @throws InvalidArgumentException 
     */
    public function createOrder(Employee $employee, ProductVariant $variant, $quantity, $note = '')
    {
        if (!is_numeric($quantity) || $quantity < 1) {
            throw new InvalidArgumentException('Quantity must be > 0');
        }

        $this->em->getConnection()->beginTransaction();

        $credits = $variant->getProduct()->getCredits() * $quantity;

        $order = new Order();
        $order->setEmployee($employee);
        $order->setInserted(new DateTime());
        $order->setNote($note);
        $order->setProductVariant($variant);
        $order->setStatus($this->em->getRepository('OrderStatus')->findOneBy(array('code' => OrderStatus::STATUS_NEW)));
        $order->setCredits($credits);
        $order->setAmount($quantity);

        $this->em->persist($order);

        $balance = $employee->getBalance();
        $balance -= $credits;
        $employee->setBalance($balance);

        $this->em->persist($employee);
        $this->em->getConnection()->commit();
        $this->em->flush();
        
        return $order;
    }

    public function cancelOrder($orderId, $reason = null)
    {
        $order = $this->em->find('Order', $orderId);

        if (!$order->isCancellable()) {
            throw new \Exception("Objednavku nelze stornoat");
        }

        $employee = $order->getEmployee();

        if ($order->getStatus()->getCode() != OrderStatus::STATUS_NEW) {
            $qty =  $order->getProductVariant()->getQuantity();
            $qty +=  $order->getAmount();
            $order->getProductVariant()->setQuantity($qty);
        }
        
        $canceledOrderStatus = $this->em->getRepository('OrderStatus')->findOneBy(array('code' => OrderStatus::STATUS_STORNO));
        $orderCreditAmount = $order->getCredits();

        $this->em->getConnection()->beginTransaction();

        $order->setStatus($canceledOrderStatus);
        $order->setStatusChanged(new DateTime());
        $order->setStornoReason($reason);
        $this->em->persist($order);

        $employee->setBalance($employee->getBalance() + $orderCreditAmount);
        $this->em->persist($employee);

        $this->em->getConnection()->commit();
        $this->em->flush();
    }
    
    /**
     * 
     * @param Order $order
     */
    
    public function prepareOrder(Order $order)
    {
        $this->em->getConnection()->beginTransaction();
        
        $order->setStatus($this->em->getRepository('OrderStatus')->findOneBy(array('code' => OrderStatus::STATUS_PREPARED)));
        $order->setStatusChanged(new DateTime());
        
        $this->em->persist($order);
        
        $variant = $order->getProductVariant();
        $qty = $variant->getQuantity();
        $qty -= $order->getAmount();
        $variant->setQuantity($qty);
        
        $this->em->persist($variant);
        $this->em->getConnection()->commit();
        $this->em->flush();
    }

    /**
     * Potvrdi obednavku a 
     * 
     * @param Order $order 
     */
    public function confirmOrder(Order $order)
    {
        $this->em->getConnection()->beginTransaction();

        $order->setStatus($this->em->getRepository('OrderStatus')->findOneBy(array('code' => OrderStatus::STATUS_CONFIRMED)));
        $order->setStatusChanged(new DateTime());

        $this->em->persist($order);
/*
        $variant = $order->getProductVariant();
        $qty = $variant->getQuantity();
        $qty -= $order->getAmount();
        $variant->setQuantity($qty);
        $this->em->persist($variant);
*/
        
        $this->em->getConnection()->commit();
        $this->em->flush();
    }

    public function isAllowed($employeeId, $orderId)
    {
        $query = $this->em->createQuery("SELECT o FROM Order o JOIN o.employee e WHERE e.id = ?1 AND o.id = ?2");
        $query->setParameter(1, $employeeId);
        $query->setParameter(2, $orderId);
        $result = $query->getResult();

        return !!$result;
    }
}
