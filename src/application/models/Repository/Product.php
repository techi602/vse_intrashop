<?php

use Doctrine\ORM\EntityRepository;


/**
 * Description of Product
 *
 * @author Techi
 */
class Repository_Product extends EntityRepository
{
    public function fetchForHomepage()
    {
        return $this->_em->createQuery("SELECT p FROM Product p WHERE p.visible = true")->getResult();
    }
 
}
