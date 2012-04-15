<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        
        
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $em = Zend_Registry::get('EntityManager');
        
        $query = $em->createQuery("SELECT p FROM Product p WHERE p.visible = true");
        
        $products = $query->getResult();
        
        $this->view->products = $products;

        
        $query = $em->createQuery("SELECT p FROM Product p WHERE p.id = 1");
        $products = $query->getArrayResult();
        
        $form = new Bootstrap_Form();
        $form->addDecorator(new Zend_Form_Decorator_Fieldset());
        $form->setLegend('Produkt');
        
        $form->addElement($form->createElement('text', 'name', array(
            'label' => 'NĂˇzev produktu',
            'required' => true
        )));
        
        $form->addElement($form->createElement('text', 'code', array(
            'label' => 'KĂłd produktu',
            'required' => true
        )));
        
        $form->addElement($form->createElement('checkbox', 'visible', array(
            'label' => 'AktivnĂ­',
            'required' => true
        )));
        
        $form->addElement($form->createElement('textarea', 'description', array(
            'label' => 'Popis',
            'cols' => 50,
            'rows' => 3
        )));
        
        $form->addElement($form->createElement('submit', 'button', array(
            'label' => 'Odeslat'
        )));
        
        $form->loadDefaultDecorators();
        $form->setDefaults(reset($products));
        
        $this->view->form = $form;
    }

}

