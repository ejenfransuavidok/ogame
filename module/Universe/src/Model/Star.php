<?php

/**
 * 
 * @ author Jigulin V.V.
 * @ 07.02.2017
 * 
 */

namespace Universe\Model;

class Star extends CelestialBody
{
    const TABLE_NAME = 'stars';
    
    /**
     * 
     * @ тип звезды StarType
     * 
     */
    private $star_type;
    
    public function __construct($name, $description, $coordinate, $size, $star_type, $planet_system, $id=null)
    {
        parent::__construct(self::TABLE_NAME);
        $this->name             = $name;
        $this->description      = $description;
        $this->coordinate       = $coordinate;
        $this->size             = $size;
        $this->star_type        = $star_type;
        $this->celestialParent  = $planet_system;
        $this->id               = $id;
    }
    
    public function exchangeArray(array $data, $prefix = '')
    {
        $this->id               = !empty($data[$prefix.'id']) ? $data[$prefix.'id'] : null;
        $this->name             = !empty($data[$prefix.'name']) ? $data[$prefix.'name'] : null;
        $this->description      = !empty($data[$prefix.'description']) ? $data[$prefix.'description'] : null;
        $this->coordinate       = !empty($data[$prefix.'coordinate']) ? $data[$prefix.'coordinate'] : null;
        $this->size             = !empty($data[$prefix.'size']) ? $data[$prefix.'size'] : null;
        $this->star_type        = !empty($data[$prefix.'star_type']) ? $data[$prefix.'star_type'] : null;
        $this->celestialParent  = !empty($data[$prefix.'planet_system']) ? $data[$prefix.'planet_system'] : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    /**
     * 
     * @param StarType
     * 
     */
    public function setStarType($star_type)
    {
        $this->star_type = $star_type;
    }
    
    /**
     * 
     * @return StarType
     * 
     */
    public function getStarType()
    {
        return $this->star_type;
    }
    
}
