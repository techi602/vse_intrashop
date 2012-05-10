<?php

/**
 * @Entity(repositoryClass="Repository_Category")
 * @Table(name="categories")
 */
class Category
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
     * @OneToOne(targetEntity="Category")
     * @JoinColumn(name="parent_category",referencedColumnName="id")
     * 
     * @var integer
     */
    protected $parentCategory;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getParentCategory()
    {
        return $this->parentCategory;
    }

    public function hasParentCategory()
    {
        return !is_null($this->parentCategory);
    }

    public function setParentCategory($parentCategory)
    {
        $this->parentCategory = $parentCategory;
    }

    /**
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->getName();
    }

}