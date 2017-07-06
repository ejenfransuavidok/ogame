<?php

namespace Eventer\Processor;

use Zend\View\Model\ViewModel;
use Entities\Model\Event;
use Eventer\Processor\Finish4DonateProcessor;
use Eventer\Processor\ResourcesCalculator;
use Settings\Model\SettingsRepositoryInterface;
use Entities\Model\EventCommand;
use Entities\Model\Building;
use Entities\Model\BuildingType;
use Entities\Model\BuildingTypeRepository;
use Entities\Model\BuildingRepository;
use Entities\Model\BuildingCommand;
use Entities\Model\User;
use Universe\Model\PlanetRepository;
use Universe\Model\PlanetCommand;
use Universe\Model\SputnikRepository;
use Universe\Model\SputnikCommand;
use Universe\Model\Planet;

class Build4DonateProcessor
{
    
    static public function execute
    (
        User                        $user,
        BuildingType                $buildingType,
        Planet                      $planet,
        BuildingTypeRepository      $buildingTypeRepository,
        BuildingRepository          $buildingRepository,
        BuildingCommand             $buildingCommand,
        PlanetRepository            $planetRepository,
        SputnikRepository           $sputnikRepository,
        PlanetCommand               $planetCommand,
        SputnikCommand              $sputnikCommand,
        SettingsRepositoryInterface $settingsRepository,
        ViewModel                   &$view
    )
    {   
        try{
            $building = $buildingRepository->findOneBy('buildings.buildingType = ' . $buildingType->getId() . ' AND buildings.planet = ' . $planet->getId());
            $level = $building->getLevel() + 1;
            $building->setLevel($level);
            $building = $buildingCommand->updateEntity($building);
        }
        catch(\Exception $e){
            $building = new Building(
                                $buildingType->getName(), 
                                $buildingType->getDescription(),
                                $planet,
                                Null,
                                $user,
                                1,
                                $buildingType,
                                time());
            $building = $buildingCommand->insertEntity($building);
            $level = 1;
        }
        $donateTotal  = Finish4DonateProcessor::getTotalDonateNeedle($settingsRepository, $building->getBuildingType(), $level, 1);
        list($K, $electricity, $metall, $heavygas, $ore, $hydro, $titan, $darkmatter, $redmatter, $anti, $electricity, $areResourcesEnough, $time) =
            ResourcesCalculator::resourcesCalc($building->getBuildingType(), $planet, $level);
        if($donateTotal > $anti){
            $view->setVariable('data', array(
                    'result' => 'NO', 
                    'auth' => 'YES', 
                    'message' => 'Недостаточно доната на счету ! Имеется: ' . $anti . ', нужно: ' . $donateTotal));
        }
        else{
            $planet->setAnti($anti - $donateTotal);
            $planet = $planetCommand->updateEntity($planet);
            $building->setLevel($level);
            $building = $buildingCommand->updateEntity($building);
            $view->setVariable('data', array(
                    'result'    => 'YES', 
                    'auth'      => 'YES', 
                    'message'   => 'Здание успешно куплено за донат'));
        }
        return $view;
    }
    
}
