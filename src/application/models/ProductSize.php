<?php

/**
 * @Entity
 * @Table(name="product_sizes")
 */
class ProductSize {
    
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

}
