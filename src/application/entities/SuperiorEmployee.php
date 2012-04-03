<?php

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
}
