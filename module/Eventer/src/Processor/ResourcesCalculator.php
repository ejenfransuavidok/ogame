<?php

namespace Eventer\Processor;

use Entities\Model\BuildingType;
use Universe\Model\Planet;
use Entities\Model\EventRepository;
use Entities\Classes\EventTypes;

class ResourcesCalculator
{
    
    static public function getResourcesCoeff(BuildingType $buildingType, $level)
    {
        return pow($buildingType->getPriceFactor(), $level - 1);
    }
    
    static public function getBuildingTime(BuildingType $buildingType, $level)
    {
        $K = self::getResourcesCoeff($buildingType, $level);
        return
            intval(ceil($K * $buildingType->getConsumeAll() / 30));
    }
    
    static public function resourcesCalc(BuildingType $buildingType, Planet $planet, $level)
    {
        $K              = self::getResourcesCoeff($buildingType, $level);
        $metall         = $planet->getMetall()      - $buildingType->getConsumeMetall()     * $K;
        $heavygas       = $planet->getHeavyGas()    - $buildingType->getConsumeHeavygas()   * $K;
        $ore            = $planet->getOre()         - $buildingType->getConsumeOre()        * $K;
        $hydro          = $planet->getHydro()       - $buildingType->getConsumeHydro()      * $K;
        $titan          = $planet->getTitan()       - $buildingType->getConsumeTitan()      * $K;
        $darkmatter     = $planet->getDarkmatter()  - $buildingType->getConsumeDarkmatter() * $K;
        $redmatter      = $planet->getRedmatter()   - $buildingType->getConsumeRedmatter()  * $K;
        $anti           = $planet->getAnti()        - $buildingType->getConsumeAnti()       * $K;
        $electricity    = $planet->getElectricity() - $buildingType->getPowerFactor()       * $K;
        /**
         * @ хватит ли ресурсов на планете
         */
        $areResourcesEnough =
            (
            $metall      >= 0 && 
            $heavygas    >= 0 && 
            $ore         >= 0 && 
            $hydro       >= 0 && 
            $titan       >= 0 && 
            $darkmatter  >= 0 && 
            $redmatter   >= 0 &&
            $anti        >= 0 &&
            $electricity >= 0
            );
        /**
         * @ время на строительство
         */
        $time = self::getBuildingTime($buildingType, $level);
        
        return array($K, $electricity, $metall, $heavygas, $ore, $hydro, $titan, $darkmatter, $redmatter, $anti, $electricity, $areResourcesEnough, $time);
    }
    
    static public function resourcesCalcGetBack(BuildingType $buildingType, Planet $planet, $level)
    {
        $K              = self::getResourcesCoeff($buildingType, $level);
        $electricity    = $planet->getElectricity() + $buildingType->getPowerFactor()       * $K;
        $metall         = $planet->getMetall()      + $buildingType->getConsumeMetall()     * $K;
        $heavygas       = $planet->getHeavyGas()    + $buildingType->getConsumeHeavygas()   * $K;
        $ore            = $planet->getOre()         + $buildingType->getConsumeOre()        * $K;
        $hydro          = $planet->getHydro()       + $buildingType->getConsumeHydro()      * $K;
        $titan          = $planet->getTitan()       + $buildingType->getConsumeTitan()      * $K;
        $darkmatter     = $planet->getDarkmatter()  + $buildingType->getConsumeDarkmatter() * $K;
        $redmatter      = $planet->getRedmatter()   + $buildingType->getConsumeRedmatter()  * $K;
        $anti           = $planet->getAnti()        + $buildingType->getConsumeAnti()       * $K;
        return array($K, $electricity, $metall, $heavygas, $ore, $hydro, $titan, $darkmatter, $redmatter, $anti);
    }
    
    static public function getConsumedResources(BuildingType $buildingType, $level)
    {
        $K              = self::getResourcesCoeff($buildingType, $level);
        $electricity    = $buildingType->getPowerFactor()       * $K;
        $metall         = $buildingType->getConsumeMetall()     * $K;
        $heavygas       = $buildingType->getConsumeHeavygas()   * $K;
        $ore            = $buildingType->getConsumeOre()        * $K;
        $hydro          = $buildingType->getConsumeHydro()      * $K;
        $titan          = $buildingType->getConsumeTitan()      * $K;
        $darkmatter     = $buildingType->getConsumeDarkmatter() * $K;
        $redmatter      = $buildingType->getConsumeRedmatter()  * $K;
        $anti           = $buildingType->getConsumeAnti()       * $K;
        return array($electricity, $metall, $heavygas, $ore, $hydro, $titan, $darkmatter, $redmatter, $anti);
    }
    
    static public function getElectricityOnBuildings(Planet $planet, EventRepository $eventRepository)
    {
        $result = 0;
        if($events = EventTypes::getBuildingEventsByPlanet($eventRepository, $planet)){
            foreach($events as $event){
                $buildingType = $event->getTargetBuildingType();
                $level = $event->getTargetLevel();
                list($electricity, $metall, $heavygas, $ore, $hydro, $titan, $darkmatter, $redmatter, $anti) =
                    self::getConsumedResources($buildingType, $level);
                $result += intval($electricity);
            }
        }
        return $result;
    }
    
}
