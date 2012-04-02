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
     * Prihhlašovací uživatelské jméno
     * 
     * @var string
     * @Column(type="string")
     */ 

    protected $username;
    
    /**
     * Je zaměstnán = může objendávat
     * 
     * @Column(type="boolean")
     * @var boolean
     */
    
    protected $employed;
    
    /**
     *
     * @var string
     * @Column(type="string")
     */
    
    protected $email;
    
    /**
     * Datum nástupu
     * 
     * @var DateTime
     * @Column(type="date",name="employed_since")
     */
    protected $employedSince;
    
    /**
     * Počet kreditů na účtě
     * 
     * @Column(type="integer")
     * @var integer
     */
    protected $balance;
    
    /**
     * Datum propouštění
     * 
     * @Column(type="date",nullable=true)
     * @var DateTime 
     */
    
    protected $dismissal;
    
    /**
     * Nadřízený zaměstnanec
     * 
     * @var SuperiorEmployee
     * @OneToOne(targetEntity="SuperiorEmployee")
     * @JoinToColumn(name="superior_id",referencedColumnName="id")
     */
    
    protected $superiorEmployee;
    
    /**
     * @var array
     * @ManyToMany(targetEntity="UserRole")
     * @JoinTable(name="employees_roles",
     *      joinColumns={@JoinColumn(name="employee_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="role_id", referencedColumnName="id")}
     *      )
     */
    protected $roles;

    public function __construct() {
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getEmployed() {
        return $this->employed;
    }

    public function setEmployed($employed) {
        $this->employed = $employed;
    }
    public function getDismissal() {
        return $this->dismissal;
    }

    public function setDismissal($dismissal) {
        $this->dismissal = $dismissal;
    }

    public function getRoles() {
        return $this->roles;
    }

    public function setRoles($roles) {
        $this->roles = $roles;
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