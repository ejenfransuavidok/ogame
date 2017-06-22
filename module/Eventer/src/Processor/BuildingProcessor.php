<?php

namespace Eventer\Processor;

use App\Controller\AuthController;
use Entities\Model\Event;
use Entities\Model\EventRepository;
use Entities\Model\EventCommand;
use Entities\Model\BuildingType;
use Entities\Model\Building;
use Universe\Model\PlanetRepository;
use Universe\Model\PlanetCommand;
use Entities\Model\BuildingTypeErrorException;
use Entities\Model\BuildingTypeRepository;
use Entities\Model\BuildingTypeCommand;
use Entities\Classes\EventTypes;
use Entities\Model\BuildingRepository;

class BuildingProcessor
{
    
    /**
     * @ AuthController
     */
    private $authController;
    
    /**
     * @ EventRepository
     */
    private $eventRepository;
    
    /**
     * @ EventCommand
     */
    private $eventCommand;
    
    /**
     * @ PlanetRepository
     */
    private $planetRepository;
    
    /**
     * @ PlanetCommand
     */
    private $planetCommand;
    
    /**
     * @ BuildingRepository
     */
    protected $buildingRepository;
    
    public function __construct(
            AuthController          $authController,
            EventRepository         $eventRepository,
            EventCommand            $eventCommand,
            PlanetRepository        $planetRepository,
            planetCommand           $planetCommand,
            BuildingTypeRepository  $buildingTypeRepository,
            BuildingTypeCommand     $buildingTypeCommand,
            BuildingRepository      $buildingRepository
        )
    {
        $this->authController           = $authController;
        $this->eventRepository          = $eventRepository;
        $this->eventCommand             = $eventCommand;
        $this->planetRepository         = $planetRepository;
        $this->planetCommand            = $planetCommand;
        $this->buildingTypeRepository   = $buildingTypeRepository;
        $this->buildingTypeCommand      = $buildingTypeCommand;
        $this->buildingRepository       = $buildingRepository;
    }
    
    public function execute($planet, $buildingType, &$view)
    {
        /**
         * @ переводим в объекты
         */
        $planet = $this->planetRepository->findEntity($planet);
        $buildingType = $this->buildingTypeRepository->findOneBy('building_types.id = ' . $buildingType);
        if($this->authController->isAuthorized()){
            $this->user = $this->authController->getUser();
            /**
             * @ выбрать все строительные события, относящиеся к данному юзеру на данной планете
             */
            $events = $this->eventRepository->findAllEntities(
                'events.user = ' . $this->user->getId() .
                ' AND events.target_planet = ' . $planet->getId() .
                ' AND (events.event_type = ' . $this->getBuildingEventType($buildingType) . ')'
                )->buffer();
            if(count($events)) {
                /**
                 * @ если здание уже строится, то строить запрещено
                 */
                $view->setVariable('data', array('result' => 'ERR', 'auth' => 'YES', 'message' => 'На данной планете уже ведется строительство!'));
            }
            else {
                /**
                 * @ строить можно
                 */
                $level = 1;
                /**
                 * @ есть ли на данной планете уже такое здание (поиск по имени)
                 */
                try{
                    if($building = $this->buildingRepository->findOneBy(
                        'buildings.name = "' . $buildingType->getName() . '" AND buildings.planet = ' . $planet->getId())){
                        /**
                         * @ здание есть, увеличиваем level
                         */
                        $level = $building->getLevel() + 1;
                    }
                }
                catch(\Exception $e){
                    /**
                     * @ на случай, если таблица зданий пустая
                     */
                    $level = 1;
                }
                list($K, $electricity, $metall, $heavygas, $ore, $hydro, $titan, $darkmatter, $redmatter, $anti, $electricity, $areResourcesEnough, $time) =
                    ResourcesCalculator::resourcesCalc($buildingType, $planet, $level);
                if($areResourcesEnough) {
                    /**
                     * @ ресурсов хватает - строим
                     */                    
                    $event = new Event(
                        $buildingType->getName(),
                        $buildingType->getDescription(),
                        $this->user,
                        $this->getBuildingEventType($buildingType),
                        time(),
                        time() + $time,
                        null,
                        $planet,
                        null,
                        $buildingType,
                        $level
                    );
                    $event = $this->eventCommand->insertEntity($event);
                    /**
                     * @ на планете стало меньше ресурсов
                     */
                    $planet->setElectricity($electricity);
                    $planet->setMetall($metall);
                    $planet->setHeavyGas($heavygas);
                    $planet->setOre($ore);
                    $planet->setHydro($hydro);
                    $planet->setTitan($titan);
                    $planet->setDarkmatter($darkmatter);
                    $planet->setRedmatter($redmatter);
                    $planet->setAnti($anti);
                    $planet = $this->planetCommand->updateEntity($planet);
                    /**
                     * @
                     */
                    $view->setVariable('data', array(
                        'result'        => 'YES', 
                        'auth'          => 'YES',
                        'event_id'      => $event->getId(),
                        'begin'         => $event->getEventBegin(),
                        'end'           => $event->getEventEnd(),
                        'now'           => time(),
                        'message' => 'Строительство успешно началось!'));
                }
                else {
                    /**
                     * @ ресурсов не хватает
                     */
                    $view->setVariable('data', array('result' => 'ERR', 'auth' => 'YES', 'message' => 'Недостаточно ресурсов'));
                }
            }
        }
        else {
            /**
             * @ пользователь неавторизован
             */
            $view->setVariable('data', array('result' => 'ERR', 'auth' => 'NO', 'message' => 'Пользователь не авторизован'));
        }
    }
    
    
    private function getBuildingEventType(BuildingType $buildingType)
    {
        return EventTypes::calcEventIdForBuildings($buildingType->getObjectType(), $buildingType->getBuildingType());
        /*
        switch($buildingType->getBuildingType()){
            case Building::$BUILDING_RESOURCE:
                return EventTypes::$DO_BUILD_RESOURCES;
                break;
            case Building::$BUILDING_INDUSTRIAL:
                return EventTypes::$DO_BUILD_INDUSTRIAL;
                break;
            default:
                throw new BuildingTypeErrorException("type " . $buildingType . " does not acceptable!");
                break;
        }
        */
    }
    
}
