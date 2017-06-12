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
use Settings\Model\SettingsRepositoryInterface;
use Entities\Classes\SelectBuildings;

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
    
    /**
     * @ SettingsRepositoryInterface;
     */
    protected $settingsRepositoryInterface;
    
    public function __construct(
        AdapterInterface            $db, 
        PlanetRepository            $planetRepository,
        PlanetCommand               $planetCommand,
        SputnikRepository           $sputnikRepository,
        UserRepository              $userRepository,
        BuildingRepository          $buildingRepository,
        BuildingCommand             $buildingCommand,
        PlanetCapacity              $planetCapacity,
        SettingsRepositoryInterface $settingsRepositoryInterface
        )
    {
        $this->dbAdapter                    = $db;
        $this->planetRepository             = $planetRepository;
        $this->planetCommand                = $planetCommand;
        $this->sputnikRepository            = $sputnikRepository;
        $this->userRepository               = $userRepository;
        $this->buildingRepository           = $buildingRepository;
        $this->buildingCommand              = $buildingCommand;
        $this->planetCapacity               = $planetCapacity;
        $this->settingsRepositoryInterface  = $settingsRepositoryInterface;
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
                    $electricity    = 0;
                    $metall         = 0;
                    $heavygas       = 0;
                    $ore            = 0;
                    $hydro          = 0;
                    $titan          = 0;
                    $darkmatter     = 0;
                    $redmatter      = 0;
                    $anti           = 0;
                    /**
                     * @ выберем ресурсные здания
                     */
                    foreach(SelectBuildings::selectResourcesBuildings($planet, $this->buildingRepository) as $building){
                        $electricity     = $electricity + $building->getProduceElectricity() - $building->getConsumeElectricity();
                        $metall         += $building->getProduceMetallPerHour();
                        $heavygas       += $building->getProduceHeavygasPerHour();
                        $ore            += $building->getProduceOrePerHour();
                        $hydro          += $building->getProduceHydroPerHour();
                        $titan          += $building->getProduceTitanPerHour();
                        $darkmatter     += $building->getProduceDarkmatterPerHour();
                        $redmatter      += $building->getProduceRedmatterPerHour();
                        $anti           += $building->getProduceAntiPerHour();
                    }
                    /**
                     * @ установка скоростей обновления Building::$DELTA_REFRESH = 1 час = 3600 секунд
                     */
                    $update = $planet->getUpdate();
                    
                    $base_metall_per_second     = floatval($this->settingsRepositoryInterface->findSettingByKey ('METALL_BASE_VELOCITY_PER_SECOND')->getText());
                    $base_heavygas_per_second   = floatval($this->settingsRepositoryInterface->findSettingByKey ('HEAVYGAS_BASE_VELOCITY_PER_SECOND')->getText());
                    $base_ore_per_second        = floatval($this->settingsRepositoryInterface->findSettingByKey ('ORE_BASE_VELOCITY_PER_SECOND')->getText());
                    $base_hydro_per_second      = floatval($this->settingsRepositoryInterface->findSettingByKey ('HYDRO_BASE_VELOCITY_PER_SECOND')->getText());
                    $base_titan_per_second      = floatval($this->settingsRepositoryInterface->findSettingByKey ('TITAN_BASE_VELOCITY_PER_SECOND')->getText());
                    $base_darkmatter_per_second = floatval($this->settingsRepositoryInterface->findSettingByKey ('DARKMATTER_BASE_VELOCITY_PER_SECOND')->getText());
                    $base_redmatter_per_second  = floatval($this->settingsRepositoryInterface->findSettingByKey ('REDMATTER_BASE_VELOCITY_PER_SECOND')->getText());
                    $base_anti_per_second       = floatval($this->settingsRepositoryInterface->findSettingByKey ('ANTI_BASE_VELOCITY_PER_SECOND')->getText());
                    
                    $planet->set_velocity_per_second_mineral_metall     ($base_metall_per_second    + $type->getMetall()     * $metall       / Building::$DELTA_REFRESH);
                    $planet->set_velocity_per_second_mineral_heavygas   ($base_heavygas_per_second  + $type->getHeavyGas()   * $heavygas     / Building::$DELTA_REFRESH);
                    $planet->set_velocity_per_second_mineral_ore        ($base_ore_per_second       + $type->getOre()        * $ore          / Building::$DELTA_REFRESH);
                    $planet->set_velocity_per_second_mineral_hydro      ($base_hydro_per_second     + $type->getHydro()      * $hydro        / Building::$DELTA_REFRESH);
                    $planet->set_velocity_per_second_mineral_titan      ($base_titan_per_second     + $type->getTitan()      * $titan        / Building::$DELTA_REFRESH);
                    $planet->set_velocity_per_second_mineral_darkmatter ($base_darkmatter_per_second+ $type->getDarkmatter() * $darkmatter   / Building::$DELTA_REFRESH);
                    $planet->set_velocity_per_second_mineral_redmatter  ($base_redmatter_per_second + $type->getRedmatter()  * $redmatter    / Building::$DELTA_REFRESH);
                    $planet->set_velocity_per_second_mineral_anti       ($base_anti_per_second      + $type->getAnti()       * $anti         / Building::$DELTA_REFRESH);
                    $planet->setElectricity($electricity);
                    $planet = $this->planetCommand->updateEntity($planet);
                    
                    if($now > $update + 10){
                        $K                  = $now - $update;
                        $metall             = $K * $planet->get_velocity_per_second_mineral_metall()        + $planet->getMetall();
                        $heavygas           = $K * $planet->get_velocity_per_second_mineral_heavygas()      + $planet->getHeavyGas();
                        $ore                = $K * $planet->get_velocity_per_second_mineral_ore()           + $planet->getOre();
                        $hydro              = $K * $planet->get_velocity_per_second_mineral_hydro()         + $planet->getHydro();
                        $titan              = $K * $planet->get_velocity_per_second_mineral_titan()         + $planet->getTitan();
                        $darkmatter         = $K * $planet->get_velocity_per_second_mineral_darkmatter()    + $planet->getDarkmatter();
                        $redmatter          = $K * $planet->get_velocity_per_second_mineral_redmatter()     + $planet->getRedmatter();
                        $anti               = $K * $planet->get_velocity_per_second_mineral_anti()          + $planet->getAnti();
                        
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
                            
                        $planet->setUpdate($now/*Building::$DELTA_REFRESH / 600*/);
                        $planet = $this->planetCommand->updateEntity($planet);
                    }
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
