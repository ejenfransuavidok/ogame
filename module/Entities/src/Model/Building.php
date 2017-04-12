<?php

/**
 * 
 * @ author Jigulin V.V.
 * @ 14.03.2017
 * 
 */

namespace Entities\Model;

use RuntimeException;
use Zend\Math\Rand;
use Universe\Model\Entity;

class Building extends Entity
{
    const TABLE_NAME = 'buildings';
    
    public static $BUILDING_RESOURCE = 1;
    
    public static $BUILDING_INDUSTRIAL = 2;
    /**
     * @ час - по ТЗ
     */
    public static $DELTA_REFRESH = 3600;
    
    /**
     * @ Planet
     */
    protected $planet;
    
    /**
     * @ Sputnik
     */
    protected $sputnik;
    
    /**
     * @ User
     */
    protected $owner;
    
    /**
     * @ int
     */
    protected $level;
    
    /**
     * @ BuildinType
     */
    protected $buidingType;
    
    
    public function __construct(
        $name, 
        $description, 
        $planet,
        $sputnik,
        $owner,
        $level,
        $buildingType,
        $update,
        $id=null)
    {
        parent::__construct(self::TABLE_NAME);
        $this->name                 = $name;
        $this->description          = $description;
        $this->planet               = $planet;
        $this->sputnik              = $sputnik;
        $this->owner                = $owner;
        $this->level                = $level;
        $this->buildingType         = $buildingType;
        $this->update               = $update;
        $this->id                   = $id;
    }
    
    public function exchangeArray(array $data, $prefix = '')
    {
        $this->id                   = !empty($data[$prefix.'id'])                   ? $data[$prefix.'id'] : null;
        $this->name                 = !empty($data[$prefix.'name'])                 ? $data[$prefix.'name'] : null;
        $this->description          = !empty($data[$prefix.'description'])          ? $data[$prefix.'description'] : null;
        $this->planet               = !empty($data[$prefix.'planet'])               ? $data[$prefix.'planet'] : null;
        $this->sputnik              = !empty($data[$prefix.'sputnik'])              ? $data[$prefix.'sputnik'] : null;
        $this->owner                = !empty($data[$prefix.'owner'])                ? $data[$prefix.'owner'] : null;
        $this->level                = !empty($data[$prefix.'level'])                ? $data[$prefix.'level'] : null;
        $this->buildingType         = !empty($data[$prefix.'buildingType'])         ? $data[$prefix.'buildingType'] : null;
        $this->update               = !empty($data[$prefix.'update'])               ? $data[$prefix.'update'] : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setPlanet($planet)
    {
        $this->planet = $planet;
    }
    
    public function getPlanet()
    {
        return $this->planet;
    }
    
    public function setSputnik($sputnik)
    {
        $this->sputnik = $sputnik;
    }
    
    public function getSputnik()
    {
        return $this->sputnik;
    }
    
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }
    
    public function getOwner()
    {
        return $this->owner;
    }
    
    public function setLevel($level)
    {
        $this->level = $level;
    }
    
    public function getLevel()
    {
        return $this->level;
    }
    
    public function setBuildingType($buildingType)
    {
        $this->buildingType = $buildingType;
    }
    
    public function getBuildingType()
    {
        return $this->buildingType;
    }
    
    public function setUpdate($update)
    {
        $this->update = $update;
    }
    
    public function getUpdate()
    {
        return $this->update;
    }
    
    public function getProduceElectricity()
    {
        /**
         * @ согласно Таблица данных 0.5.xlsx
         */
        return $this->buildingType->getProduceElectricity() * $this->level * $this->level;
    }
    
    public function getConsumeElectricity()
    {
        /**
         * @ согласно Таблица данных 0.5.xlsx
         */
        return $this->buildingType->getPowerFactor() * $this->level * $this->level;
    }
    
    public function getProduceMetallPerHour()
    {
        /**
         * @ согласно Таблица данных 0.5.xlsx
         */
        return $this->buildingType->getProduceMetall() * $this->level * $this->level;
    }
    
    public function getProduceHeavygasPerHour()
    {
        /**
         * @ согласно Таблица данных 0.5.xlsx
         */
        return $this->buildingType->getProduceHeavygas() * $this->level * $this->level;
    }
    
    public function getProduceOrePerHour()
    {
        /**
         * @ согласно Таблица данных 0.5.xlsx
         */
        return $this->buildingType->getProduceOre() * $this->level * $this->level;
    }
    
    public function getProduceHydroPerHour()
    {
        /**
         * @ согласно Таблица данных 0.5.xlsx
         */
        return $this->buildingType->getProduceHydro() * $this->level * $this->level;
    }
    
    public function getProduceTitanPerHour()
    {
        /**
         * @ согласно Таблица данных 0.5.xlsx
         */
        return $this->buildingType->getProduceTitan() * $this->level * $this->level;
    }
    
    public function getProduceDarkmatterPerHour()
    {
        /**
         * @ согласно Таблица данных 0.5.xlsx
         */
        return $this->buildingType->getProduceDarkmatter() * $this->level * $this->level;
    }
    
    public function getProduceRedmatterPerHour()
    {
        /**
         * @ согласно Таблица данных 0.5.xlsx
         */
        return $this->buildingType->getProduceRedmatter() * $this->level * $this->level;
    }
    
    public function getProduceAntiPerHour()
    {
        /**
         * @ согласно Таблица данных 0.5.xlsx
         */
        return $this->buildingType->getProduceAnti() * $this->level * $this->level;
    }
    
    /**
     * CONSUME
     */
    public function getCapacityMetall()
    {
        /**
         * @ согласно Таблица данных 0.5.xlsx
         */
        return $this->buildingType->getCapacityMetall() * $this->level * $this->level * $this->level;
    }
    
    public function getCapacityHeavygas()
    {
        /**
         * @ согласно Таблица данных 0.5.xlsx
         */
        return $this->buildingType->getCapacityHeavygas() * $this->level * $this->level * $this->level;
    }
    
    public function getCapacityOre()
    {
        /**
         * @ согласно Таблица данных 0.5.xlsx
         */
        return $this->buildingType->getCapacityOre() * $this->level * $this->level  * $this->level;
    }
    
    public function getCapacityHydro()
    {
        /**
         * @ согласно Таблица данных 0.5.xlsx
         */
        return $this->buildingType->getCapacityHydro() * $this->level * $this->level * $this->level;
    }
    
    public function getCapacityTitan()
    {
        /**
         * @ согласно Таблица данных 0.5.xlsx
         */
        return $this->buildingType->getCapacityTitan() * $this->level * $this->level * $this->level;
    }
    
    public function getCapacityDarkmatter()
    {
        /**
         * @ согласно Таблица данных 0.5.xlsx
         */
        return $this->buildingType->getCapacityDarkmatter() * $this->level * $this->level * $this->level;
    }
    
    public function getCapacityRedmatter()
    {
        /**
         * @ согласно Таблица данных 0.5.xlsx
         */
        return $this->buildingType->getCapacityRedmatter() * $this->level * $this->level * $this->level;
    }
    
    public function getCapacityAnti()
    {
        /**
         * @ согласно Таблица данных 0.5.xlsx
         */
        return $this->buildingType->getCapacityAnti() * $this->level * $this->level * $this->level;
    } 
    
    /**
     * 
     */
    
    public function getConsumeAll()
    {
        /*
        $metall         = $this->getConsumeMetall();
        $heavygas       = $this->getConsumeHeavygas();
        $ore            = $this->getConsumeOre();
        $hydro          = $this->getConsumeHydro();
        $titan          = $this->getConsumeTitan();
        $darkmatter     = $this->getConsumeDarkmatter();
        $redmatter      = $this->getConsumeRedmatter();
        $anti           = $this->getConsumeAnti();
        return ($metall + $heavygas + $ore + $hydro + $titan + $darkmatter + $redmatter + $anti);
        */
    }
    
}
