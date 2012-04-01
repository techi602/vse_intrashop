<?php 

define('APPLICATION_PATH', "c:/Projects/php/intr/vse_intrashop/src/application");

require 'Doctrine/ORM/Tools/Setup.php';

// Setup Autoloader (1)
Doctrine\ORM\Tools\Setup::registerAutoloadPEAR();

// Define application environment
define('APPLICATION_ENV', "development");

/*
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
*/

 $classLoader = new \Doctrine\Common\ClassLoader('models', APPLICATION_PATH);
 $classLoader->register();
 $classLoader = new \Doctrine\Common\ClassLoader('Proxies', APPLICATION_PATH);
 $classLoader->register();

// configuration (2)
 $config = new \Doctrine\ORM\Configuration();

// Proxies (3)
 $config->setProxyDir(APPLICATION_PATH . '/Proxies');
 $config->setProxyNamespace('Proxies');

 $config->setAutoGenerateProxyClasses((APPLICATION_ENV == "development"));

// Driver (4)
 $driverImpl = $config->newDefaultAnnotationDriver(array(APPLICATION_PATH."/models"));
 $config->setMetadataDriverImpl($driverImpl);

// Caching Configuration (5)
 if (APPLICATION_ENV == "development") {

     $cache = new \Doctrine\Common\Cache\ArrayCache();

 } else {

     $cache = new \Doctrine\Common\Cache\ApcCache();
 }

 $config->setMetadataCacheImpl($cache);
 $config->setQueryCacheImpl($cache);

 $connectionOptions = array(
    'driver' => 'pdo_mysql',
    'dbname' => 'test',
    'user' => '',
    'password' => '',
    'host' => 'localhost');

 $em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);

 $helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
     'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
     'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
 ));