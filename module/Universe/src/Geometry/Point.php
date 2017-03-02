<?php

/**
 * 
 * @ author Jigulin V.V.
 * @ 07.02.2017
 * 
 */
 
namespace Universe\Geometry;

class Point
{
    /**
     * @var int
     */ 
    private $x;
    
    /**
     * @var int
     */
    private $y;
     
    /**
     * @return int
     */
    public function getX()
    {
        return $this->x;
    }
    
    /**
     * @return int
     */
    public function getY()
    {
        return $this->y;
    }
    
    /**
     * @param int $x 
     */
    public function setX($x)
    {
        $this->x = $x;
        return $this;
    }
    
    /**
     * @param int $y
     */
    public function setY($y)
    {
        $this->y = $y;
        return $this;
    }
    
}
