<?php

namespace App\Renderer;

use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\AdapterInterface;
use App\Controller\AuthController;
use App\Classes\TimeUtils;
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

class FleetMoovingActivity
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
     * @ TimeUtils
     */
    private $timeUtils;
    
    public function __construct(
        AdapterInterface        $db,
        AuthController          $authController,
        SpaceSheepRepository    $spaceSheepRepository,
        SpaceSheepCommand       $spaceSheepCommand,
        GalaxyRepository        $galaxyRepository,
        PlanetSystemRepository  $planetSystemRepository,
        PlanetRepository        $planetRepository,
        EventRepository         $eventRepository,
        EventCommand            $eventCommand
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
        $this->timeUtils                = new TimeUtils();
        $this->result                   = array();
    }
    
    public function execute(&$view)
    {
        $events = array(EventTypes::$FLEET_RELOCATION_BIDIRECTIONAL);
        $are_mooving_fleet_activity = false;
        if($this->authController->isAuthorized()){
            foreach($events as $event){
                switch($event){
                    case EventTypes::$FLEET_RELOCATION_BIDIRECTIONAL:
                        $are_mooving_fleet_activity = $this->parseFleetRelocationBidirectional($view) ? true : $are_mooving_fleet_activity;
                        break;
                    default:
                        break;
                }
            }
        }
        $view->setVariable('are_mooving_fleet_activity', $are_mooving_fleet_activity);
        $view->setVariable('events_list', $this->result);
        return $view;
    }
    
    public function parseFleetRelocationBidirectional(&$view)
    {
        try{
            $events = $this->eventRepository->findBy(
                'events.event_type = ' . EventTypes::$FLEET_RELOCATION_BIDIRECTIONAL
                . ' AND events.user = ' . $this->authController->getUser()->getId()
                )->buffer();
            if(count($events)){
                foreach($events as $event){
                    /**
                     * @ в шаблон нужно отдать время до окончания полета, остаток топлива, сколько кораблей принимает участие
                     * @ пока сократим немного задачу :-)
                     */
                    $time = TimeUtils::interval2String($event->getEventEnd() - time());
                    $sheeps = $this->spaceSheepRepository->findBy('spacesheeps.event = ' . $event->getId())->buffer();
                    $count = count($sheeps);
                    $this->result[EventTypes::$FLEET_RELOCATION_BIDIRECTIONAL][$event->getId()] = 
                        array('time' => $time, 'count' => $count, 'event' => $event);
                }
                return true;
            }
            else{
                return false;
            }
        }
        catch(\Exception $e){
            return false;
        }
    }
    
}
