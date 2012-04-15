<?php

class Form_Product extends Bootstrap_Form
{
    
    public function prepare()
    {
        $this->addDecorator(new Zend_Form_Decorator_Fieldset());
        $this->setLegend('Produkt');
        $this->setMethod(self::METHOD_POST);
        
        $this->addElement(new Zend_Form_Element_Text('name', array(
            'label' => 'NĂˇzev produktu',
            'required' => true
        )));
        
        $this->addElement($this->createElement('text', 'code', array(
            'label' => 'KĂłd produktu',
            'required' => true
        )));
        
        $this->addElement($this->createElement('checkbox', 'visible', array(
            'label' => 'AktivnĂ­',
            'required' => true
        )));
        
        $this->addElement($this->createElement('textarea', 'description', array(
            'label' => 'Popis',
            'cols' => 50,
            'rows' => 3
        )));
        
        $this->addElement($this->createElement('submit', 'button', array(
            'label' => 'Odeslat'
        )));
    }
}
        