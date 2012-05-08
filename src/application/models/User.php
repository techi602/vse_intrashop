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
        
        if (is_null($role)) {
            $entity = 'Employee';
        } else {
            switch ($role) {
                case UserRole::ROLE_WAREHOUSEKEEPER:
                    $entity = 'WarehouseKeeper';
                    break;

                case UserRole::ROLE_PERSONNELOFFICER:
                    $entity = 'PersonnelOfficer';
                    break;

                default:
                    throw new User_Exception("Unknown role: $role");
            }
        }

        $em = EntityManager::getInstance();
        $email = Zend_Auth::getInstance()->getIdentity();
        $user = $em->getRepository($entity)->findOneBy(array('email' => $email));
        
        if (is_null($user)) {
            throw new User_Exception("Entity $entity not found with identity $email");
        }
        
        if (!is_null($role)) {
            if (!$user->hasRole($role)) {
                throw new User_Exception("User $email has not role $role");
            }
        }
        
        return $user;
    }
}