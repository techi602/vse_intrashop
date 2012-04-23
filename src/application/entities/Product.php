<?php

/**
 * @Entity(repositoryClass="Repository_Product")
 * @Table(name="products")
 */
class Product {

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
     * @Column(type="text",nullable=true)
     * @var string
     */
    protected $description;

    /**
     * @Column(type="string",nullable=true)
     * @var string
     */
    protected $picture;

    /**
     * JednoznaÄŤnĂˇ identifikace produktu
     * 
     * @Column(type="string",unique=true)
     * @var string
     */
    protected $code;

    
    /**
     * @Column(type="float")
     * @var float
     */
    protected $price;
    
    /**
     * Zobrazit pro nĂˇkup
     * 
     * @Column(type="boolean")
     * @var boolean
     */
    
    protected $visible;

    /**
     * @Column(type="float")
     * @var float
     */
    protected $credits;
    
    /**
     * @OneToMany(targetEntity="ProductVariant", mappedBy="product")
     * @var array
     */
    
    protected $variants;

    /**
     * Vice variant produktu
     * 
     * @Column(type="boolean",name="has_multiple_variants")
     * @var boolean
     */

    protected $hasMultipleVariants;
    
    /**
     * @var array
     * @ManyToMany(targetEntity="Category")
     * @JoinTable(name="products_categories",
     *      joinColumns={@JoinColumn(name="product_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="category_id", referencedColumnName="id")}
     *      )
     * */
    protected $categories;

    public function __construct() {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->variants = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getCategories() {
        return $this->categories;
    }

    public function setCategories($categories) {
        $this->categories = $categories;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getPicture() {
        if (empty($this->picture)) {
            return 'http://www.moviespad.com/photos/lars-and-the-real-girl-2-d9a2f.jpeg';
        } else {
            return $this->picture;
        }
    }

    public function setPicture($picture) {
        $this->picture = $picture;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function getCredits() {
        return $this->credits;
    }

    public function setCredits($credits) {
        $this->credits = $credits;
    }
    
    public function getVisible() {
        return $this->visible;
    }

    public function setVisible($visible) {
        $this->visible = $visible;
    }
    public function getCode() {
        return $this->code;
    }

    public function setCode($code) {
        $this->code = $code;
    }
    
    public function getVariants()
    {
        return $this->variants;
    }

    public function getMultipleVariants() {
        return $this->hasMultipleVariants;
    }

    public function setMultipleVariants($hasMultipleVariants) {
        $this->hasMultipleVariants = $hasMultipleVariants;
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