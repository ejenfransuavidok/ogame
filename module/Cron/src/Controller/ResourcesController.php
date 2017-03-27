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
    
    public function __construct(
        AdapterInterface    $db, 
        PlanetRepository    $planetRepository,
        PlanetCommand       $planetCommand,
        SputnikRepository   $sputnikRepository,
        UserRepository      $userRepository,
        BuildingRepository  $buildingRepository,
        BuildingCommand     $buildingCommand
        )
    {
        $this->dbAdapter = $db;
        $this->planetRepository = $planetRepository;
        $this->planetCommand = $planetCommand;
        $this->sputnikRepository = $sputnikRepository;
        $this->userRepository = $userRepository;
        $this->buildingRepository = $buildingRepository;
        $this->buildingCommand = $buildingCommand;
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
                    foreach($this->buildingRepository->findBy('buildings.planet = ' . $planet->getId() . ' AND buildings.type = ' . Building::$BUILDING_RESOURCE) as $building){
                        $update = $building->getUpdate();
                        $electricity = $electricity + $building->getProduceElectricity() - $building->getConsumeElectricity;
                        /**
                         * пришло время для обновления
                         */
                        if($now > $update){
                            $K = intval(ceil(($now - $update) / Building::$DELTA_REFRESH));
                            $metall = intval(ceil($K * $type->getMetall() * $building->getProduceMetall() + $planet->getMetall()));
                            $heavygas = intval(ceil($K * $type->getHeavyGas() * $building->getProduceHeavygas() + $planet->getHeavyGas()));
                            $ore = intval(ceil($K * $type->getOre() * $building->getProduceOre() + $planet->getOre()));
                            $hydro = intval(ceil($K * $type->getHydro() * $building->getProduceHydro() + $planet->getHydro()));
                            $titan = intval(ceil($K * $type->getTitan() * $building->getProduceTitan() + $planet->getTitan()));
                            $darkmatter = intval(ceil($K * $type->getDarkmatter() * $building->getProduceDarkmatter() + $planet->getDarkmatter()));
                            $redmatter = intval(ceil($K * $type->getRedmatter() * $building->getProduceRedmatter() + $planet->getRedmatter()));
                            $anti = intval(ceil($K * $type->getAnti() * $building->getProduceAnti() + $planet->getAnti()));
                            /* end */
                            $building->setUpdate($now + Building::$DELTA_REFRESH);
                            $building = $this->buildingCommand->updateEntity($building);
                            $planet->setMetall($metall);
                            $planet->setHeavyGas($heavygas);
                            $planet->setOre($ore);
                            $planet->setHydro($hydro);
                            $planet->setTitan($titan);
                            $planet->setDarkmatter($darkmatter);
                            $planet->setRedmatter($redmatter);
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
