<?php

/**
 * @Entity
 * @Table(name="users")
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"superior_employee" = "SuperiorEmployee", "employee" = "Employee", "warehouse_keeper" = "WarehouseKeeper", "personnel_officer" = "PersonnelOfficer"})
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
     * @Column(type="string",unique=true)
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
     * @Column(type="string",unique=true)
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
     * Počet bodů na účtě
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
     * @OneToMany(targetEntity="Order", mappedBy="order")
     * @var array
     * */
    protected $orders;

    /**
     * @ManyToOne(targetEntity="Department")
     * @JoinColumn(name="department_id", referencedColumnName="id",nullable=true)
     * @var Department
     */
    protected $department;

    /**
     * @var array
     * @ManyToMany(targetEntity="UserRole")
     * @JoinTable(name="employee_roles",
     *      joinColumns={@JoinColumn(name="employee_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="role_id", referencedColumnName="id")}
     *      )
     * */
    protected $roles;

    public function __construct()
    {
        $this->orders = new \Doctrine\Common\Collections\ArrayCollection();
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getDepartment()
    {
        return $this->department;
    }

    public function setDepartment($department)
    {
        $this->department = $department;
    }

    public function getEmployedSince()
    {
        return $this->employedSince;
    }

    public function setEmployedSince($employedSince)
    {
        $this->employedSince = $employedSince;
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function setBalance($balance)
    {
        $this->balance = $balance;
    }

    public function getOrders()
    {
        return $this->orders;
    }

    public function setOrders($orders)
    {
        $this->orders = $orders;
    }

    public function getSuperiorEmployee()
    {
        return $this->superiorEmployee;
    }

    public function setSuperiorEmployee($superiorEmployee)
    {
        $this->superiorEmployee = $superiorEmployee;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getEmployed()
    {
        return $this->employed;
    }

    public function setEmployed($employed)
    {
        $this->employed = $employed;
    }

    public function getDismissal()
    {
        return $this->dismissal;
    }

    public function setDismissal($dismissal)
    {
        $this->dismissal = $dismissal;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    /**
     *
     * @param string $role
     * @return boolean 
     */
    public function hasRole($role)
    {
        foreach ($this->roles as $xrole) {
            if ($xrole->getRole() == $role) {
                return true;
            }
        }

        return false;
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