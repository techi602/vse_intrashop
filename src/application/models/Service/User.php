<?php

class Service_User
{

    /** @var Doctrine\ORM\EntityManager */
    private $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function getUser($id, $desiredRole = null)
    {
        $user = $this->em->find("Employee", $identifier);

        if (!is_null($user) && !is_null($desiredRole)) {
            if (!$user->hasRole($desiredRole)) {
                throw new Exception("User {" . $user->getName() . "} has not assigned $desiredRole role");
            }
        }

        return $user;
    }

}
