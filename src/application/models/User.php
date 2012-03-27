<?php

use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity **/
class User
{
    /**
     *  @var integer
     *  @ORM\Id
     *  @ORM\GeneratedValue
     *  @ORM\Column(type="integer")
     */
    protected $id;
    
    /**
     * @var string
     * @ORM\Column(type="string")
     */ 
    protected $name;
    
    /**
     * @var DateTime
     * @ORM\Column(type="date")
     */
    protected $employedSince;
    
    /** @ORM\Column(type="integer") **/
    protected $balance;
    
    /**
     * @ORM\OneToMany(targetEntity="Order", mappedBy="order")
     **/
    
    private $orders;
}