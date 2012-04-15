<?php

class EntityManager
{
    /**
     * @return \Doctrine\ORM\EntityManager
     */
    
    public static function getInstance()
    {
        return Zend_Registry::get("EntityManager");
    }
}