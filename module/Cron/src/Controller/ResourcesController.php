<?php

namespace Cron\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\AdapterInterface;
use Universe\Model\PlanetRepository;
use Universe\Model\PlanetCommand;
use Universe\Model\SputnikRepository;
use Entities\Model\UserRepository;
use Entities\Model\Building;
use Entities\Model\BuildingRepository;
use Entities\Model\BuildingCommand;
use Universe\Classes\PlanetCapacity;

class ResourcesController extends AbstractActionController
{
    
    /**
     * @ PlanetRepository
     */
    protected $planetRepository;
    
    /**
     * @ PlanetCommand
     */
    protected $planetCommand; 
    
    /**
     * @ SputnikRepository
     */
    protected $sputnikRepository;
    
    /**
     * @ UserRepository
     */
    protected $userRepository;
    
    /**
     * @ BuildingRepository
     */
    protected $buildingRepository;
    
    /**
     * @ BuildingCommand
     */
    protected $buildingCommand;
    
    /**
     * @ PlanetCapacity
     */
    protected $planetCapacity;
    
    
    
    public function __construct(
        AdapterInterface    $db, 
        PlanetRepository    $planetRepository,
        PlanetCommand       $planetCommand,
        SputnikRepository   $sputnikRepository,
        UserRepository      $userRepository,
        BuildingRepository  $buildingRepository,
        BuildingCommand     $buildingCommand,
        PlanetCapacity      $planetCapacity
        )
    {
        $this->dbAdapter = $db;
        $this->planetRepository = $planetRepository;
        $this->planetCommand = $planetCommand;
        $this->sputnikRepository = $sputnikRepository;
        $this->userRepository = $userRepository;
        $this->buildingRepository = $buildingRepository;
        $this->buildingCommand = $buildingCommand;
        $this->planetCapacity = $planetCapacity;
    }
    
    public function srccalcAction()
    {
        $view = new ViewModel([]);
        try{
            /**
             * расчет прироста ресурсов юзеров
             */
            $now = time();
            /**
             * 1. выберем всех юзеров
             */
            foreach($this->userRepository->findAllEntities() as $user){
                /**
                 * 2. выберем все планеты юзеров
                 */
                foreach($this->planetRepository->findBy('planets.owner = ' . $user->getId()) as $planet){
                    $type = $planet->getType();
                    /**
                     * 3. выберем все ресурсные здания на планете
                     */
                    $electricity = 0;
                    foreach($this->buildingRepository->findBy('buildings.planet = ' . $planet->getId() . ' AND building_types_alias.type = ' . Building::$BUILDING_RESOURCE) as $building){
                        $update = $building->getUpdate();
                        $buildingType = $building->getBuildingType();
                        
                        $electricity = $electricity + $building->getProduceElectricity() - $building->getConsumeElectricity();
                        /**
                         * пришло время для обновления
                         */
                        if($now > $update){
                            $K          = floatval(ceil(($now - $update) / Building::$DELTA_REFRESH));
                            $metall     = intval(ceil($K * $type->getMetall() * $building->getProduceMetallPerHour() 
                                + $planet->getMetall()));
                            $heavygas   = intval(ceil($K * $type->getHeavyGas() * $building->getProduceHeavygasPerHour() 
                                + $planet->getHeavyGas()));
                            $ore        = intval(ceil($K * $type->getOre() * $building->getProduceOrePerHour()
                                + $planet->getOre()));
                            $hydro      = intval(ceil($K * $type->getHydro() * $building->getProduceHydroPerHour()
                                + $planet->getHydro()));
                            $titan      = intval(ceil($K * $type->getTitan() * $building->getProduceTitanPerHour()
                                + $planet->getTitan()));
                            $darkmatter = intval(ceil($K * $type->getDarkmatter() * $building->getProduceDarkmatterPerHour()
                                + $planet->getDarkmatter()));
                            $redmatter  = intval(ceil($K * $type->getRedmatter() * $building->getProduceRedmatterPerHour()
                                + $planet->getRedmatter()));
                            $anti       = intval(ceil($K * $type->getAnti() * $building->getProduceAntiPerHour()
                                + $planet->getAnti()));
                            /* end */
                            $building->setUpdate($now + Building::$DELTA_REFRESH / 600);
                            $building = $this->buildingCommand->updateEntity($building);
                            
                            $metall_limit       = $this->planetCapacity->getMetallCapacity($planet->getId());
                            $heavygas_limit     = $this->planetCapacity->getHeavyGasCapacity($planet->getId());
                            $ore_limit          = $this->planetCapacity->getOreCapacity($planet->getId());
                            $hydro_limit        = $this->planetCapacity->getHydroCapacity($planet->getId());
                            $titan_limit        = $this->planetCapacity->getTitanCapacity($planet->getId());
                            $darkmatter_limit   = $this->planetCapacity->getDarkmatterCapacity($planet->getId());
                            $redmatter_limit    = $this->planetCapacity->getRedmatterCapacity($planet->getId());
                            
                            $planet->setMetall(         $metall     > $metall_limit     ? $metall_limit     : $metall);
                            $planet->setHeavyGas(       $heavygas   > $heavygas_limit   ? $heavygas_limit   : $heavygas);
                            $planet->setOre(            $ore        > $ore_limit        ? $ore_limit        : $ore);
                            $planet->setHydro(          $hydro      > $hydro_limit      ? $hydro_limit      : $hydro);
                            $planet->setTitan(          $heavygas   > $titan_limit      ? $titan_limit      : $titan);
                            $planet->setDarkmatter(     $darkmatter > $darkmatter_limit ? $darkmatter_limit : $darkmatter);
                            $planet->setRedmatter(      $redmatter  > $redmatter_limit  ? $redmatter_limit  : $redmatter);
                            $planet->setAnti($anti);
                            $planet = $this->planetCommand->updateEntity($planet);
                        }
                    }
                    $planet->setElectricity($electricity);
                    $planet = $this->planetCommand->updateEntity($planet);
                }
            }
            $view->setVariable('data', array('result' => 'OK'));
        }
        catch(\Exception $e){
            $view->setVariable('data', array('result' => 'ERROR', 'message' => $e->getMessage()));
        }
        $view->setTerminal(true);
        return $view;
    }
    
}
