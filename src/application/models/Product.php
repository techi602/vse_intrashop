<?php

/**
 * @Entity
 * @Table(name="products")
 */
class Product
{
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
     * @Column(type="string",nullable=true)
     * @var string
     */
    
    protected $description;
    
    /**
     * @Column(type="string")
     * @var string
     */
    
    protected $picture;
    
    /**
     * @Column(type="float")
     * @var float
     */
    
    protected $price;

    /**
     * @Column(type="float")
     * @var float
     */

    protected $credits;
    
}