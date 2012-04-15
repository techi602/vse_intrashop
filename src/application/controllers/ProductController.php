<?php

/**
 * Zobrazeni detailu produktu
 * 
 * @author Techi
 *
 */

class ProductController extends Zend_Controller_Action
{
    public function indexAction()
    {
        if (!$this->_hasParam('id')) {
            throw new InvalidArgumentException('Product ID is missing');
        }

        $em = EntityManager::getInstance();
        
        //$query = $em->createQuery("SELECT p FROM Product p JOIN p.variants pv WHERE p.id = 1");
        //$product = $query->getSingleResult(1);
        
        $product = $em->find("Product", $this->_getParam('id'));
        
        $variants = $product->getVariants();
        
        foreach ($variants as $variant) {
            echo $variant->getName();
        }
        
        $this->view->product = $product;
    }

}



