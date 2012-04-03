<?php

/**
 * @Entity
 * @Table(name="order_statuses")
 */
class OrderStatus {
    
    const STATUS_NEW = 'New';
    const STATUS_STORNO = 'Storno';
    const STATUS_CONFIRMED = 'Confirmed';
    
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
