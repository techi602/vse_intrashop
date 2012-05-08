<?php

class Form_CreditsAdmin extends Bootstrap_Form
{
    const MAX_CREDIT_AMOUNT_GIVEN = 50000;

    private $accountBalance;

    public function __construct($accountBalance) {
        parent::__construct();
        $this->accountBalance = $accountBalance;
    }

    public function prepare()
    {
        $this->addDecorator(new Zend_Form_Decorator_Fieldset());
        $this->setLegend('Přidělit body');
        $this->setMethod(self::METHOD_POST);

        $creditsAmountLessThanValidate = new Zend_Validate_LessThan(null);

        if ($this->accountBalance <= self::MAX_CREDIT_AMOUNT_GIVEN) {
            $creditsAmountLessThanValidate->setMax($this->accountBalance + 1);
            $creditsAmountLessThanValidate->setMessage("Překročena výše konta");
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

		$this->addElement($this->createElement('hidden', 'hidden-confirm', array()));
    }
}
