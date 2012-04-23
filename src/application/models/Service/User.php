<?php

class Service_User
{
    /** @var Doctrine\ORM\EntityManager */
    private $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }
 
    public function getEmployee($id, $desiredRole = null)
    {
        if (is_null($desiredRole)) {
            $entity = 'Employee';
        } else {
            switch ($desiredRole) {
                case UserRole::ROLE_SUPERIOR:
                    $entity = 'SuperiorEmployee';
                    break;
                
                case UserRole::ROLE_PERSONNELOFFICER:
                    $entity = 'PersonnelOfficer';
                    break;
                
                case UserRole::ROLE_WAREHOUSEKEEPER:
                    $entity = 'WarehouseKeeper';
                    break;
                
                default:
                    $entity = 'Employee';
            }
        }
        
        $user = $this->em->find($entity, $identifier);
        
        if (!is_null($user) && !is_null($desiredRole)) {
            if (!$user->hasRole($desiredRole)) {
                throw new Exception("User {" . $user->getName() . "} has not assigned $desiredRole role");
            }
        }
        
        return $user;
    }
}
