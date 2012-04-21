<?php

class Controller_Default extends Zend_Controller_Action {

    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    public function init() {
        parent::init();

        $this->view->strictVars(true);

        $this->em = EntityManager::getInstance();
        $this->view->username = User::getLoggedUser()->getUsername();
        $this->view->balance = User::getLoggedUser()->getBalance();
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