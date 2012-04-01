<?php

class IntrashopAuthResolver implements Zend_Auth_Adapter_Http_Resolver_Interface
{
    /**
     * Docasna implementace
     * Ma vratit heslo
     * 
     * @param type $username
     * @param type $realm
     * @return string 
     */
    
    public function resolve($username, $realm) {
        
        return 'intrashop';
    }
}

class Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $auth = Zend_Auth::getInstance();

        $adapter = new Zend_Auth_Adapter_Http(array(
                    'accept_schemes' => 'basic',
                    'realm' => 'intrashop',
                    'nonce_timeout' => 3600,
                ));
        $adapter->setBasicResolver(new IntraShopAuthResolver());
        $adapter->setRequest($this->getRequest());
        $adapter->setResponse($this->getResponse());

        $result = Zend_Auth::getInstance()->authenticate($adapter);
        
        if (!$result->isValid()) {
            $this->getResponse()->setBody("401 Unauthorized")->sendResponse();
            //Zend_Controller_Front::getInstance();
        }
    }
}