<?php

/**
 * 
 * @ author Jigulin V.V.
 * @ 07.02.2017
 * 
 */

namespace Universe\Model;

use Universe\Geometry\Point;

class CelestialBody extends Entity implements CelestialBodyInterface
{
    
    /**
     * 
     * @ int
     *
     */
    protected $coordinate;
    
    /**
     * 
     * @ int 
     * 
     */
    protected $size;
    
    /**
     * @CelestialContainer
     */
    protected $celestialParent;
    
    public function setCoordinate($point)
    {
        $this->coordinate = $point;
    }
    
    public function getCoordinate()
    {
        return $this->coordinate;
    }
    
    public function setSize($size)
    {
        $this->size = $size;
    }
    
    public function getSize()
    {
        return $this->size;
    }
    
    public function setCelestialParent(CelestialContainer $celestialParent)
    {
        $this->celestialParent = $celestialParent;
    }
    
    public function getCelestialParent()
    {
        return $this->celestialParent;
    }
    
}
