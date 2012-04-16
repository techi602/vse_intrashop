<?php

class Form_Product extends Bootstrap_Form
{
    
    public function prepare()
    {
        $this->addDecorator(new Zend_Form_Decorator_Fieldset());
        $this->setLegend('Produkt');
        $this->setMethod(self::METHOD_POST);
        
        $this->addElement(new Zend_Form_Element_Text('name', array(
            'label' => 'NÄ‚Ë‡zev produktu',
            'required' => true
        )));
        
        $this->addElement($this->createElement('text', 'code', array(
            'label' => 'KÄ‚Ĺ‚d produktu',
            'required' => true
        )));
        
        $this->addElement($this->createElement('checkbox', 'visible', array(
            'label' => 'AktivnÄ‚Â­',
            'required' => true
        )));
        
        $this->addElement($this->createElement('textarea', 'description', array(
            'label' => 'Popis',
            'cols' => 50,
            'rows' => 3
        )));
        
        $this->addElement($this->createElement('submit', 'button-catalog', array(
            'label' => 'Uložit a přejít na výpis produktů',
            'class' => 'btn'
        )));
        
        $this->addElement($this->createElement('submit', 'button-detail', array(
            'label' => 'Uložit a přejít na detail',
            'class' => 'btn'
        )));
    }
}
        