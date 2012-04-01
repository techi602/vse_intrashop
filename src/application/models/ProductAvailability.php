<?php

/**
 * @Entity
 * @Table(name="product_availabilities")
 */
class ProductAvailability {
    
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     * @var integer
     */
    protected $id;
    
    /**
     * @Column(type="string")
     * @var string
     */
    protected $name;
    
    /**
     * @Column(type="integer")
     * @var type 
     */
    
    protected $days;
    
    /**
     * @Column(type="boolean")
     * @var bool
     */
    
    protected $available;

}
