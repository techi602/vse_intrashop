<?php

final class Util
{

    private function __construct()
    {
        
    }

    public static function generateRange($max, $min = 1)
    {
        $a = array();

        for ($i = $min; $i <= $max; $i++) {
            $a[$i] = $i;
        }

        return $a;
    }

}
