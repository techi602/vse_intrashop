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
}