<?php

class WarehouseController extends Controller_Default
{
    public function indexAction()
    {
        $query = $this->em->createQuery("SELECT p FROM Product p");
        $products = $query->getArrayResult();
        $this->view->products = $products;
    }
    
    public function editAction()
    {
        if (!$this->_hasParam('id')) {
            throw new InvalidArgumentException('Missing Product ID');
        }

        $em = EntityManager::getInstance();
        $product = $em->find('Product', $this->_getParam('id'));
        
        $form = new Form_Product();
        $form->prepare();
        
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {

                $product->setName($form->getValue('name'));
                $product->setDescription($form->getValue('description'));
                $em->persist($product);
                $em->flush();
            }
        } else {

            $query = $em->createQuery("SELECT p FROM Product p WHERE p.id = " . (int)$this->_getParam('id'));
            $products = $query->getArrayResult();
            $form->setDefaults(reset($products));
        }
        
        $this->view->form = $form;
    }

}


