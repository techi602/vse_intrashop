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
        $this->em->commit();
        $this->em->flush();
    }

    public function cancelOrder($orderId, $reason = null)
    {
        $order = $this->em->find('Order', $orderId);

        if (!$order->isCancellable()) {
            throw new \Exception("Objednavku nelze stornoat");
        }

        $employee = $order->getEmployee();
        $canceledOrderStatus = $this->em->getRepository('OrderStatus')->findOneBy(array('code' => OrderStatus::STATUS_STORNO));
        $orderCreditAmount = $order->getCredits();

        $this->em->beginTransaction();

        $order->setStatus($canceledOrderStatus);
        $order->setStatusChanged(new DateTime());
        $order->setStornoReason($reason);
        $this->em->persist($order);

        $employee->setBalance($employee->getBalance() + $orderCreditAmount);
        $this->em->persist($employee);

        $this->em->commit();
        $this->em->flush();
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
        $order->setStatusChanged(new DateTime());

        $this->em->persist($order);

        $variant = $order->getProductVariant();
        $qty = $variant->getQuantity();
        $qty -= $order->getAmount();
        $variant->setQuantity($qty);

        $this->em->persist($variant);
        $this->em->commit();
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
