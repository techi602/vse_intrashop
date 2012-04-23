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
        
        $this->em->beginTransaction();
        
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
        $this->em->flush();
        $this->em->commit();
    }
    
    /**
     * Stornuje obednavku a pricte body zpet na ucet zamestnance
     * 
     * @param Order $order 
     */
    
    public function stornoOrder(Order $order)
    {
        $this->em->beginTransaction();
        
        $order->setStatus($this->em->getRepository('OrderStatus')->findOneBy(array('code' => OrderStatus::STATUS_STORNO)));
        $this->em->persist($order);
        
        $employee = $order->getEmployee();
        $balance = $employee->getBalance();
        $balance += $order->getCredits();
        $employee->setBalance($balance);
        
        $this->em->persist($employee);
        $this->em->flush();
        $this->em->commit();
    }
    
    /**
     * Potvrdi obednavku a provede vyskladneni produktu na skladu
     * 
     * @param Order $order 
     */
    
    public function confirmOrder(Order $order)
    {
        $this->em->beginTransaction();
        
        $order->setStatus($this->em->getRepository('OrderStatus')->findOneBy(array('code' => OrderStatus::STATUS_CONFIRMED)));
        
        $this->em->persist($order);
        
        $variant = $order->getProductVariant();
        $qty = $variant->getQuantity();
        $qty -= $order->getAmount();
        $variant->setQuantity($qty);
        
        $this->em->persist($variant);
        $this->em->flush();
        $this->em->commit();
    }
}