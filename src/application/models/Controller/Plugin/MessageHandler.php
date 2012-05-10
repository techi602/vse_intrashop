<?php

class Controller_Plugin_MessageHandler extends Zend_Controller_Plugin_Abstract
{

    private static $messages = array();

    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        $layout = Zend_Layout::getMvcInstance();

        $layout->info = self::getMessages('info');

        $layout->error = self::getMessages('error');
        $layout->warning = self::getMessages('warning');
    }

    public function dispatchLoopShutdown()
    {
        $flash = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');

        //$mess = (array)@self::$messages[$namespace] + $flash->setNamespace($namespace)->getMessages();

        $flash->setNamespace('info')->clearMessages();
        $flash->setNamespace('error')->clearMessages();
        $flash->setNamespace('warning')->clearMessages();
    }

    public static function clearMessages($namespace)
    {
        $flash = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');

        $flash->setNamespace($namespace)->clearMessages();
    }

    /**
     * Enter description here...
     *
     * @param string $namespace
     * @return array
     */
    public static function getMessages($namespace)
    {
        $flash = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');

        $mess = (array) @self::$messages[$namespace] + $flash->setNamespace($namespace)->getMessages();

        $flash->setNamespace($namespace)->clearMessages();

        return $mess;
    }

    /**
     * Enter description here...
     *
     * @param string $message
     * @param string $namespace
     */
    public static function addMessage($message, $namespace)
    {
        if (!empty($message)) {
            $flash = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');

            $flash->setNamespace($namespace)->addMessage($message);

            if (!array_key_exists($namespace, self::$messages)) {
                self::$messages[$namespace] = array();
            }

            self::$messages[$namespace][] = $message;

            /*
              $writer = new Zend_Log_Writer_Stream('./logs/messages.log');

              $priority = Zend_Log::INFO;

              if ($namespace == "error")
              {
              $priority = Zend_Log::ERR;
              }

              $log = new Zend_Log($writer);
              $log->log($message, $priority);
             */
        }
    }

    /**
     * Enter description here...
     *
     * @param string $message
     */
    public static function addInfoMessage($message)
    {
        self::addMessage($message, 'info');
    }

    /**
     * Enter description here...
     *
     * @param string $message
     */
    public static function addErrorMessage($message)
    {
        self::addMessage($message, 'error');
    }

    /**
     * Enter description here...
     *
     * @param string $message
     */
    public static function addWarningMessage($message)
    {
        self::addMessage($message, 'warning');
    }

}