<?php

/**
 * 
 * @ author Jigulin V.V.
 * @ 07.02.2017
 * 
 */

namespace Universe\Model;

class Galaxy extends CelestialContainer
{
    const TABLE_NAME = 'galaxies';
    
    public function __construct($name, $description, $basis, $size, $id=null)
    {
        parent::__construct(self::TABLE_NAME);
        $this->name         = $name;
        $this->description  = $description;
        $this->basis        = $basis;
        $this->size         = $size;
        $this->id           = $id;
    }
    
    public function exchangeArray(array $data, $prefix = '')
    {
        $this->id           = !empty($data[$prefix.'id']) ? $data[$prefix.'id'] : null;
        $this->name         = !empty($data[$prefix.'name']) ? $data[$prefix.'name'] : null;
        $this->description  = !empty($data[$prefix.'description']) ? $data[$prefix.'description'] : null;
        $this->basis        = !empty($data[$prefix.'basis']) ? $data[$prefix.'basis'] : null;
        $this->size         = !empty($data[$prefix.'size']) ? $data[$prefix.'size'] : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}
