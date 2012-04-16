<?php

class IndexController extends Controller_Default
{

    public function init()
    {
        parent::init();
        
        
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
            'label' => 'NÄ‚Ë‡zev produktu',
            'required' => true
        )));
        
        $form->addElement($form->createElement('text', 'code', array(
            'label' => 'KÄ‚Ĺ‚d produktu',
            'required' => true
        )));
        
        $form->addElement($form->createElement('checkbox', 'visible', array(
            'label' => 'AktivnÄ‚Â­',
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

