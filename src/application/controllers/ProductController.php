<?php

/**
 * Zobrazeni detailu produktu
 * 
 * @author Techi
 *
 */
class ProductController extends Controller_Default
{

    public function indexAction()
    {
        if (!$this->_hasParam('id')) {
            throw new InvalidArgumentException('Product ID is missing');
        }

        $product = $this->em->find("Product", $this->_getParam('id'));

        $service = new Service_ProductVariant($this->em);
        
        $qty = array();
        
        foreach ($product->getVariants() as $variant) {
            $qty[$variant->getId()] = $service->getQuantity($variant);
        }
        
        $this->view->qty = $qty;
        $this->view->product = $product;
    }

}

