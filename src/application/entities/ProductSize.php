<?php

/**
 * @Entity(repositoryClass="Repository_ProductSize")
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

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    /**
     *
     * @return integer
     */

    public function getId()
    {
        return $this->id;
    }
    
    
    public function __toString() {
        return $this->getName();
    }
}
