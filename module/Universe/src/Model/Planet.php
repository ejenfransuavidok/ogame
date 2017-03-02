<?php

/**
 * 
 * @ author Jigulin V.V.
 * @ 07.02.2017
 * 
 */

namespace Universe\Model;

class Planet extends CelestialBody
{
    const TABLE_NAME = 'planets';
    
    /**
     * 
     * position of planet into planets system 1...N
     * @int
     * 
     */
    private $position;
    
    /**
     * 
     * @пригодность для жизни
     * 
     */
    private $livable;
    
    public function __construct($name, $description, $coordinate, $size, $position, $livable, $planet_system, $id=null)
    {
        parent::__construct(self::TABLE_NAME);
        $this->name             = $name;
        $this->description      = $description;
        $this->coordinate       = $coordinate;
        $this->size             = $size;
        $this->position         = $position;
        $this->livable          = $livable;
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
        $this->position         = !empty($data[$prefix.'position']) ? $data[$prefix.'position'] : null;
        $this->livable          = !empty($data[$prefix.'livable']) ? $data[$prefix.'livable'] : null;
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
     * @param int
     * 
     */
    public function setLivable($livable)
    {
        $this->livable = $livable;
    }
    
    /**
     * 
     * @return int
     * 
     */
    public function getLivable()
    {
        return $this->livable;
    }
}
