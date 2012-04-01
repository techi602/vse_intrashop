<?php

/**
 * @Entity
 * @Table(name="users")
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"superior_employee" = "SuperiorEmployee", "employee" = "Employee"})
 */
class Employee
{
    /**
     * @var integer
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;
    
    /**
     * @var string
     * @Column(type="string")
     */ 
    protected $name;
    
    /**
     *
     * @var string
     * @Column(type="string")
     */
    
    protected $email;
    
    /**
     * @var DateTime
     * @Column(type="date",name="employed_since")
     */
    protected $employedSince;
    
    /**
     * @Column(type="integer")
     * @var integer
     */
    protected $balance;
    
    /**
     *
     * @var SuperiorEmployee
     * @OneToOne(targetEntity="SuperiorEmployee")
     * @JoinToColumn(name="superior_id",referencedColumnName="id")
     */
    
    protected $superiorEmployee;
    
    /**
     * OneToMany(targetEntity="Order", mappedBy="order")
     **/
    
    //private $orders;
    
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getEmployedSince() {
        return $this->employedSince;
    }

    public function setEmployedSince($employedSince) {
        $this->employedSince = $employedSince;
    }

    public function getBalance() {
        return $this->balance;
    }

    public function setBalance($balance) {
        $this->balance = $balance;
    }

    public function getOrders() {
        return $this->orders;
    }

    public function setOrders($orders) {
        $this->orders = $orders;
    }
    public function getSuperiorEmployee() {
        return $this->superiorEmployee;
    }

    public function setSuperiorEmployee($superiorEmployee) {
        $this->superiorEmployee = $superiorEmployee;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
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