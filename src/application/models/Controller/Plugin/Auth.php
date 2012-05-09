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
        
        $user = EntityManager::getInstance()->getRepository('Employee')->findOneBy(array('email' => $username));
        
        if (is_null($user)) {
            return null;
        }

        return $user->getEmail();
    }
}

class Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        if ($request->getControllerName() == 'import') {
            return;
        }
        
        $realm = 'intrashop';
        
       if ($request->getParam('logoff')) {
            if (Zend_Auth::getInstance()->hasIdentity()) {
                Zend_Auth::getInstance()->clearIdentity();
                header("HTTP/1.0 401 Authorization Required");
                header('WWW-Authenticate: Basic realm="' . $realm . '"');
                exit;
            } else {
                header("Location:http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']));
                exit;
            }
        }
        
        $adapter = new Zend_Auth_Adapter_Http(array(
                    'accept_schemes' => 'basic',
                    'realm' => $realm,
                    'nonce_timeout' => 3600,
                ));
        $adapter->setBasicResolver(new IntraShopAuthResolver());
        $adapter->setRequest($this->getRequest());
        $adapter->setResponse($this->getResponse());
        
        $result = Zend_Auth::getInstance()->authenticate($adapter);
        
        if (!$result->isValid()) {
            $this->getResponse()->setBody("401 Unauthorized")->sendResponse();
        }
    }
}