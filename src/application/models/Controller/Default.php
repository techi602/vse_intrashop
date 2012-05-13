<?php

class Controller_Default extends Zend_Controller_Action
{

    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /** @var string */
    protected $loggedUserRole;

    /** @var Employee */
    protected $loggedEmployee;

    /** @var PersonnelOfficer */
    protected $loggedPersonnelOfficer;

    /** @var WarehouseKeeper */
    protected $loggedWarehouseKeeper;

    /** @var SuperiorEmployee */
    protected $loggedSuperiorEmployee;

    public function init()
    {
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
    protected function addInfoMessage($message)
    {
        Controller_Plugin_MessageHandler::addInfoMessage($message);
    }

    /**
     * Add error message into quee
     *
     * @param string $message
     */
    protected function addErrorMessage($message)
    {
        Controller_Plugin_MessageHandler::addErrorMessage($message);
    }

    private function initRoles()
    {
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

        if ($loggedUser instanceof SuperiorEmployee) {
            $this->loggedSuperiorEmployee = $loggedUser;
        }
    }

    private function initViewVariables()
    {

        $this->view->name = $this->loggedEmployee->getName();
        $this->view->balance = $this->loggedEmployee->getBalance();
        $this->view->role = $this->loggedUserRole;

        if ($this->loggedPersonnelOfficer) {
            $this->view->personnelOfficerBalance = $this->loggedPersonnelOfficer->getPersonnelOfficerBalance();
        }

        if ($this->loggedSuperiorEmployee) {
            $this->view->superiorEmployeeBalance = $this->loggedSuperiorEmployee->getSuperiorBalance();
        }
    }

    private function initNavigation()
    {
        $nav = array();

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

        if ($this->loggedWarehouseKeeper) {
            $nav = array_merge($nav, array(
                array(
                    'controller' => 'warehouse',
                    'label' => 'Sklad',
                    'pages' => array(
                        array(
                            'controller' => 'warehouse',
                            'action' => 'index',
                            'label' => 'Katalog',
                        ),
                        array(
                            'controller' => 'warehouse',
                            'action' => 'edit',
                            'label' => 'Nový produkt',
                        ),
                    ),
                    )));

            $nav = array_merge($nav, array(
                array(
                    'controller' => 'orders',
                    'action' => 'index',
                    'label' => 'Objednávky',
                    )));
        }

        if ($this->loggedSuperiorEmployee) {
            $nav = array_merge($nav, array(
                array(
                    'controller' => 'performance-credits-admin',
                    'label' => 'Přidělit bonusové body'
                )
                    ));
        }

        if ($this->loggedPersonnelOfficer) {
            $nav = array_merge($nav, array(
                array(
                    'controller' => 'extra-credits-admin',
                    'label' => 'Přidělit mimořádné body'
                )
                    ));
        }

        $navigation = new Zend_Navigation();
        $navigation->setPages($nav);

        $this->view->navigation($navigation);

        $activeNav = $this->view->navigation()->findByController($this->getRequest()->getControllerName());
        @$activeNav->active = true;
    }

}
