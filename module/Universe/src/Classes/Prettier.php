<?php

namespace Universe\Classes;

class Prettier
{
    
    static public function doIt($num)
    {
        if($num > 1000000){
            return ceil($num / 1000000).'m';
        }
        else if($num > 1000){
            return ceil($num / 1000).'k';
        }
        else
            return $num;
    }
    
}
