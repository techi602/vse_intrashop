<?php

use Doctrine\ORM\EntityRepository;

/**
 * Description of ProductColor
 *
 * @author Techi
 */
class Repository_ProductColor extends EntityRepository
{

    public function fetchToCodebook()
    {
        $a = array();
        $result = $this->_em->createQuery("SELECT c.id, c.name FROM ProductColor c")->getArrayResult();

        foreach ($result as $r) {
            $a[$r['id']] = $r['name'];
        }

        return $a;
    }

}
