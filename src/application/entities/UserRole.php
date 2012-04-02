<?php

/**
 * @Entity
 * @Table(name="user_roles")
 */
class UserRole {
    
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
     * @Column(type="string")
     * @var string
     */
    protected $role;

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getRole() {
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
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
