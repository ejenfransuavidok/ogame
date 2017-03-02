<?php

/**
 * 
 * @ author Jigulin V.V.
 * @ 07.02.2017
 * 
 */
  
namespace Universe\Model;

use Universe\Geometry\Point;

interface CelestialContainerInterface
{
    /**
     * 
     * Set coordinate of body
     * @param int
     * 
     */ 
    public function setBasis($basis);
    
    /**
     * 
     * Get coordinate of body
     * @return int
     * 
     */ 
    public function getBasis();
    
    /**
     * 
     * @param int
     * 
     */
     public function setSize($size);
     
     /**
     * 
     * @return int
     * 
     */
     public function getSize();
}
