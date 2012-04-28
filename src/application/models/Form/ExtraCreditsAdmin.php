<?php

class Form_ExtraCreditsAdmin extends Bootstrap_Form
{
    const MAX_CREDIT_AMOUNT_GIVEN = 50000;

    private $personnelOfficerBalance;

    public function __construct($personnelOfficerBalance) {
        parent::__construct();
        $this->personnelOfficerBalance = $personnelOfficerBalance;
    }

    public function prepare()
    {
        $this->addDecorator(new Zend_Form_Decorator_Fieldset());
        $this->setLegend('Přidělit body');
        $this->setMethod(self::METHOD_POST);

        $creditsAmountLessThanValidate = new Zend_Validate_LessThan(null);

        if ($this->personnelOfficerBalance <= self::MAX_CREDIT_AMOUNT_GIVEN) {
            $creditsAmountLessThanValidate->setMax($this->personnelOfficerBalance + 1);
            $creditsAmountLessThanValidate->setMessage("Překročena výše konta mimořádných bodů");
        }
        else {
            $creditsAmountLessThanValidate->setMax(self::MAX_CREDIT_AMOUNT_GIVEN + 1);
            $creditsAmountLessThanValidate->setMessage("Maximálně možno přidělit " . self::MAX_CREDIT_AMOUNT_GIVEN . " bodů");
        }

        $this->addElement(new Zend_Form_Element_Text('creditsAmount', array(
            'label' => 'Počet přidělovaných bodů',
            'required' => true,
            'validators' => array(
                new Zend_Validate_Int(),
                new Zend_Validate_GreaterThan(0),
                $creditsAmountLessThanValidate
            )
        )));

        $this->addElement($this->createElement('textarea', 'note', array(
            'label' => 'Důvod přidělení bodů',
            'class' => ''
        ))->setAttrib('rows', 4));

        $this->addElement($this->createElement('submit', 'button-detail', array(
            'label' => 'Přidělit',
            'class' => 'btn btn-primary'
        )));

        $this->addElement($this->createElement('submit', 'button-cancel', array(
            'label' => 'Zrušit',
            'class' => 'btn'
        )));
    }
}
