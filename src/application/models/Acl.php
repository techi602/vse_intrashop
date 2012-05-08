<?php

class Acl extends Zend_Acl
{
    public function __construct()
    {
        $this->addRole(new Zend_Acl_Role(UserRole::ROLE_FINANCIALDIRECTOR));
        $this->addRole(new Zend_Acl_Role(UserRole::ROLE_WAREHOUSEKEEPER));
        $this->addRole(new Zend_Acl_Role(UserRole::ROLE_SUPERIOR));
        $this->addRole(new Zend_Acl_Role(UserRole::ROLE_PERSONNELOFFICER));
    }
}