<?php

/**
 * @Entity 
 * @Table(name="categories")
 */

class Category {
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
     * @OneToOne(targetEntity="Category")
     * @JoinColumn(name="parent_category",referencedColumnName="id")
     * 
     * @var integer
     */
    
    protected $parentCategory;
    
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getParentCategory() {
        return $this->parentCategory;
    }

    public function setParentCategory($parentCategory) {
        $this->parentCategory = $parentCategory;
    }


}