<?php
/** @Entity **/
class Order
{
    /**
     *  @Id @GeneratedValue
     *  @Column(type="integer")
     */
    protected $id;
    
    /** @Column(type="date") **/
    protected $inserted;
    
    /** @Column(type="integer") **/
    protected $credits;
    
    
    /**
     * @ManyToOne(targetEntity="User", inversedBy="Order")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     **/
    
    private $user;
    
    /**
     * @ManyToOne(targetEntity="Product")
     * @JoinColumn(name="product_id", referencedColumnName="id")
     **/
    private $product;
}