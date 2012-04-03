<?php

require_once 'SuperiorEmployee.php';

/**
 * @Entity
 */
class FinancialDirector extends SuperiorEmployee
{
    
    /**
     * Počet bodů pro přidělení personalistům
     * 
     * @Column(type="integer")
     * @var integer
     */
    
    protected $financialBalance;
}
