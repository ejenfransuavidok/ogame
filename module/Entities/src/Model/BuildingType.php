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
use Entities\Model\BuildingRepository;
use Universe\Model\Planet;
use Universe\Model\Sputnik;
use App\Classes\TimeUtils;

class BuildingType extends Entity
{
    const TABLE_NAME = 'building_types';
    
    /**
     * @ float
     */
    protected $factor;
    
    /**
     * @ int
     */
    protected $building_acceleration;
    
    /**
     * @ int
     */
    protected $produce_metall;
    
    /**
     * @ int
     */
    protected $produce_heavygas;
    
    /**
     * @ int
     */
    protected $produce_ore;
    
    /**
     * @ int
     */
    protected $produce_hydro;
    
    /**
     * @ int
     */
    protected $produce_titan;
    
    /**
     * @ int
     */
    protected $produce_darkmatter;
    
    /**
     * @ int
     */
    protected $produce_redmatter;
    
    /**
     * @ int
     */
    protected $produce_anti;
    
    /**
     * @ int
     */
    protected $produce_electricity;
    
    /**
     * @ int
     */
    protected $consume_metall;
    
    /**
     * @ int
     */
    protected $consume_heavygas;
    
    /**
     * @ int
     */
    protected $consume_ore;
    
    /**
     * @ int
     */
    protected $consume_hydro;
    
    /**
     * @ int
     */
    protected $consume_titan;
    
    /**
     * @ int
     */
    protected $consume_darkmatter;
    
    /**
     * @ int
     */
    protected $consume_redmatter;
    
    /**
     * @ int
     */
    protected $consume_anti;
    
    /**
     * @ int
     */
    protected $consume_electricity;
    
    /**
     * @ int
     */
    protected $type;
    
    /**
     * @ string
     */
    protected $picture;
    
    /**
     * @ int
     */
    protected $capacity_metall;
    
    /**
     * @ int
     */
    protected $capacity_heavygas;
    
    /**
     * @ int
     */
    protected $capacity_ore;
    
    /**
     * @ int
     */
    protected $capacity_hydro;
    
    /**
     * @ int
     */
    protected $capacity_titan;
    
    /**
     * @ int
     */
    protected $capacity_darkmatter;
    
    /**
     * @ int
     */
    protected $capacity_redmatter;
    
    
    public function __construct(
        $name, 
        $description, 
        $type,
        $factor,
        $building_acceleration,
        $produce_metall,
        $produce_heavygas,
        $produce_ore,
        $produce_hydro,
        $produce_titan,
        $produce_darkmatter,
        $produce_redmatter,
        $produce_anti,
        $produce_electricity,
        $consume_metall,
        $consume_heavygas,
        $consume_ore,
        $consume_hydro,
        $consume_titan,
        $consume_darkmatter,
        $consume_redmatter,
        $consume_anti,
        $consume_electricity,
        $capacity_metall,
        $capacity_heavygas,
        $capacity_ore,
        $capacity_hydro,
        $capacity_titan,
        $capacity_darkmatter,
        $capacity_redmatter,
        $picture,
        $id=null)
    {
        parent::__construct(self::TABLE_NAME);
        $this->name                 = $name;
        $this->description          = $description;
        $this->type                 = $type;
        $this->factor               = $factor;
        $this->building_acceleration= $building_acceleration;
        $this->produce_metall       = $produce_metall;
        $this->produce_heavygas     = $produce_heavygas;
        $this->produce_ore          = $produce_ore;
        $this->produce_hydro        = $produce_hydro;
        $this->produce_titan        = $produce_titan;
        $this->produce_darkmatter   = $produce_darkmatter;
        $this->produce_redmatter    = $produce_redmatter;
        $this->produce_anti         = $produce_anti;
        $this->produce_electricity  = $produce_electricity;
        $this->consume_metall       = $consume_metall;
        $this->consume_heavygas     = $consume_heavygas;
        $this->consume_ore          = $consume_ore;
        $this->consume_hydro        = $consume_hydro;
        $this->consume_titan        = $consume_titan;
        $this->consume_darkmatter   = $consume_darkmatter;
        $this->consume_redmatter    = $consume_redmatter;
        $this->consume_anti         = $consume_anti;
        $this->consume_electricity  = $consume_electricity;
        $this->capacity_metall      = $capacity_metall;
        $this->capacity_heavygas    = $capacity_heavygas;
        $this->capacity_ore         = $capacity_ore;
        $this->capacity_hydro       = $capacity_hydro;
        $this->capacity_titan       = $capacity_titan;
        $this->capacity_darkmatter  = $capacity_darkmatter;
        $this->capacity_redmatter   = $capacity_redmatter;
        $this->picture              = $picture;
        $this->id                   = $id;
    }
    
    public function exchangeArray(array $data, $prefix = '')
    {
        $this->id                   = !empty($data[$prefix.'id'])                   ? $data[$prefix.'id'] : null;
        $this->name                 = !empty($data[$prefix.'name'])                 ? $data[$prefix.'name'] : null;
        $this->description          = !empty($data[$prefix.'description'])          ? $data[$prefix.'description'] : null;
        $this->type                 = !empty($data[$prefix.'type'])                 ? $data[$prefix.'type'] : null;
        $this->factor               = !empty($data[$prefix.'factor'])               ? $data[$prefix.'factor'] : null;
        $this->building_acceleration= !empty($data[$prefix.'building_acceleration'])? $data[$prefix.'building_acceleration'] : null;
        
        $this->produce_metall       = !empty($data[$prefix.'produce_metall'])       ? $data[$prefix.'produce_metall'] : null;
        $this->produce_heavygas     = !empty($data[$prefix.'produce_heavygas'])     ? $data[$prefix.'produce_heavygas'] : null;
        $this->produce_ore          = !empty($data[$prefix.'produce_ore'])          ? $data[$prefix.'produce_ore'] : null;
        $this->produce_hydro        = !empty($data[$prefix.'produce_hydro'])        ? $data[$prefix.'produce_hydro'] : null;
        $this->produce_titan        = !empty($data[$prefix.'produce_titan'])        ? $data[$prefix.'produce_titan'] : null;
        $this->produce_darkmatter   = !empty($data[$prefix.'produce_darkmatter'])   ? $data[$prefix.'produce_darkmatter'] : null;
        $this->produce_redmatter    = !empty($data[$prefix.'produce_redmatter'])    ? $data[$prefix.'produce_redmatter'] : null;
        $this->produce_anti         = !empty($data[$prefix.'produce_anti'])         ? $data[$prefix.'produce_anti'] : null;
        $this->produce_electricity  = !empty($data[$prefix.'produce_electricity'])  ? $data[$prefix.'produce_electricity'] : null;
        
        $this->consume_metall       = !empty($data[$prefix.'consume_metall'])       ? $data[$prefix.'consume_metall'] : null;
        $this->consume_heavygas     = !empty($data[$prefix.'consume_heavygas'])     ? $data[$prefix.'consume_heavygas'] : null;
        $this->consume_ore          = !empty($data[$prefix.'consume_ore'])          ? $data[$prefix.'consume_ore'] : null;
        $this->consume_hydro        = !empty($data[$prefix.'consume_hydro'])        ? $data[$prefix.'consume_hydro'] : null;
        $this->consume_titan        = !empty($data[$prefix.'consume_titan'])        ? $data[$prefix.'consume_titan'] : null;
        $this->consume_darkmatter   = !empty($data[$prefix.'consume_darkmatter'])   ? $data[$prefix.'consume_darkmatter'] : null;
        $this->consume_redmatter    = !empty($data[$prefix.'consume_redmatter'])    ? $data[$prefix.'consume_redmatter'] : null;
        $this->consume_anti         = !empty($data[$prefix.'consume_anti'])         ? $data[$prefix.'consume_anti'] : null;
        $this->consume_electricity  = !empty($data[$prefix.'consume_electricity'])  ? $data[$prefix.'consume_electricity'] : null;
        
        $this->capacity_metall      = !empty($data[$prefix.'capacity_metall'])      ? $data[$prefix.'capacity_metall'] : null;
        $this->capacity_heavygas    = !empty($data[$prefix.'capacity_heavygas'])    ? $data[$prefix.'capacity_heavygas'] : null;
        $this->capacity_ore         = !empty($data[$prefix.'capacity_ore'])         ? $data[$prefix.'capacity_ore'] : null;
        $this->capacity_hydro       = !empty($data[$prefix.'capacity_hydro'])       ? $data[$prefix.'capacity_hydro'] : null;
        $this->capacity_titan       = !empty($data[$prefix.'capacity_titan'])       ? $data[$prefix.'capacity_titan'] : null;
        $this->capacity_darkmatter  = !empty($data[$prefix.'capacity_darkmatter'])  ? $data[$prefix.'capacity_darkmatter'] : null;
        $this->capacity_redmatter   = !empty($data[$prefix.'capacity_redmatter'])   ? $data[$prefix.'capacity_redmatter'] : null;
        
        $this->picture              = !empty($data[$prefix.'picture']) ? $data[$prefix.'picture'] : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    public function setType($type)
    {
        $this->type = $type;
    }
    
    public function getType()
    {
        return $this->type;
    }
    
    public function setFactor($factor)
    {
        $this->factor = $factor;
    }
    
    public function getFactor()
    {
        return $this->factor;
    }
    
    public function setBuildingAcceleration($building_acceleration)
    {
        $this->building_acceleration = $building_acceleration;
    }
    
    public function getBuildingAcceleration()
    {
        return $this->building_acceleration;
    }
    
    public function setProduceMetall($produce_metall)
    {
        $this->produce_metall = $produce_metall;
    }
    
    public function getProduceMetall()
    {
        return $this->produce_metall != null ? $this->produce_metall : 0;
    }
    
    public function setProduceHeavygas($produce_heavygas)
    {
        $this->produce_heavygas = $produce_heavygas;
    }
    
    public function getProduceHeavygas()
    {
        return $this->produce_heavygas != null ? $this->produce_heavygas : 0;
    }
    
    public function setProduceOre($produce_ore)
    {
        $this->produce_ore = $produce_ore;
    }
    
    public function getProduceOre()
    {
        return $this->produce_ore != null ? $this->produce_ore : 0;
    }
    
    public function setProduceHydro($produce_hydro)
    {
        $this->produce_hydro = $produce_hydro;
    }
    
    public function getProduceHydro()
    {
        return $this->produce_hydro != null ? $this->produce_hydro : 0;
    }
    
    public function setProduceTitan($produce_titan)
    {
        $this->produce_titan = $produce_titan;
    }
    
    public function getProduceTitan()
    {
        return $this->produce_titan != null ? $this->produce_titan : 0;
    }
    
    public function setProduceDarkmatter($produce_darkmatter)
    {
        $this->produce_darkmatter = $produce_darkmatter;
    }
    
    public function getProduceDarkmatter()
    {
        return $this->produce_darkmatter != null ? $this->produce_darkmatter : 0;
    }
    
    public function setProduceRedmatter($produce_redmatter)
    {
        $this->produce_redmatter = $produce_redmatter;
    }
    
    public function getProduceRedmatter()
    {
        return $this->produce_redmatter != null ? $this->produce_redmatter : 0;
    }
    
    public function setProduceAnti($produce_anti)
    {
        $this->produce_anti = $produce_anti;
    }
    
    public function getProduceAnti()
    {
        return $this->produce_anti != null ? $this->produce_anti : 0;
    }
    
    public function setProduceElectricity($produce_electricity)
    {
        $this->produce_electricity = $produce_electricity;
    }
    
    public function getProduceElectricity()
    {
        return $this->produce_electricity != null ? $this->produce_electricity : 0;
    }
    
    /*consume*/
    
    public function setConsumeMetall($consume_metall)
    {
        $this->consume_metall = $consume_metall;
    }
    
    public function getConsumeMetall()
    {
        return $this->consume_metall != null ? $this->consume_metall : 0;
    }
    
    public function setConsumeHeavygas($consume_heavygas)
    {
        $this->consume_heavygas = $consume_heavygas;
    }
    
    public function getConsumeHeavygas()
    {
        return $this->consume_heavygas != null ? $this->consume_heavygas : 0;
    }
    
    public function setConsumeOre($consume_ore)
    {
        $this->consume_ore = $consume_ore;
    }
    
    public function getConsumeOre()
    {
        return $this->consume_ore != null ? $this->consume_ore : 0;
    }
    
    public function setConsumeHydro($consume_hydro)
    {
        $this->consume_hydro = $consume_hydro;
    }
    
    public function getConsumeHydro()
    {
        return $this->consume_hydro != null ? $this->consume_hydro : 0;
    }
    
    public function setConsumeTitan($consume_titan)
    {
        $this->consume_titan = $consume_titan;
    }
    
    public function getConsumeTitan()
    {
        return $this->consume_titan != null ? $this->consume_titan : 0;
    }
    
    public function setConsumeDarkmatter($consume_darkmatter)
    {
        $this->consume_darkmatter = $consume_darkmatter;
    }
    
    public function getConsumeDarkmatter()
    {
        return $this->consume_darkmatter != null ? $this->consume_darkmatter : 0;
    }
    
    public function setConsumeRedmatter($consume_redmatter)
    {
        $this->consume_redmatter = $consume_redmatter;
    }
    
    public function getConsumeRedmatter()
    {
        return $this->consume_redmatter != null ? $this->consume_redmatter : 0;
    }
    
    public function setConsumeAnti($consume_anti)
    {
        $this->consume_anti = $consume_anti;
    }
    
    public function getConsumeAnti()
    {
        return $this->consume_anti != null ? $this->consume_anti : 0;
    }
    
    public function setConsumeElectricity($consume_electricity)
    {
        $this->consume_electricity = $consume_electricity;
    }
    
    public function getConsumeElectricity()
    {
        return $this->consume_electricity != null ? $this->consume_electricity : 0;
    }

    public function setCapacityMetall($capacity_metall)
    {
        $this->capacity_metall = $capacity_metall;
    }
    
    public function getCapacityMetall()
    {
        return $this->capacity_metall;
    }
    
    public function setCapacityHeavygas($capacity_heavygas)
    {
        $this->capacity_heavygas = $capacity_heavygas;
    }
    
    public function getCapacityHeavygas()
    {
        return $this->capacity_heavygas;
    }
    
    public function setCapacityOre($capacity_ore)
    {
        $this->capacity_ore = $capacity_ore;
    }
    
    public function getCapacityOre()
    {
        return $this->capacity_ore;
    }
    
    public function setCapacityHydro($capacity_hydro)
    {
        $this->capacity_hydro = $capacity_hydro;
    }
    
    public function getCapacityHydro()
    {
        return $this->capacity_hydro;
    }
    
    public function setCapacityTitan($capacity_titan)
    {
        $this->capacity_titan = $capacity_titan;
    }
    
    public function getCapacityTitan()
    {
        return $this->capacity_titan;
    }
    
    public function setCapacityDarkmatter($capacity_darkmatter)
    {
        $this->capacity_darkmatter = $capacity_darkmatter;
    }
    
    public function getCapacityDarkmatter()
    {
        return $this->capacity_darkmatter;
    }
    
    public function setCapacityRedmatter($capacity_redmatter)
    {
        $this->capacity_redmatter = $capacity_redmatter;
    }
    
    public function getCapacityRedmatter()
    {
        return $this->capacity_redmatter;
    }

    public function setPicture($picture)
    {
        $this->picture = $picture;
    }
    
    public function getPicture()
    {
        return $this->picture;
    }
    
    public function getConsumeAll()
    {
        $metall         = $this->getConsumeMetall();
        $heavygas       = $this->getConsumeHeavygas();
        $ore            = $this->getConsumeOre();
        $hydro          = $this->getConsumeHydro();
        $titan          = $this->getConsumeTitan();
        $darkmatter     = $this->getConsumeDarkmatter();
        $redmatter      = $this->getConsumeRedmatter();
        $anti           = $this->getConsumeAnti();
        return ($metall + $heavygas + $ore + $hydro + $titan + $darkmatter + $redmatter + $anti);
    }
    
    public function getCurrentBuilding($currentPlanet, $currentSputnik, $buildingRepository)
    {
        if($currentPlanet)
            $criteria = 'buildings.planet = ' . $currentPlanet->getId() . ' AND buildings.name = "' . $this->name . '"';
        else
            $criteria = 'buildings.sputnik = ' . $currentPlanet->getId() . ' AND buildings.name = "' . $this->name . '"';
        try{
            if($building = $buildingRepository->findOneBy($criteria)){
                return $building;
            }
            else{
                return false;
            }
        }
        catch(\Exception $e){
            return false;
        }
    }
    
    public function getCurrentLevel($currentPlanet, $currentSputnik, $buildingRepository)
    {
        if($building = $this->getCurrentBuilding($currentPlanet, $currentSputnik, $buildingRepository)){
            return $building->getLevel();
        }
        else{
            return 0;
        }
    }
    
    public function getBuildingPeriod($currentPlanet, $currentSputnik, $buildingRepository)
    {
        $level = $this->getCurrentLevel($currentPlanet, $currentSputnik, $buildingRepository) + 1;
        $K = $this->factor * $level;
        $time = intval(ceil($K * $this->getConsumeAll() / 30));
        return TimeUtils::time2Interval($time);
    }
    
}
