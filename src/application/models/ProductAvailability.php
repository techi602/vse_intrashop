<?php

/**
 * @Entity
 * @Table(name="product_availabilities")
 */
class ProductAvailability {
    
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
     * @Column(type="integer")
     * @var type 
     */
    
    protected $days;
    
    /**
     * @Column(type="boolean")
     * @var bool
     */
    
    protected $available;

    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getDays() {
        return $this->days;
    }

    public function setDays($days) {
        $this->days = $days;
    }

    public function getAvailable() {
        return $this->available;
    }

    public function setAvailable($available) {
        $this->available = $available;
    }


}
