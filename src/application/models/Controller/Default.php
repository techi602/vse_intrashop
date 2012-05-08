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
        $this->initNavigation();
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
    
    private function initNavigation()
    {
        $nav = array();
        
        $user = User::getLoggedUser();
        $user->getRoles();
        
        // zamestnanec
        $nav = array_merge($nav, array(
    array(
        'controller' => 'index',
        'label' => 'Home',
        'pages' => array(
                array(
                'controller' => 'orders',
                'action' => 'employee',
                'label' => 'Moje objednávky',
                ),
                array(
                'controller' => 'index',
                'action' => 'catalog',
                'label' => 'Seznam zboží',
                ),
            ),
        )));
        
        
        $nav = array_merge($nav, array(
    array(
        'controller' => 'catalog',
        'label' => 'Katalog',
        'pages' => array(
            array(
                'controller' => 'tools',
                'action' => 'free',
                'label' => 'Free Tools',
                ),
            array(
                'controller' => 'tools',
                'action' => 'licenses',
                'label' => 'New Licenses',
                ),
            array(
                'controller' => 'tools',
                'action' => 'products',
                'label' => 'Products',
                ),
            ),
        )));
           
                /*
    array(
        'module' => 'admin',
        'label' => 'Administration',
        'resource' => 'admin',
        'privilege' => 'index',
        'pages' => array(
            array(
                'module' => 'admin',
                'controller' => 'adduser',
                'label' => 'Add User',
                'resource' => 'admin',
                'privilege' => 'adduser',
                ),
            array(
                'module' => 'admin',
                'controller' => 'addpage',
                'label' => 'Add Page',
                'resource' => 'admin',
                'privilege' => 'addpage',
                ),
            ),
        )
    );
        */
        $navigation = new Zend_Navigation();
        $navigation->setPages($nav);
        
        $this->view->navigation($navigation);
        
        $activeNav = $this->view->navigation()->findByController($this->getRequest()->getControllerName());
		@$activeNav->active = true;
    }
}
