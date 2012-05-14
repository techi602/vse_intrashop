<?php

class WarehouseController extends Controller_Default
{

    public function indexAction()
    {
        $title = "Katalog produktů";
        
        $query = $this->em->createQuery("SELECT p FROM Product p");
        $products = $query->getArrayResult();
        $this->view->products = $products;
        
        $this->view->title = $title;
        $this->view->headTitle($title);
        
    }
    
    public function listAction()
    {
        $service = new Service_Warehouse($this->em);
        $variantService = new Service_ProductVariant($this->em);
        
        $warehouse = $service->fetchProductVariantsAvailability();
        
        $qty = array();
        foreach ($warehouse as $variant) {
            $qty[$variant->getId()] = $variantService->getQuantity($variant);
        }
        
        $this->view->qty = $qty;
        $this->view->warehouse = $warehouse;
        
        $title = 'Dostupnost';
        
        $this->view->title = $title;
        $this->view->headTitle($title);
    }

    public function editAction()
    {
        if ($this->_hasParam('id')) {
            $product = $this->em->find('Product', $this->_getParam('id'));

            if (is_null($product)) {
                throw new InvalidArgumentException('Invalid Product ID');
            }
        } else {
            $product = new Product();
        }

        $form = new Form_Product();
        $form->setProduct($product);
        $form->prepare();

        if ($this->_request->isPost()) {
            if ($this->_getParam('buttoncancel')) {
                $this->_helper->redirector->goto('index');
            }

            if ($this->_getParam('buttondelete')) {
                $this->addInfoMessage('Produkt ' . $product->getCode() . ' byl smazan');

                $this->em->remove($product);
                $this->em->flush();

                $this->_helper->redirector->goto('index');
            }

            if ($form->isValid($this->_request->getPost())) {

                $product->setName($form->getValue('name'));
                $product->setCode($form->getValue('code'));
                $product->setDescription($form->getValue('description'));
                $product->setVisible($form->getValue('visible'));
                $product->setPrice($form->getValue('price'));
                $product->setCredits($form->getValue('credits'));
                $product->setMultipleVariants($form->getValue('hasMultipleVariants'));

                $categories = array();
                foreach ($form->getValue('categories') as $categoryId) {
                    $categories[] = $this->em->find('Category', $categoryId);
                }
                $product->setCategories($categories);

                if (isset($_FILES['picture'])) {
                    if ($_FILES['picture']['tmp_name']) {
                        $product->setPicture(base64_encode(file_get_contents($_FILES['picture']['tmp_name'])));
                    }
                }

                foreach ($product->getVariants() as $variant) {
                    $variantId = $variant->getId();
                    $variant->setName($form->getValue("variant{$variantId}_name"));
                    $variant->setQuantity($form->getValue("variant{$variantId}_quantity"));
                    $variant->setColor($this->em->find('ProductColor', $form->getValue("variant{$variantId}_color")));
                    $variant->setSize($this->em->find('ProductSize', $form->getValue("variant{$variantId}_size")));

                    $this->em->persist($variant);
                }

                if ($this->_getParam('buttonvariant')) {
                    $variant = new ProductVariant();
                    $variant->setName('Varianta #' . (count($product->getVariants()) + 1));
                    $variant->setQuantity(0);
                    $variant->setProduct($product);
                    $this->em->persist($variant);
                } else if (count($product->getVariants()) == 0) {
                    $variant = new ProductVariant();
                    $variant->setName('Výchozí varianta');
                    $variant->setQuantity(0);
                    $variant->setProduct($product);
                    $this->em->persist($variant);
                }

                $this->em->persist($product);
                $this->em->flush();

                $this->addInfoMessage('Produkt ' . $product->getCode() . ' polozka zbozi ulozena');

                if ($this->_getParam('buttondetail')) {
                    $this->_helper->redirector->goto('index', 'product', null, array('id' => $product->getId()));
                }

                if ($this->_getParam('buttoncatalog')) {
                    $this->_helper->redirector->goto('index');
                }

                $this->_helper->redirector->goto('edit', null, null, array('id' => $product->getId()));
            }
        } else if ($this->_hasParam('id')) {

            $query = $this->em->createQuery("SELECT p FROM Product p WHERE p.id = " . (int) $this->_getParam('id'));
            $products = $query->getArrayResult();
            $defaults = reset($products);
            $form->setDefaults($defaults);
        }

        $this->view->form = $form;
        $this->view->product = $product;
        $this->view->headTitle($title);
        $this->view->title = $product->getName() ? $product->getName() : 'Nový produkt';
    }

}

