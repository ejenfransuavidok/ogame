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
     * @ Star
     */
    protected $target_star;
    
    /**
     * @ Planet
     */
    protected $target_planet;
    
    /**
     * @ Sputnik
     */
    protected $target_sputnik;
    
    /**
     * @ BuildingType
     */
    protected $targetBuildingType;
    
    /**
     * @ int
     */
    protected $targetLevel;
    
    
    public function __construct(
        $name, 
        $description, 
        $user, 
        $event_type, 
        $event_begin, 
        $event_end, 
        $target_star, 
        $target_planet, 
        $target_sputnik, 
        $targetBuildingType,
        $targetLevel,
        $id=null)
    {
        parent::__construct(self::TABLE_NAME);
        $this->name                 = $name;
        $this->description          = $description;
        $this->user                 = $user;
        $this->event_type           = $event_type;
        $this->event_begin          = $event_begin;
        $this->event_end            = $event_end;
        $this->target_star          = $target_star;
        $this->target_planet        = $target_planet;
        $this->target_sputnik       = $target_sputnik;
        $this->targetBuildingType   = $targetBuildingType;
        $this->targetLevel          = $targetLevel;
        $this->id                   = $id;
    }
    
    public function exchangeArray(array $data, $prefix = '')
    {
        $this->id                   = !empty($data[$prefix.'id']) ? $data[$prefix.'id'] : null;
        $this->name                 = !empty($data[$prefix.'name']) ? $data[$prefix.'name'] : null;
        $this->description          = !empty($data[$prefix.'description']) ? $data[$prefix.'description'] : null;
        $this->user                 = !empty($data[$prefix.'user']) ? $data[$prefix.'user'] : null;
        $this->event_type           = !empty($data[$prefix.'event_type']) ? $data[$prefix.'event_type'] : null;
        $this->event_begin          = !empty($data[$prefix.'event_begin']) ? $data[$prefix.'event_begin'] : null;
        $this->event_end            = !empty($data[$prefix.'event_end']) ? $data[$prefix.'event_end'] : null;
        $this->target_star          = !empty($data[$prefix.'target_star']) ? $data[$prefix.'target_star'] : null;
        $this->target_planet        = !empty($data[$prefix.'target_planet']) ? $data[$prefix.'target_planet'] : null;
        $this->target_sputnik       = !empty($data[$prefix.'target_sputnik']) ? $data[$prefix.'target_sputnik'] : null;
        $this->targetBuildingType   = !empty($data[$prefix.'targetBuildingType']) ? $data[$prefix.'targetBuildingType'] : null;
        $this->targetLevel          = !empty($data[$prefix.'targetLevel']) ? $data[$prefix.'targetLevel'] : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    public function setUser($user)
    {
        $this->user = $user;
    }
    
    public function getUser()
    {
        return $this->user;
    }
    
    public function setEventType($event_type)
    {
        $this->event_type = $event_type;
    }
    
    public function getEventType()
    {
        return $this->event_type;
    }
    
    public function setEventBegin($event_begin)
    {
        $this->event_begin = $event_begin;
    }
    
    public function getEventBegin()
    {
        return $this->event_begin;
    }
    
    public function setEventEnd($event_end)
    {
        $this->event_end = $event_end;
    }
    
    public function getEventEnd()
    {
        return $this->event_end;
    }
    
    public function setTargetStar($target_star)
    {
        $this->target_star = $target_star;
    }
    
    public function getTargetStar()
    {
        return $this->target_star;
    }
    
    public function setTargetPlanet($target_planet)
    {
        $this->target_planet = $target_planet;
    }
    
    public function getTargetPlanet()
    {
        return $this->target_planet;
    }
    
    public function setTargetSputnik($target_sputnik)
    {
        $this->target_sputnik = $target_sputnik;
    }
    
    public function getTargetSputnik()
    {
        return $this->target_sputnik;
    }
    
    public function getTargetBuildingType()
    {
        return $this->targetBuildingType;
    }
    
    public function setTargetBuildingType($targetBuildingType)
    {
        $this->targetBuildingType = $targetBuildingType;
    }
    
    public function getTargetLevel()
    {
        return $this->targetLevel;
    }
    
    public function setTargetLevel($targetLevel)
    {
        $this->targetLevel = $targetLevel;
    }
    
}
