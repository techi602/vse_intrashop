<?php

use Doctrine\ORM\EntityRepository;


/**
 * Description of ProductSize
 *
 * @author Techi
 */
class Repository_ProductSize extends EntityRepository
{
    public function fetchToCodebook()
    {
        $a = array();
        $result = $this->_em->createQuery("SELECT c.id, c.name FROM ProductSize c")->getArrayResult();
        
        foreach ($result as $r) {
            $a[$r['id']] = $r['name'];
        }
        
        return $a;
    }
 
}
