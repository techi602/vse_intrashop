<?php

class User
{
    /**
     *
     * @return Employee
     */
    
    public static function getLoggedUser()
    {
        $em = EntityManager::getInstance();
        return $em->find('Employee', 1);
    }
}