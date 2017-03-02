<?php

/**
 * 
 * @ author Jigulin V.V.
 * @ 07.02.2017
 * 
 */

namespace Universe\Model;

use Universe\Geometry\Point;

class CelestialContainer extends Entity implements CelestialContainerInterface
{
    
    /**
     * @ int
     */
    protected $basis;
    
    /**
     * 
     * @int
     * 
     */
    protected $size;
    
    /**
     * 
     * @param int
     * 
     */
    public function setBasis($basis)
    {
        $this->basis = $basis;
    }
    
    /**
     * 
     * @return int
     * 
     */
    public function getBasis()
    {
        return $this->basis;
    }
    
    /**
     * 
     * @param int
     * 
     */
    public function setSize($size)
    {
        $this->size = $size;
    }
    
    /**
     * 
     * @return int
     * 
     */
    public function getSize()
    {
        return $this->size;
    }
    
}
