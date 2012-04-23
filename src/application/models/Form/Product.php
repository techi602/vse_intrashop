<?php

class Form_Product extends Bootstrap_Form
{
    public function prepare(Product $product)
    {
        $this->addDecorator(new Zend_Form_Decorator_Fieldset());
        $this->setLegend('Produkt');
        $this->setMethod(self::METHOD_POST);
        $this->setEnctype(self::ENCTYPE_MULTIPART);
        
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
        
        
        $this->addElement($this->createElement('file', 'picture', array(
            'label' => 'Obrázek',
        )));
        
        $this->addElement($this->createElement('MultiCheckbox', 'categories', array(
            'label' => 'Kategorie',
            'multiOptions' => $this->getCategoriesCodebook()
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
        
        foreach ($product->getVariants() as $variantId => $variant) {
            $subform = new Zend_Form_SubForm();
            
            $subform->setLegend('Varianta');
            $subform->createElement('text', 'name');
            
            //$this->addSubForm($subform, 'variant' . $variantId);
        }
        
        
        $this->addElement($this->createElement('checkbox', 'has_multiple_variants', array(
            'label' => 'Více variant',
            'description' => 'Produkt má více variant'
        )));
        
        $this->addElement($this->createElement('submit', 'button-submit', array(
            'label' => 'Uložit',
            'class' => 'btn btn-primary'
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
    
    private function getCategoriesCodebook()
    {
          return EntityManager::getInstance()->getRepository('Category')->fetchToCodebook();
    }
}
        