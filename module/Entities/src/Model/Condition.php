<?php

/**
 * 
 * @ author Jigulin V.V.
 * @ 27.02.2017
 * 
 */

namespace Entities\Model;

use RuntimeException;
use Zend\Math\Rand;
use Universe\Model\Entity;

class Condition extends Entity
{
    const TABLE_NAME = 'conditions';
    
    public function __construct($name, $description, $id=null)
    {
        parent::__construct(self::TABLE_NAME);
        $this->name         = $name;
        $this->description  = $description;
        $this->id           = $id;
    }
    
    public function exchangeArray(array $data, $prefix = '')
    {
        $this->id           = !empty($data[$prefix.'id']) ? $data[$prefix.'id'] : null;
        $this->name         = !empty($data[$prefix.'name']) ? $data[$prefix.'name'] : null;
        $this->description  = !empty($data[$prefix.'description']) ? $data[$prefix.'description'] : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}
