<?php

/**
 * @Entity
 * @Table(name="order_statuses")
 */
class OrderStatus {
    
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
}
