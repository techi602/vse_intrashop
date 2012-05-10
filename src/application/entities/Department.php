<?php

/**
 * @Entity
 * @Table(name="departments")
 */
class Department
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
     * @ManyToOne(targetEntity="Employee")
     * @JoinColumn(name="employee_id",referencedColumnName="id",nullable=false)
     * @var Employee
     */
    protected $boss;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getBoss()
    {
        return $this->boss;
    }

    public function setBoss(Employee $boss)
    {
        $this->boss = $boss;
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
