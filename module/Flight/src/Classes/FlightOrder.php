<?php

namespace Flight\Classes;

class FlightOrder
{
    
    /**
     * @ int
     */
    private $distance;
    
    /**
     * @ int
     */
    private $period;
    
    /**
     * @ Planet
     */
    private $target;
    
    /**
     * @ boolean
     */
    private $canGetTarget;
    
    /**
     * @ String
     */
    private $time2OneEnd;
    
    /**
     * @ String
     */
    private $time2BothEnds;
    
    /**
     * @ String
     */
    private $speed;
    
    /**
     * @ String
     */
    private $arrival;
    
    /**
     * @ String
     */
    private $comeback;
    
    /**
     * @ String
     */
    private $capacity;
    
    /**
     * @ String (потребление топлива флотом в одну сторону)
     */
    private $spendFuelAtOneEnd;
    
    public function setDistance($distance)
    {
        $this->distance = $distance;
    }
    
    public function getDistance()
    {
        return $this->distance;
    }
    
    public function setPeriod($period)
    {
        $this->period = $period;
    }
    
    public function getPeriod()
    {
        return $this->period;
    }
    
    public function setTarget($target)
    {
        $this->target = $target;
    }
    
    public function getTarget()
    {
        return $this->target;
    }
    
    public function setCanGetTarget($canGetTarget)
    {
        $this->canGetTarget = $canGetTarget;
    }
    
    public function getCanGetTarget()
    {
        return $this->canGetTarget;
    }
    
    public function setTime2OneEnd($time2OneEnd)
    {
        $this->time2OneEnd = $time2OneEnd;
    }
    
    public function getTime2OneEnd()
    {
        return $this->time2OneEnd;
    }
    
    public function setTime2BothEnd($time2BothEnds)
    {
        $this->time2BothEnds = $time2BothEnds;
    }
    
    public function getTime2BothEnd()
    {
        return $this->time2BothEnds;
    }
    
    public function setSpeed($speed)
    {
        $this->speed = $speed;
    }
    
    public function getSpeed()
    {
        return $this->speed ? $this->speed : '-';
    }
    
    public function setArrival($arrival)
    {
        $this->arrival = $arrival;
    }
    
    public function getArrival()
    {
        return $this->arrival ? $this->arrival : '-';
    }
    
    public function setComeback($comeback)
    {
        $this->comeback = $comeback;
    }
    
    public function getComeback()
    {
        return $this->comeback ? $this->comeback : '-';
    }
    
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;
    }
    
    public function getCapacity()
    {
        return $this->capacity ? $this->capacity : '-';
    }
    
    public function setSpendFuelAtOneEnd($spendFuelAtOneEnd)
    {
        $this->spendFuelAtOneEnd = $spendFuelAtOneEnd;
    }
    
    public function getSpendFuelAtOneEnd()
    {
        return $this->spendFuelAtOneEnd ? $this->spendFuelAtOneEnd : '-';
    }
    
}
