<?php

/**
 * @Entity
 * @Table(name="product_variants")
 */
class ProductVariant
{

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     * @var integer
     */
    protected $id;

    /**
     * @Column(type="string",nullable=true)
     * @var string
     */
    protected $name;

    /**
     * @Column(type="text",nullable=true)
     * @var string
     */
    protected $picture;

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
     * @ManyToOne(targetEntity="Product",inversedBy="variants")
     * @JoinColumn(name="product_id",referencedColumnName="id",nullable=false)
     * @var Product
     */
    protected $product;

    /**
     * @ManyToOne(targetEntity="ProductAvailability")
     * @JoinColumn(name="availability_id",referencedColumnName="id",nullable=true)
     * @var ProductAvailability
     */
    protected $availability;

    /**
     * @Column(type="integer")
     * @var integer
     */
    protected $quantity;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function setColor($color)
    {
        $this->color = $color;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setSize($size)
    {
        $this->size = $size;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function setProduct($product)
    {
        $this->product = $product;
    }

    public function getAvailability()
    {
        return $this->availability;
    }

    public function setAvailability($availability)
    {
        $this->availability = $availability;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function getPicture()
    {
        if (is_null($this->picture)) {
            return $this->getProduct()->getPicture();
        } else {
            return $this->picture;
        }
    }

    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    /**
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

}
