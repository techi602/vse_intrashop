<?php

class Controller_Default extends Zend_Controller_Action {

    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /** @var Employee */
    protected $loggedUser;

    public function init() {
        parent::init();

        $this->view->strictVars(true);

        $this->em = EntityManager::getInstance();
        $this->loggedUser = User::getLoggedUser();
        $this->view->username = $this->loggedUser->getUsername();
        $this->view->balance = $this->loggedUser->getBalance();
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

}