<?php

class Controller_Default extends Zend_Controller_Action {

    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /** @var string*/
    protected $loggedUserRole;

    /** @var Employee */
    protected $loggedEmployee;

    /** @var PersonnelOfficer */
    protected $loggedPersonnelOfficer;

    /** @var WarehouseKeeper */
    protected $loggedWarehouseKeeper;

    public function init() {
        parent::init();

        $this->view->strictVars(true);
        $this->em = EntityManager::getInstance();

        $this->initRoles();
        $this->initViewVariables();
        $this->view->addHelperPath(APPLICATION_PATH . '/views/helpers/', 'Default_Helper');
    }

    /**
     * Add info message into quee
     *
     * @param string $message
     */
    protected function addInfoMessage($message) {
        Controller_Plugin_MessageHandler::addInfoMessage($message);
    }

    /**
     * Add error message into quee
     *
     * @param string $message
     */
    protected function addErrorMessage($message) {
        Controller_Plugin_MessageHandler::addErrorMessage($message);
    }

    private function initRoles() {
        $loggedUser = User::getLoggedUser();
        $this->loggedUserRole = get_class($loggedUser);

        if ($loggedUser instanceof Employee) {
            $this->loggedEmployee = $loggedUser;
        }

        if ($loggedUser instanceof PersonnelOfficer) {
            $this->loggedPersonnelOfficer = $loggedUser;
        }

        if ($loggedUser instanceof WarehouseKeeper) {
            $this->loggedWarehouseKeeper = $loggedUser;
        }
    }

    private function initViewVariables() {
        
        $this->view->username = $this->loggedEmployee->getUsername();
        $this->view->balance = $this->loggedEmployee->getBalance();
        $this->view->role = $this->loggedUserRole;

        if ($this->loggedPersonnelOfficer) {
            $this->view->personnelOfficerBalance = $this->loggedPersonnelOfficer->getPersonnelOfficerBalance();
        }
    }
}
