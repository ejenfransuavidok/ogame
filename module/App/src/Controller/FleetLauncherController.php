<?php

namespace App\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\AdapterInterface;
use App\Library\CoordinateResolver;
use Universe\Classes\UniversePosition;
use Universe\Classes\UniversePosition_P_PS_G;
use Universe\Model\GalaxyRepository;
use Universe\Model\PlanetSystemRepository;
use Universe\Model\PlanetRepository;
use Entities\Model\SpaceSheepRepository;
use Entities\Model\SpaceSheepCommand;
use Entities\Model\Event;
use Entities\Model\EventCommand;
use Entities\Model\EventRepository;
use Entities\Classes\EventTypes;
use Flight\Classes\FlightCalculator;

class FleetLauncherController extends AbstractActionController
{
    /**
     * @var AdapterInterface
     */
    private $dbAdapter;
    
    /**
     * @ AuthController
     */
    private $authController;
    
    /**
     * @ SpaceSheepRepository
     */
    private $spaceSheepRepository;
    
    /**
     * @ SpaceSheepCommand
     */
    private $spaceSheepCommand;
    
    /**
     * @ GalaxyRepository
     */
    private $galaxyRepository;
    
    /**
     * @ PlanetSystemRepository
     */
    private $planetSystemRepository;
    
    /**
     * @ PlanetRepository
     */
    private $planetRepository;
    
    /**
     * @ EventRepository
     */
    private $eventRepository;
    
    /**
     * @ EventCommand
     */
    private $eventCommand;
    
    /**
     * @ CoordinateResolver
     */
    private $coordinateResolver;
    
    /**
     * @ FlightCalculator
     */
    private $flightCalculator;
    
    public function __construct(
        AdapterInterface        $db,
        AuthController          $authController,
        SpaceSheepRepository    $spaceSheepRepository,
        SpaceSheepCommand       $spaceSheepCommand,
        GalaxyRepository        $galaxyRepository,
        PlanetSystemRepository  $planetSystemRepository,
        PlanetRepository        $planetRepository,
        EventRepository         $eventRepository,
        EventCommand            $eventCommand,
        FlightCalculator        $flightCalculator
        )
    {
        $this->dbAdapter                = $db;
        $this->authController           = $authController;
        $this->spaceSheepRepository     = $spaceSheepRepository;
        $this->spaceSheepCommand        = $spaceSheepCommand;
        $this->galaxyRepository         = $galaxyRepository;
        $this->planetSystemRepository   = $planetSystemRepository;
        $this->planetRepository         = $planetRepository;
        $this->eventRepository          = $eventRepository;
        $this->eventCommand             = $eventCommand;
        $this->flightCalculator         = $flightCalculator;
        $this->coordinateResolver       = new CoordinateResolver($this->galaxyRepository, $this->planetSystemRepository, $this->planetRepository);
    }
    
    public function fleetlaunchAction()
    {
        /**
         * @ Отправляем ВСЕ корабли на данной планете
         */
        $view = new ViewModel([]);
        $view->setTerminal(true);
        if($this->authController->isAuthorized()){
            $current_planet         = $this->params()->fromPost('current_planet');
            $target_galaxy          = $this->params()->fromPost('target_galaxy');
            $target_planet_system   = $this->params()->fromPost('target_planet_system');
            $target_planet          = $this->params()->fromPost('target_planet');
            $universePosition       = new UniversePosition();
            $universePosition->setPlanetPosition($target_planet);
            $universePosition->setPlanetSystemPosition($target_planet_system);
            $universePosition->setGalaxyPosition($target_galaxy);
            if($targetUniversePosition = $this->coordinateResolver->resolveByUniversePosition($universePosition)){
                /**
                 * @ выбираем все корабли на планете данного пользователя
                 */
                $sheeps = $this->spaceSheepRepository->findBy(
                        'spacesheeps.owner = ' . $this->authController->getUser()->getId() . 
                        ' AND spacesheeps.planet = ' . $target_planet)->buffer();
                if(!count($sheeps)){
                    $view->setVariable('data', array('result' => 'ERR', 'auth' => 'YES', 'message' => 'На планете нет кораблей!'));
                }
                else{
                    $currentPlanet = $this->planetRepository->findOneBy('planets.id = ' . $current_planet);
                    $flightOrder = $this->flightCalculator->calculate($targetUniversePosition, $currentPlanet);
                    if(!
                        $this->createEvent(
                            $sheeps,
                            $this->authController->getUser(),
                            $flightOrder->getTarget(),
                            /**
                             * @ так как полет в оба конца займет в 2 раза больше времени
                             */
                            $flightOrder->getPeriod() * 2,
                            EventTypes::$FLEET_RELOCATION_BIDIRECTIONAL,
                            $view
                        )
                    ){
                        return $view;
                    }
                    $view->setVariable('data', array('result' => 'YES', 'auth' => 'YES', 'message' => 'Полет начался!'));
                }
            }
            else{
                $view->setVariable('data', array('result' => 'ERR', 'auth' => 'YES', 'message' => 'Цель не существует!'));
            }
        }
        else{
            /**
             * @ пользователь неавторизован
             */
            $view->setVariable('data', array('result' => 'ERR', 'auth' => 'NO', 'message' => 'Пользователь не авторизован'));
        }
        return $view;
    }
    
    public function createEvent(&$sheeps, $user, $target, $event_duration, $event_type, &$view)
    {
        foreach($sheeps as $sheep) {
            if($sheep->getEvent() && $sheep->getEvent()->getEventType() == $event_type) {
                $view->setVariable('data', array('result' => 'ERR', 'auth' => 'YES', 'message' => 'По крайней мере один из кораблей флота - ' . $sheep->getName() . ' уже выполняет это же действие!'));
                return false;
            }
        }
        $event = new Event(
            'Перелет на ' . $target->getName() . ' и обратно.', // name
            '',                                                 // description
            $user,                                              // user
            $event_type,                                        // event_type
            time(),                                             // event_begin
            time() + $event_duration,                           // event_end
            null,                                               // target_star
            $target,                                            // target_planet
            null,                                               // target_sputnik
            null,                                               // targetBuildingType
            null                                                // targetLevel
        );
        $event = $this->eventCommand->insertEntity($event);
        foreach($sheeps as $sheep) {
            /**
             * @ теперь корабль не на планете
             */
            $sheep->setEvent($event);
            $sheep->setGalaxy(null);
            $sheep->setPlanetSystem(null);
            $sheep->setStar(null);
            $sheep->setPlanet(null);
            $sheep->setSputnik(null);
            $this->spaceSheepCommand->updateEntity($sheep);
        }
        return true;
    }
    
}
