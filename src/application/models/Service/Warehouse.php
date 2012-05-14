<?php

class Service_Warehouse
{

    /** @var Doctrine\ORM\EntityManager */
    private $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function fetchProductVariantsAvailability()
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select(array('p', 'v'));
        $qb->from('ProductVariant', 'v');
        $qb->join('v.product', 'p');
        
        $query = $qb->getQuery();
        
        return $query->getResult();
    }
}
