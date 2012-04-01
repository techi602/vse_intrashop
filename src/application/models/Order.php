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
     * @ManyToOne(targetEntity="Product")
     * @JoinColumn(name="product_id", referencedColumnName="id",nullable=false)
     * @var Product
     */
    protected $product;
    
    /**
     * @ManyToOne(targetEntity="OrderStatus")
     * @JoinColumn(name="status_id", referencedColumnName="id",nullable=false)
     * @var OrderStatus
     */
    
    protected $status;
    
}