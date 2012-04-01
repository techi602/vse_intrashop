<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAutoload()
    {
        
        $moduleLoader = new Zend_Application_Module_Autoloader(array(
                'namespace' => '',
                'models' => APPLICATION_PATH.'./models',
                'basePath'  => APPLICATION_PATH));
        
        
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->setFallbackAutoloader(true);
        
        
        
        
        
        //$bisnaAutoloader = new \Doctrine\Common\ClassLoader('Bisna');
        //$autoloader->pushAutoloader(array($bisnaAutoloader, 'loadClass'), 'Bisna');
    }
    
    
    /*
    protected function _initView()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('HTML5');
    }
    */
    
    protected function _initPlugins()
    {
        //$front = Zend_Controller_Front::getInstance();
        //$front->registerPlugin(new Zend_Controller_Plugin_ErrorHandler());
    

    }
    
    protected function _initDoctrine()
    {
        require 'Doctrine/ORM/Tools/Setup.php';
        
        // Setup Autoloader (1)
        \Doctrine\ORM\Tools\Setup::registerAutoloadPEAR();
        
        $classLoader = new \Doctrine\Common\ClassLoader('Entities', __DIR__);
        $classLoader->register();
        $classLoader = new \Doctrine\Common\ClassLoader('Proxies', __DIR__);
        $classLoader->register();

        // configuration (2)
        $config = new \Doctrine\ORM\Configuration();
        
        // Proxies (3)
        $config->setProxyDir(__DIR__ . '/Proxies');
        $config->setProxyNamespace('Proxies');
        
        $config->setAutoGenerateProxyClasses((APPLICATION_ENV == "development"));
        
        // Driver (4)
        $driverImpl = $config->newDefaultAnnotationDriver('models');
        $config->setMetadataDriverImpl($driverImpl);
        
        $cache = new \Doctrine\Common\Cache\ArrayCache();
        
        $config->setMetadataCacheImpl($cache);
        $config->setQueryCacheImpl($cache);
        
        $connectionOptions = array(
                'driver' => 'pdo_mysql',
                'dbname' => 'test',
                'user' => '',
                'password' => '',
                'host' => 'localhost');
        
        $em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);
        
        Zend_Registry::set('EntityManager', $em);
    }
    
    
    /*
    public function _initAutoloader()
    {
        //require_once APPLICATION_PATH . '/../library/Doctrine/Common/ClassLoader.php';
    
        //$appAutoloader = new \Doctrine\Common\ClassLoader('NOLASnowball');
        //$autoloader->pushAutoloader(array($appAutoloader, 'loadClass'), 'NOLASnowball');
    }
    */
    
    
    /*
    protected function _initRoutes()
    {
    
        //ROUTING
        $this->bootstrap('frontController');
        $router = $this->frontController->getRouter();
        $router->addConfig(new Zend_Config_Ini(APPLICATION_PATH.'/configs/routes.ini'));
    }
*/
}

