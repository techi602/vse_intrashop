<?php

class Form_Product extends Bootstrap_Form
{

    /**
     *
     * @var Product
     */
    private $product;

    public function setProduct(Product $product)
    {
        $this->product = $product;
    }

    public function prepare()
    {
        $product = $this->product;

        $this->addDecorator(new Zend_Form_Decorator_Fieldset());
        $this->setLegend('Produkt ' . $product->getName());
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

        $checkedCategories = array();

        if ($product) {
            foreach ($product->getCategories() as $category) {
                $checkedCategories[] = $category->getId();
            }
        }

        $this->addElement($this->createElement('MultiCheckbox', 'categories', array(
                    'label' => 'Kategorie',
                    'multiOptions' => $this->getCategoriesCodebook(),
                    'value' => $checkedCategories
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

        $this->addElement($this->createElement('checkbox', 'hasMultipleVariants', array(
                    'label' => 'Více variant',
                    'description' => 'Produkt má více variant'
                )));

        if ($product) {

            foreach ($product->getVariants() as $variant) {

                $variantId = $variant->getId();
                $group = array();

                $name = "variant{$variantId}_name";
                $this->addElement(new Zend_Form_Element_Text($name, array(
                            'label' => 'Název varianty',
                            'value' => $variant->getName(),
                            'required' => true
                        )));
                $group[] = $name;

                $name = "variant{$variantId}_quantity";
                $this->addElement(new Zend_Form_Element_Text($name, array(
                            'label' => 'Skladem',
                            'value' => $variant->getQuantity(),
                            'required' => true,
                            'validators' => array('Int')
                        )));
                $group[] = $name;

                $name = "variant{$variantId}_color";
                $this->addElement(new Zend_Form_Element_Select($name, array(
                            'label' => 'Barva',
                            'value' => $variant->getColor() ? $variant->getColor()->getId() : null,
                            'multiOptions' => array(' -- nevybráno -- ') + $this->getColorsCodebook(),
                        )));
                $group[] = $name;

                $name = "variant{$variantId}_size";
                $this->addElement(new Zend_Form_Element_Select($name, array(
                            'label' => 'Velikost',
                            'value' => $variant->getSize() ? $variant->getSize()->getId() : null,
                            'multiOptions' => array(' -- nevybráno -- ') + $this->getSizesCodebook(),
                        )));
                $group[] = $name;


                $this->addDisplayGroup($group, "Varianta$variantId", array('legend' => $variant->getName()));
            }
        }

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

        $this->addElement($this->createElement('submit', 'button-variant', array(
                    'label' => 'Uložit a přidat variantu',
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

    private function getColorsCodebook()
    {
        return EntityManager::getInstance()->getRepository('ProductColor')->fetchToCodebook();
    }

    private function getSizesCodebook()
    {
        return EntityManager::getInstance()->getRepository('ProductSize')->fetchToCodebook();
    }

    private function getCategoriesCodebook()
    {
        return EntityManager::getInstance()->getRepository('Category')->fetchToCodebook();
    }

}

