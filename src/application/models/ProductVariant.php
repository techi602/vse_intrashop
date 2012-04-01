<?php

/**
 * @Entity
 * @Table(name="product_variants")
 */
class ProductVariant {
    
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
     * @ManyToOne(targetEntity="ProductColor")
     * @JoinColumn(name="color_id",referencedColumnName="id")
     * @var ProductColor
     */
    
    protected $color;
    
    /**
     * @ManyToOne(targetEntity="ProductSize")
     * @JoinColumn(name="size_id",referencedColumnName="id")
     * @var ProductSize
     */
    
    protected $size;
    
    /**
     * @ManyToOne(targetEntity="Product")
     * @JoinColumn(name="product_id",referencedColumnName="id",nullable=false)
     * @var Product
     */
    
    protected $product;

    /**
     * @ManyToOne(targetEntity="ProductAvailability")
     * @JoinColumn(name="availability_id",referencedColumnName="id",nullable=false)
     * @var ProductAvailability
     */
   
    protected $availability;
    
    /**
     * @Column(type="integer")
     * @var integer
     */
    
    protected $quantity;
}
