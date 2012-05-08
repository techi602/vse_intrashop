<?php

require_once 'Employee.php';

/**
 * @Entity
 */
class SuperiorEmployee extends Employee
{

    /**
     * Počet bodů pro přidělení zaměstnanců
     *
     * @Column(type="integer")
     * @var integer
     */

    protected $superiorBalance;

    public function getSuperiorBalance() {
        return $this->superiorBalance;
    }

    public function setSuperiorBalance($creditsAmount) {
        $this->superiorBalance = $creditsAmount;
    }
}
