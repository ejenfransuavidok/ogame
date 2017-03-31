<?php

namespace App\Classes;

class TimeUtils
{
    
    public static function time2Interval($time)
    {
        $hours = "00";
        $minutes = "00";
        $seconds = "00";
        
        if($time > 0){
            $hours = floor($time / 3600);
            $minutes = floor(($time - 3600 * $hours) / 60);
            $seconds = intval($time - 3600 * $hours - 60 * $minutes);
            if($hours < 10)
                $hours = "0".$hours;
            if($minutes < 10)
                $minutes = "0".$minutes;
            if($seconds < 10)
                $seconds = "0".$seconds;
        }
        return $hours . ':' . $minutes . ':' . $seconds;
    }
    
}
