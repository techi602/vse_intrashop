<?php

/**
 * @Entity
 * @Table(name="order_statuses")
 */
class OrderStatus
{

    const STATUS_NEW = 'New';
    const STATUS_STORNO = 'Storno';
    const STATUS_CONFIRMED = 'Confirmed';
    const STATUS_PREPARED = 'Prepared';

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
    protected $code;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $name;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
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
