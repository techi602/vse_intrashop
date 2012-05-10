<?php

class DoctrineSqlLogger implements Doctrine\DBAL\Logging\SQLLogger
{

    /**
     *
     * @var Zend_Log
     */
    private $logger;

    public function __construct()
    {
        $this->logger = new Zend_Log(new Zend_Log_Writer_Firebug());
    }

    public function startQuery($sql, array $params = null, array $types = null)
    {
        $this->logger->log($sql, Zend_Log::INFO);
        //echo $sql."<br>";
    }

    /**
     * Mark the last started query as stopped. This can be used for timing of queries.
     *
     * @return void
     */
    public function stopQuery()
    {
        
    }

}