<?php

class User
{

    private function __construct()
    {
        
    }

    /**
     *
     * @return Employee
     */
    public static function getLoggedUser($role = null)
    {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            throw new User_Exception("No user logged in");
        }

        $em = EntityManager::getInstance();
        $email = Zend_Auth::getInstance()->getIdentity();
        $user = $em->getRepository("Employee")->findOneBy(array('email' => $email));

        if (is_null($user)) {
            throw new User_Exception("Entity with identity '$email' not found");
        }

        return $user;
    }

}