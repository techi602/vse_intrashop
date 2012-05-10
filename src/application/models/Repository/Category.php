<?php

use Doctrine\ORM\EntityRepository;

/**
 * Description of Product
 *
 * @author Techi
 */
class Repository_Category extends EntityRepository
{

    public function fetchToCodebook()
    {
        $a = array();
        $result = $this->_em->createQuery("SELECT c.id, c.name FROM Category c")->getArrayResult();

        foreach ($result as $r) {
            $a[$r['id']] = $r['name'];
        }

        return $a;
    }

}
