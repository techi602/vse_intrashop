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
     * Datum vytvoření objednávky
     * 
     * @Column(type="datetime")
     * @var DateTime
     */
    protected $inserted;
    
    /**
     * Datum změny stavu
     * 
     * @Column(type="datetime",nullable=true)
     * @var DateTime
     */
    protected $statusChanged;
    
    /**
     * @Column(type="integer")
     * @var integer
     */
    protected $credits;
    
    /**
     * @Column(type="integer")
     * @var type 
     */
    
    protected $amount;
    
    /**
     * @Column(type="string",nullable=true)
     * @var string
     */
    
    protected $note;
    
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
    public function getAmount() {
        return $this->amount;
    }

    public function setAmount($amount) {
        $this->amount = $amount;
    }

    public function getNote() {
        return $this->note;
    }

    public function setNote($note) {
        $this->note = $note;
    }
    
    public function getStatusChanged() {
        return $this->statusChanged;
    }

    public function setStatusChanged($statusChanged) {
        $this->statusChanged = $statusChanged;
    }

    public function getOrderId()
    {
        return str_pad($this->id, 5, '0', STR_PAD_LEFT) . '/' . $this->inserted->format('Y');
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