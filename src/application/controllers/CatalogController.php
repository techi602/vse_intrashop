<?php

class CatalogController extends Controller_Default
{
    public function init()
    {
        parent::init();
        
    }
    
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

        $product = $this->em->find('Product', $this->_getParam('id'));
        
        if (is_null($product)) {
            throw new InvalidArgumentException('Invalid Product ID');
        }
        
        $form = new Form_Product();
        $form->prepare();
        
        if ($this->_request->isPost()) {
            
            if ($this->_getParam('buttoncancel')) {
                $this->_helper->redirector->goto('index');
            }
            
            if ($this->_getParam('buttondelete')) {
                
                $this->addInfoMessage('Produkt ' . $product->getCode() . ' byl smazán');
                
                $this->em->remove($product);
                $this->em->flush();
                
                $this->_helper->redirector->goto('index');
            }
            
            if ($form->isValid($this->_request->getPost())) {

                $product->setName($form->getValue('name'));
                $product->setDescription($form->getValue('description'));
                $product->setVisible($form->getValue('visible'));
                $product->setPrice($form->getValue('price'));
                $product->setCredits($form->getValue('credits'));
                $this->em->persist($product);
                $this->em->flush();
                
                $this->addInfoMessage('Produkt ' . $product->getCode() . ' byl úspěšně aktualizován');

                if ($this->_getParam('buttondetail')) {
                    $this->_helper->redirector->goto('index', 'product', null, array('id' => $this->_getParam('id')));
                }

//                if ($this->_getParam('buttoncatalog')) {
                    $this->_helper->redirector->goto('index');
//                }
                
            }
        } else {

            $query = $this->em->createQuery("SELECT p FROM Product p WHERE p.id = " . (int)$this->_getParam('id'));
            $products = $query->getArrayResult();
            $form->setDefaults(reset($products));
        }
        
        $this->view->form = $form;
        
    }
}