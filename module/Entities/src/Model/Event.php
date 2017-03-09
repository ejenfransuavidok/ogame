<?php

/**
 * 
 * @ author Jigulin V.V.
 * @ 09.03.2017
 * 
 */

namespace Entities\Model;

use RuntimeException;
use Zend\Math\Rand;
use Universe\Model\Entity;

class Event extends Entity
{
    const TABLE_NAME = 'events';
    
    /**
     * @ User
     */
    protected $user;
    
    /**
     * @ EventType
     */
    protected $event_type;
    
    /**
     * @ time
     */
    protected $event_begin;
    
    /**
     * @ time
     */
    protected $event_end;
    
    /**
     * @ Planet
     */
    protected $target_planet;
    
    /**
     * @ Sputnik
     */
    protected $target_sputnik;
    
    
    
    public function __construct($name, $description, $user, $event_type, $event_begin, $event_end, $target_planet, $target_sputnik, $id=null)
    {
        parent::__construct(self::TABLE_NAME);
        $this->name             = $name;
        $this->description      = $description;
        $this->user             = $user;
        $this->event_type       = $event_type;
        $this->event_begin      = $event_begin;
        $this->event_end        = $event_end;
        $this->target_planet    = $target_planet;
        $this->target_sputnik   = $target_sputnik;
        $this->id               = $id;
    }
    
    public function exchangeArray(array $data, $prefix = '')
    {
        $this->id               = !empty($data[$prefix.'id']) ? $data[$prefix.'id'] : null;
        $this->name             = !empty($data[$prefix.'name']) ? $data[$prefix.'name'] : null;
        $this->description      = !empty($data[$prefix.'description']) ? $data[$prefix.'description'] : null;
        $this->user             = !empty($data[$prefix.'user']) ? $data[$prefix.'user'] : null;
        $this->event_type       = !empty($data[$prefix.'event_type']) ? $data[$prefix.'event_type'] : null;
        $this->event_begin      = !empty($data[$prefix.'event_begin']) ? $data[$prefix.'event_begin'] : null;
        $this->event_end        = !empty($data[$prefix.'event_end']) ? $data[$prefix.'event_end'] : null;
        $this->target_planet    = !empty($data[$prefix.'target_planet']) ? $data[$prefix.'target_planet'] : null;
        $this->target_sputnik   = !empty($data[$prefix.'target_sputnik']) ? $data[$prefix.'target_sputnik'] : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    public function setUser($user)
    {
    }
}
