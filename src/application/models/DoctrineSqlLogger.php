<?php

class DoctrineSqlLogger implements Doctrine\DBAL\Logging\SQLLogger
{
    public function startQuery($sql, array $params = null, array $types = null)
    {
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