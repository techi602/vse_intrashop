<?php

class Service_ProductVariant
{

    /** @var Doctrine\ORM\EntityManager */
    private $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }
    
    /**
     * Vrati dostupne mnozstvi na sklade
     * 
     * @param ProductVariant $variant
     * @return integer
     */
    
    public function getQuantity(ProductVariant $variant)
    {
        $status = $this->em->getRepository('OrderStatus')->findOneBy(array('code' => OrderStatus::STATUS_NEW));
        
        $query = $this->em->createQuery("
            SELECT SUM(o.amount)
            FROM Order o
            JOIN o.status s
            JOIN o.productVariant p
            WHERE s.id = ?1 AND p.id = ?2
            ORDER BY o.id DESC
        ");
        $query->setParameter(1, $status->getId());
        $query->setParameter(2, $variant->getId());
        
        $qty = $query->getSingleScalarResult();

        return $variant->getQuantity() - $qty;
    }
}