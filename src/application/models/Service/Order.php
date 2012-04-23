<?php

class Service_Order
{
    /** @var Doctrine\ORM\EntityManager */
    private $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }
    
    public function hasEnoughCredits(Employee $employee, Product $product, $quantity)
    {
        if (!is_numeric($quantity) || $quantity < 1) {
            throw new InvalidArgumentException('Quantity must be > 0');
        }
        
        $balance = $employee->getBalance();
        
        $totalCredits = $product->getCredits() * $quantity;
        
        return $balance >= $totalCredits;
    }
    
    public function createOrder(Employee $employee, ProductVariant $variant, $quantity, $note = '')
    {
        if (!is_numeric($quantity) || $quantity < 1) {
            throw new InvalidArgumentException('Quantity must be > 0');
        }
        
        $this->em->beginTransaction();
        
        $credits = $variant->getProduct()->getCredits() * $quantity;
        
        $order = new Order();
        $order->setEmployee($employee);
        $order->setInserted(new DateTime());
        $order->setNote($note);
        $order->setProductVariant($variant);
        $order->setStatus($this->em->getRepository('OrderStatus')->findOneBy(array('code' => OrderStatus::STATUS_NEW)));
        $order->setCredits($credits);
        $order->setAmount($variant->getProduct()->getPrice() * $quantity);
        
        $this->em->persist($order);
        
        $balance = $employee->getBalance();
        $balance -= $credits;
        $employee->setBalance($balance);
        
        $this->em->persist($employee);
        
        $this->em->commit();
    }
}