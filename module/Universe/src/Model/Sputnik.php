<?php

/**
 * 
 * @ author Jigulin V.V.
 * @ 07.02.2017
 * 
 */

namespace Universe\Model;

class Sputnik extends CelestialBody
{
    const TABLE_NAME = 'sputniks';
    
    /**
     *
     * 
     * @parent planet Planet
     * 
     */
    private $parent_planet;
    
    /**
     * 
     * @int distance
     * 
     */
    private $position;
    
    /**
     * 
     * @int distance
     * 
     */
    private $distance;
    
    public function __construct($name, $description, $size, $position, $distance, $parent_planet, $planet_system, $id=null)
    {
        parent::__construct(self::TABLE_NAME);
        $this->name             = $name;
        $this->description      = $description;
        $this->size             = $size;
        $this->position         = $position;
        $this->distance         = $distance;
        $this->parent_planet    = $parent_planet;
        $this->celestialParent  = $planet_system;
        $this->id               = $id;
    }
    
    public function exchangeArray(array $data, $prefix = '')
    {
        $this->id               = !empty($data[$prefix.'id']) ? $data[$prefix.'id'] : null;
        $this->name             = !empty($data[$prefix.'name']) ? $data[$prefix.'name'] : null;
        $this->description      = !empty($data[$prefix.'description']) ? $data[$prefix.'description'] : null;
        $this->size             = !empty($data[$prefix.'size']) ? $data[$prefix.'size'] : null;
        $this->position         = !empty($data[$prefix.'position']) ? $data[$prefix.'position'] : null;
        $this->distance         = !empty($data[$prefix.'distance']) ? $data[$prefix.'distance'] : null;
        $this->parent_planet    = !empty($data[$prefix.'parent_planet']) ? $data[$prefix.'parent_planet'] : null;
        $this->celestialParent  = !empty($data[$prefix.'planet_system']) ? $data[$prefix.'planet_system'] : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    /**
     * 
     * @param int
     * 
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;
    }
    
    /**
     * 
     * @return int
     * 
     */
    public function getDistance()
    {
        return $this->distance;
    }
    
    /**
     * 
     * @param int
     * 
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }
    
    /**
     * 
     * @return int
     * 
     */
    public function getPosition()
    {
        return $this->position;
    }
    
    /**
     * 
     * @param Planet
     * 
     */
    public function setParentPlanet(Planet $parent)
    {
        $this->parent_planet = $parent;
    }
    
    /**
     * 
     * @return Planet
     * 
     */
     public function getParentPlanet()
     {
         return $this->parent_planet;
     }
}
