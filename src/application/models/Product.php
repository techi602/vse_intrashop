<?php

use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity **/
class Product
{
    /**
     *  @Id @GeneratedValue
     *  @Column(type="integer")
     */
    protected $id;
    
    /** @Column(type="string") **/
    protected $name;
}