<?php

namespace Entities\Model;

use Universe\Model\Entity;

class SpaceSheep extends Entity
{
    /**
     * 
     * @ int
     * @ скорость
     * 
     */
    protected $speed;
    
    /**
     * 
     * @ int
     * @ вместительность
     * 
     */
    protected $capacity;
    
    /**
     * 
     * @ int fuel_consumption
     * @ удельное потребление топлива
     * 
     */
    protected $fuel_consumption;
    
    /**
     * 
     * @ int fuel_tank_size
     * @ размер топливных баков
     * 
     */
    protected $fuel_tank_size;
    
    /**
     * 
     * @ int attak_power
     * @ мощность атаки
     * 
     */
    protected $attak_power;
    
    /**
     * 
     * @ int rate_of_fire
     * @ скорость стрельбы
     * 
     */
    protected $rate_of_fire;
    
    /**
     * 
     * @ int the_number_of_attak_targets
     * @ количество целей атаки
     * 
     */
    protected $the_number_of_attak_targets;
    
    /**
     * 
     * @ int sheep_size
     * @ размер корабля
     * 
     */
    protected $sheep_size;
    
    /**
     * 
     * @ int protection
     * @ защита
     * 
     */
    protected $protection;
    
    /**
     * 
     * @ int number_of_guns
     * @ количество вооружения
     * 
     */
    protected $number_of_guns;
    
    /**
     * 
     * @ int construction time
     * @ время строительства
     * 
     */
    protected $construction_time;
    
    /**
     * 
     * @ int fuel rest
     * @ объем оставшегося топлива в баке
     * 
     */
    protected $fuel_rest;
    
    /**
     * 
     * @ Galaxy
     * @ текущая галактика
     * 
     */
    protected $galaxy;
    
    /**
     * 
     * @ PlanetSystem
     * @ текущая планетная система
     * 
     */
    protected $planetSystem;
    
    /**
     * 
     * @ Star
     * @ текущая звезда
     * 
     */
    protected $star;
    
    /**
     * 
     * @ Planet
     * @ текущая планета
     * 
     */
    protected $planet;
    
    /**
     * 
     * @ Sputnik
     * @ текущий спутник
     * 
     */
    protected $sputnik;
    
    /**
     * 
     * @ User
     * @ владелец
     * 
     */
    protected $owner;
    
    /**
     * 
     * @ Event
     * @ событие связанное с кораблем
     */
    protected $event;
    
    /**
     * 
     * @ int
     * @ дистанция проходимая кораблем на остатке топлива
     * 
     */
    private $distance;
    
    /**
     * 
     * @ int
     * @ коэффициент для вычисления пробега
     * 
     */
    private $fuel_factor;
    
    /**
     * 
     * @ int
     * @ коэффициент для вычисления времени пробега
     * 
     */
    private $time_factor;
    
    const TABLE_NAME = 'spacesheeps';
    
    public function __construct(
        $name, 
        $description, 
        $speed, 
        $capacity, 
        $fuel_consumption, 
        $fuel_tank_size, 
        $attak_power, 
        $rate_of_fire, 
        $the_number_of_attak_targets,
        $sheep_size,
        $protection,
        $number_of_guns,
        $construction_time,
        $fuel_rest,
        $galaxy,
        $planetSystem,
        $star,
        $planet,
        $sputnik,
        $owner,
        $event,
        $id=null)
    {
        parent::__construct(self::TABLE_NAME);
        $this->name                         = $name;
        $this->description                  = $description;
        $this->speed                        = $speed;
        $this->capacity                     = $capacity;
        $this->fuel_consumption             = $fuel_consumption;
        $this->fuel_tank_size               = $fuel_tank_size;
        $this->attak_power                  = $attak_power;
        $this->rate_of_fire                 = $rate_of_fire;
        $this->the_number_of_attak_targets  = $the_number_of_attak_targets;
        $this->sheep_size                   = $sheep_size;
        $this->protection                   = $protection;
        $this->number_of_guns               = $number_of_guns;
        $this->construction_time            = $construction_time;
        $this->fuel_rest                    = $fuel_rest;
        $this->galaxy                       = $galaxy;
        $this->planetSystem                 = $planetSystem;
        $this->star                         = $star;
        $this->planet                       = $planet;
        $this->sputnik                      = $sputnik;
        $this->owner                        = $owner;
        $this->event                        = $event;
        $this->id                           = $id;
        
        /**
         * no db
         */
        $this->fuel_factor                  = 1;
        $this->time_factor                  = 1;
    }
    
    public function exchangeArray(array $data, $prefix = '')
    {
        $this->id                           = !empty($data[$prefix.'id']) ? $data[$prefix.'id'] : null;
        $this->name                         = !empty($data[$prefix.'name']) ? $data[$prefix.'name'] : null;
        $this->description                  = !empty($data[$prefix.'description']) ? $data[$prefix.'description'] : null;
        $this->speed                        = !empty($data[$prefix.'speed']) ? $data[$prefix.'speed'] : null;
        $this->capacity                     = !empty($data[$prefix.'capacity']) ? $data[$prefix.'capacity'] : null;
        $this->fuel_consumption             = !empty($data[$prefix.'fuel_consumption']) ? $data[$prefix.'fuel_consumption'] : null;
        $this->fuel_tank_size               = !empty($data[$prefix.'fuel_tank_size']) ? $data[$prefix.'fuel_tank_size'] : null;
        $this->attak_power                  = !empty($data[$prefix.'attak_power']) ? $data[$prefix.'attak_power'] : null;
        $this->rate_of_fire                 = !empty($data[$prefix.'rate_of_fire']) ? $data[$prefix.'rate_of_fire'] : null;
        $this->the_number_of_attak_targets  = !empty($data[$prefix.'the_number_of_attak_targets']) ? $data[$prefix.'the_number_of_attak_targets'] : null;
        $this->sheep_size                   = !empty($data[$prefix.'sheep_size']) ? $data[$prefix.'sheep_size'] : null;
        $this->protection                   = !empty($data[$prefix.'protection']) ? $data[$prefix.'protection'] : null;
        $this->number_of_guns               = !empty($data[$prefix.'number_of_guns']) ? $data[$prefix.'number_of_guns'] : null;
        $this->construction_time            = !empty($data[$prefix.'construction_time']) ? $data[$prefix.'construction_time'] : null;
        $this->fuel_rest                    = !empty($data[$prefix.'fuel_rest']) ? $data[$prefix.'fuel_rest'] : null;
        $this->galaxy                       = !empty($data[$prefix.'galaxy']) ? $data[$prefix.'galaxy'] : null;
        $this->planetSystem                 = !empty($data[$prefix.'planetSystem']) ? $data[$prefix.'planetSystem'] : null;
        $this->star                         = !empty($data[$prefix.'star']) ? $data[$prefix.'star'] : null;
        $this->planet                       = !empty($data[$prefix.'planet']) ? $data[$prefix.'planet'] : null;
        $this->sputnik                      = !empty($data[$prefix.'sputnik']) ? $data[$prefix.'sputnik'] : null;
        $this->owner                        = !empty($data[$prefix.'owner']) ? $data[$prefix.'owner'] : null;
        $this->event                        = !empty($data[$prefix.'event']) ? $data[$prefix.'event'] : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    public function setSpeed($speed)
    {
        $this->speed = $speed;
    }
    
    public function getSpeed()
    {
        return $this->speed;
    }
    
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;
    }
    
    public function getCapacity()
    {
        return $this->capacity;
    }
    
    public function setFuelConsumption($fuel_consumption)
    {
        $this->fuel_consumption = $fuel_consumption;
    }
    
    public function getFuelConsumption()
    {
        return $this->fuel_consumption;
    }
    
    public function setFuelTankSize($fuel_tank_size)
    {
        $this->fuel_tank_size = $fuel_tank_size;
    }
    
    public function getFuelTankSize()
    {
        return $this->fuel_tank_size;
    }
    
    public function setAttakPower($attak_power)
    {
        $this->attak_power = $attak_power;
    }
    
    public function getAttakPower()
    {
        return $this->attak_power;
    }
    
    public function setRateOfFire($rate_of_fire)
    {
        $this->rate_of_fire = $rate_of_fire;
    }
    
    public function getRateOfFire()
    {
        return $this->rate_of_fire;
    }
    
    public function setTheNumberOfAttakTarget($the_number_of_attak_targets)
    {
        $this->the_number_of_attak_targets = $the_number_of_attak_targets;
    }
    
    public function getTheNumberOfAttakTarget()
    {
        return $this->the_number_of_attak_targets;
    }
    
    public function setSheepSize($sheep_size)
    {
        $this->sheep_size = $sheep_size;
    }
    
    public function getSheepSize()
    {
        return $this->sheep_size;
    }
    
    public function setProtection($protection)
    {
        $this->protection = $protection;
    }
    
    public function getProtection()
    {
        return $this->protection;
    }
    
    public function setNumberOfGuns($number_of_guns)
    {
        $this->number_of_guns = $number_of_guns;
    }
    
    public function getNumberOfGuns()
    {
        return $this->number_of_guns;
    }
    
    public function setConstructionTime($construction_time)
    {
        $this->construction_time = $construction_time;
    }
    
    public function getConstructionTime()
    {
        return $this->construction_time;
    }
    
    public function setFuelRest($fuel_rest)
    {
        $this->fuel_rest = $fuel_rest;
    }
    
    public function getFuelRest()
    {
        return $this->fuel_rest;
    }
    
    public function setGalaxy($galaxy)
    {
        $this->galaxy = $galaxy;
    }
    
    public function getGalaxy()
    {
        return $this->galaxy;
    }
    
    public function setPlanetSystem($planetSystem)
    {
        $this->planetSystem = $planetSystem;
    }
    
    public function getPlanetSystem()
    {
        return $this->planetSystem;
    }
    
    public function setStar($star)
    {
        $this->star = $star;
    }
    
    public function getStar()
    {
        return $this->star;
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
    
    public function setEvent($event)
    {
        $this->event = $event;
    }
    
    public function getEvent()
    {
        return $this->event;
    }
    
    public function setDistance($distance)
    {
        $this->distance = $distance;
    }
    
    public function getDistance()
    {
        return $this->distance;
    }
    
    public function setFuelFactor($fuel_factor)
    {
        $this->fuel_factor = $fuel_factor;
    }
    
    public function getFuelFactor()
    {
        return $this->fuel_factor;
    }
    
    public function calcDistance($fuel_factor)
    {
        $this->setFuelFactor($fuel_factor);
        $this->distance = ceil(($this->fuel_factor * $this->fuel_rest) / ($this->fuel_consumption * $this->capacity));
        return $this->distance;
    }
    
    public function calcSpendFuelByDistance($distance)
    {
        return ceil($distance * $this->fuel_consumption * $this->capacity / $this->fuel_factor);
    }
    
    public function setTimeFactor($time_factor)
    {
        $this->time_factor = $time_factor;
    }
    
    public function getTimeFactor()
    {
        return $this->time_factor;
    }
    
    public function calcTime($time_factor, $distance)
    {
        return ceil($time_factor * $distance / $this->speed);
    }
    
}
