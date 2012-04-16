<?php

class Form_Product extends Bootstrap_Form
{
    public function prepare()
    {
        $this->addDecorator(new Zend_Form_Decorator_Fieldset());
        $this->setLegend('Produkt');
        $this->setMethod(self::METHOD_POST);
        
        $this->addElement(new Zend_Form_Element_Text('name', array(
            'label' => 'Název produktu',
            'required' => true
        )));
        
        $this->addElement($this->createElement('text', 'code', array(
            'label' => 'Kód produktu',
            'required' => true
        )));
        
        $this->addElement($this->createElement('checkbox', 'visible', array(
            'label' => 'Aktivní',
            'description' => 'Povolen k nákupu'
        )));
        
        $this->addElement($this->createElement('textarea', 'description', array(
            'label' => 'Popis',
            'cols' => 50,
            'rows' => 3
        )));
        
        $this->addElement($this->createElement('text', 'price', array(
            'label' => 'Cena',
            'required' => true,
            'validators' => array(new Zend_Validate_Int(), new Zend_Validate_GreaterThan(0))
        )));
        
        $this->addElement($this->createElement('text', 'credits', array(
            'label' => 'Body',
            'required' => true,
            'validators' => array(new Zend_Validate_Int(), new Zend_Validate_GreaterThan(0))
        )));
        
        $this->addElement($this->createElement('submit', 'button-catalog', array(
            'label' => 'Uložit a přejít na výpis produktů',
            'class' => 'btn btn-primary'
        )));
        
        
        $this->addElement($this->createElement('submit', 'button-detail', array(
            'label' => 'Uložit a přejít na detail',
            'class' => 'btn btn-primary'
        )));

        $this->addElement($this->createElement('submit', 'button-delete', array(
            'label' => 'Smazat',
            'class' => 'btn btn-danger',
            'onclick' => "return confirm('Opravdu smazat?');"
        )));
        
        $this->addElement($this->createElement('submit', 'button-cancel', array(
            'label' => 'Zrušit',
            'class' => 'btn'
        )));
    }
}
        