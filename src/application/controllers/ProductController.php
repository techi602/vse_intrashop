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

        $this->view->product = $product;
    }

}

