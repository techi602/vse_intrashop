<?php

require_once 'Employee.php';

/**
 * @Entity
 */
class PersonnelOfficer extends Employee
{

    /**
     * Počet bodů přidělených finančním ředitelem
     *
     * @Column(type="integer")
     * @var integer
     */
    protected $personnelOfficerBalance;

    public function getPersonnelOfficerBalance()
    {
        return $this->personnelOfficerBalance;
    }

    public function setPersonnelOfficerBalance($creditAmount)
    {
        return $this->personnelOfficerBalance = $creditAmount;
    }

}
