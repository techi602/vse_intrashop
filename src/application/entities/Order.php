<?php
/**
 * @Entity
 * @Table(name="orders")
 */
class Order
{
    /**
     * @Id @GeneratedValue
     * @Column(type="integer")
     * @var integer
     */
    protected $id;
    
    /**
     * @Column(type="date")
     * @var DateTime
     */
    protected $inserted;
    
    /**
     * @Column(type="integer")
     * @var integer
     */
    protected $credits;
    
    /**
     * @ManyToOne(targetEntity="Employee", inversedBy="Order")
     * @JoinColumn(name="employee_id", referencedColumnName="id",nullable=false)
     * @var Employee
     */
    
    protected $employee;
    
    /**
     * @ManyToOne(targetEntity="ProductVariant")
     * @JoinColumn(name="variant_id", referencedColumnName="id",nullable=false)
     * @var ProductVariant
     */
    protected $productVariant;
    
    /**
     * @ManyToOne(targetEntity="OrderStatus")
     * @JoinColumn(name="status_id", referencedColumnName="id",nullable=false)
     * @var OrderStatus
     */
    
    protected $status;
 
    
    public function getInserted() {
        return $this->inserted;
    }

    public function setInserted($inserted) {
        $this->inserted = $inserted;
    }

    public function getCredits() {
        return $this->credits;
    }

    public function setCredits($credits) {
        $this->credits = $credits;
    }

    public function getEmployee() {
        return $this->employee;
    }

    public function setEmployee($employee) {
        $this->employee = $employee;
    }
    
    public function getProductVariant() {
        return $this->productVariant;
    }

    public function setProductVariant($productVariant) {
        $this->productVariant = $productVariant;
    }
    
    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
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