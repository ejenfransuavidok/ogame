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
    
    public static function interval2String($interval)
    {
        $monthes    = 0;
        $days       = 0;
        $hours      = 0;
        $minutes    = 0;
        $seconds    = 0;
        
        if($interval > 0){
            $monthes = floor($interval / (3600 * 24 * 31));
            if($monthes > 0)
                $days = floor(($interval - $monthes * 3600 * 24 * 31) / (3600 * 24));
            else
                $days = floor($interval / (3600 * 24));
            if($monthes > 0 || $days > 0)
                $hours = floor(($interval - $monthes * 3600 * 24 * 31 - $days * 3600 * 24) / 3600);
            else
                $hours = floor($interval / 3600);
            if($monthes > 0 || $days > 0 || $hours > 0)
                $minutes = floor(($interval - $monthes * 3600 * 24 * 31 - $days * 3600 * 24 - $hours * 3600) / 60);
            else
                $minutes = floor($interval / 60);
            if($monthes > 0 || $days > 0 || $hours > 0 || $minutes > 0)
                $seconds = floor($interval - $monthes * 3600 * 24 * 31 - $days * 3600 * 24 - $hours * 3600 - $minutes * 60);
            else
                $seconds = $interval;
            $monthes = $monthes ? $monthes . " месяцев, " : "";
            $days = $days ? $days . " дней, " : "";
            if($hours){
                $hours = $hours < 10 ? "0".$hours : $hours;
            }
            else{
                $hours = "00";
            }
            if($minutes){
                $minutes = $minutes < 10 ? "0".$minutes : $minutes;
            }
            else{
                $minutes = "00";
            }
            if($seconds){
                $seconds = $seconds < 10 ? "0".$seconds : $seconds;
            }
            else{
                $seconds = "00";
            }
            return $monthes . $days . $hours . ':' . $minutes . ':' . $seconds;
        }
        else
            return '00:00:00';
    }
    
}
