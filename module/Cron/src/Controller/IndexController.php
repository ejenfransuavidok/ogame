<?php

namespace Cron\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\AdapterInterface;
use Universe\Model\PlanetRepository;
use Universe\Model\PlanetCommand;
use Universe\Model\SputnikRepository;
use Universe\Model\SputnikCommand;
use Entities\Model\Building;
use Entities\Model\EventRepository;
use Entities\Model\EventCommand;
use Entities\Model\BuildingTypeRepository;
use Entities\Model\BuildingTypeCommand;
use Entities\Model\UserRepository;
use Entities\Model\UserCommand;
use Entities\Model\BuildingRepository;
use Entities\Model\BuildingCommand;
use Entities\Classes\EventTypes;
use App\Controller\AuthController;

class IndexController extends AbstractActionController
{
    /**
     * @ EventRepository
     */
    protected $eventRepository;
    
    /**
     * @ EventCommand
     */
    protected $eventCommand;
    
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
     * @ SputnikCommand
     */
    protected $sputnikCommand;
    
    /**
     * @ UserRepository
     */
    protected $userRepository;
    
    /**
     * @ UserCommand
     */
    protected $userCommand;
    
    /**
     * @ BuildingRepository
     */
    protected $buildingRepository;
    
    /**
     * @ BuildingCommand
     */
    protected $buildingCommand;
    
    /**
     * @ AuthController
     */
    protected $authController;
    
    /**
     * @ BuildingTypeRepository
     */
    protected $buildingTypeRepository;
    
    /**
     * @ BuildingTypeCommand
     */
    protected $buildingTypeCommand;
    
    
    
    public function __construct(
        AdapterInterface        $db,
        EventRepository         $eventRepository,
        EventCommand            $eventCommand,
        BuildingTypeRepository  $buildingTypeRepository,
        BuildingTypeCommand     $buildingTypeCommand,
        PlanetRepository        $planetRepository,
        PlanetCommand           $planetCommand,
        SputnikRepository       $sputnikRepository,
        SputnikCommand          $sputnikCommand,
        UserRepository          $userRepository,
        UserCommand             $userCommand,
        BuildingRepository      $buildingRepository,
        BuildingCommand         $buildingCommand,
        AuthController          $authController
        )
    {
        $this->dbAdapter                = $db;
        $this->planetRepository         = $planetRepository;
        $this->planetCommand            = $planetCommand;
        $this->sputnikRepository        = $sputnikRepository;
        $this->sputnikCommand           = $sputnikCommand;
        $this->userRepository           = $userRepository;
        $this->userCommand              = $userCommand;
        $this->buildingRepository       = $buildingRepository;
        $this->buildingCommand          = $buildingCommand;
        $this->authController           = $authController;
        $this->eventRepository          = $eventRepository;
        $this->eventCommand             = $eventCommand;
        $this->buildingTypeRepository   = $buildingTypeRepository;
        $this->buildingTypeCommand      = $buildingTypeCommand;
    }
    
    public function indexAction()
    {
        try{
            /**
             * @ работа с событиями для строительства ресурсных зданий
             */
            $this->executeResourcesBuildingDoBuild();
        }
        catch(\Exception $e){
            die($e->getMessage());
        }
    }
    
    private function executeResourcesBuildingDoBuild()
    {
        /**
         * @ выберем все события по строительству ресурсных зданий
         */
        try{
            $events = $this->eventRepository->findAllEntities('events.event_type = ' . EventTypes::$DO_BUILD_RESOURCES)->buffer();
            if(count($events)){
                foreach($events as $event){
                    /**
                     * @ если евент просрочен, выполняем
                     */
                    if(time() >= $event->getEventEnd()) {
                        $targetBuildingType = $event->getTargetBuildingType();
                        /**
                         * @ если это здание уже есть у юзера, то просто обновляем левел
                         *   иначе создаем здание по-новой
                         */
                        $level = $event->getTargetLevel();
                        if($level == 1){
                            /**
                             * @ этого здания у юзера нет
                             */
                            $building = new Building(
                                $targetBuildingType->getName(),
                                $targetBuildingType->getDescription(),
                                $event->getTargetPlanet(),
                                $event->getTargetSputnik(),
                                $event->getUser(),
                                $event->getTargetLevel(),
                                $targetBuildingType->getType(),
                                time(),
                                $targetBuildingType->getFactor(),
                                $targetBuildingType->getProduceMetall(),
                                $targetBuildingType->getProduceHeavygas(),
                                $targetBuildingType->getProduceOre(),
                                $targetBuildingType->getProduceHydro(),
                                $targetBuildingType->getProduceTitan(),
                                $targetBuildingType->getProduceDarkmatter(),
                                $targetBuildingType->getProduceRedmatter(),
                                $targetBuildingType->getProduceAnti(),
                                $targetBuildingType->getProduceElectricity(),
                                $targetBuildingType->getConsumeMetall(),
                                $targetBuildingType->getConsumeHeavygas(),
                                $targetBuildingType->getConsumeOre(),
                                $targetBuildingType->getConsumeHydro(),
                                $targetBuildingType->getConsumeTitan(),
                                $targetBuildingType->getConsumeDarkmatter(),
                                $targetBuildingType->getConsumeRedmatter(),
                                $targetBuildingType->getConsumeAnti(),
                                $targetBuildingType->getConsumeElectricity(),
                                $targetBuildingType->getCapacityMetall(),
                                $targetBuildingType->getCapacityHeavygas(),
                                $targetBuildingType->getCapacityOre(),
                                $targetBuildingType->getCapacityHydro(),
                                $targetBuildingType->getCapacityTitan(),
                                $targetBuildingType->getCapacityDarkmatter(),
                                $targetBuildingType->getCapacityRedmatter()
                                );
                            $building = $this->buildingCommand->insertEntity($building);
                        }
                        else{
                            /**
                             * @ у юзера уже есть этот тип здания, значит надо обновить фактор и умножить на него
                             *   вырабатываемые ресурсы
                             */
                            $user           = $event->getUser();
                            $name           = $targetBuildingType->getName();
                            $building       = $this->buildingRepository->findOneBy('buildings.owner = ' . $user->getId() . ' AND buildings.name = "' . $name . '"');
                            $factor         = $building->getFactor()            * $level;
                            $metall         = $building->getProduceMetall()     * $factor;
                            $heavygas       = $building->getProduceHeavygas()   * $factor;
                            $ore            = $building->getProduceOre()        * $factor;
                            $hydro          = $building->getProduceHydro()      * $factor;
                            $titan          = $building->getProduceTitan()      * $factor;
                            $darkmatter     = $building->getProduceDarkmatter() * $factor;
                            $redmatter      = $building->getProduceRedmatter()  * $factor;
                            $anti           = $building->getProduceAnti()       * $factor;
                            $electricity    = $building->getProduceElectricity()* $factor;
                            $building->setLevel             ($level);
                            $building->setProduceMetall     ($metall);
                            $building->setProduceHeavygas   ($heavygas);
                            $building->setProduceOre        ($ore);
                            $building->setProduceHydro      ($hydro);
                            $building->setProduceTitan      ($titan);
                            $building->setProduceDarkmatter ($darkmatter);
                            $building->setProduceRedmatter  ($redmatter);
                            $building->setProduceAnti       ($anti);
                            $building->setProduceElectricity($electricity);
                            $building = $this->buildingCommand->updateEntity($building);
                        }
                        /**
                         * @удаляем ивент
                         */
                        $this->eventCommand->deleteEntity($event);
                    }
                }
            }
        }
        catch (\Exception $e){
            die($e->getMessage());
        }
    }
    
}
