<?php

/**
 * 
 * @ author Jigulin V.V.
 * @ 07.02.2017
 * 
 */
  
namespace Universe\Model;

use Universe\Geometry\Point;

interface CelestialBodyInterface
{
    /**
     * 
     * Set coordinate of body
     * @param int
     * 
     */ 
    public function setCoordinate($point);
    
    /**
     * 
     * Get coordinate of body
     * @return int
     * 
     */ 
    public function getCoordinate();
 
    /**
     * 
     * Set parent celestial CelestialContainer
     * @param CelestialContainer
     * 
     */
    public function setCelestialParent(CelestialContainer $celestialParent);
     
    /**
     * 
     * @Get parent celestial CelestialContainer
     * 
     */
    public function getCelestialParent();
}
