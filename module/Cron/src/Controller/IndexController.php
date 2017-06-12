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
        $view = new ViewModel([]);
        $view->setTerminal(true);
        try{
            /**
             * @ работа с событиями, относящимися к строительству зданий
             */
            $this->executeBuildings();
            /**
             * @ просто показываем что скрипт отработал нормально
             */
            $view->setVariable('data', array('result' => 'YES', 'auth' => 'YES', 'message' => 'building completed'));
        }
        catch(\Exception $e){
            $view->setVariable('data', array('result' => 'ERR', 'auth' => 'YES', 'message' => $e->getMessage()));
        }
        return $view;
    }
    
    private function executeBuildings()
    {
        /**
         * @ выберем все события по строительству зданий
         */
        $events = getBuildingEvents::getBuildingEvents($this->eventRepository);
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
                        /**
                        $name, 
                        $description, 
                        $planet,
                        $sputnik,
                        $owner,
                        $level,
                        $buildingType,
                        $update,
                        $id=null
                        */
                        $building = new Building(
                            $targetBuildingType->getName(),
                            $targetBuildingType->getDescription(),
                            $event->getTargetPlanet(),
                            $event->getTargetSputnik(),
                            $event->getUser(),
                            $event->getTargetLevel(),
                            $targetBuildingType,
                            time()
                            );
                        $building = $this->buildingCommand->insertEntity($building);
                    }
                    else{
                        /**
                        * @ у юзера уже есть этот тип здания, значит надо обновить левел
                        */
                        $user           = $event->getUser();
                        $name           = $targetBuildingType->getName();
                        $building       = $this->buildingRepository->findOneBy('buildings.owner = ' . $user->getId() . ' AND buildings.name = "' . $name . '"');
                        $building->setLevel ($level);
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
    
}
