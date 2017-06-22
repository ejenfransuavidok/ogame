<?php

namespace Eventer\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\AdapterInterface;
use App\Controller\AuthController;
use Entities\Model\Event;
use Entities\Model\EventRepository;
use Entities\Model\EventCommand;
use Entities\Model\UserRepository;
use Entities\Model\UserCommand;
use Entities\Model\BuildingRepository;
use Entities\Model\BuildingCommand;
use Entities\Model\BuildingTypeRepository;
use Entities\Model\BuildingTypeCommand;
use Universe\Model\StarRepository;
use Universe\Model\StarCommand;
use Universe\Model\PlanetRepository;
use Universe\Model\PlanetCommand;
use Universe\Model\SputnikRepository;
use Universe\Model\SputnikCommand;
use Entities\Classes\EventTypes;

class JsreaderController extends AbstractActionController
{
    
    /**
     * @ AuthController
     */
    protected $authController;
    
    /**
     * @ EventRepository
     */
    protected $eventRepository;
    
    /**
     * @ EventCommand
     */
    protected $eventCommand;
    
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
     * @ BuildingTypeRepository
     */
    protected $buildingTypeRepository;
    
    /**
     * @ BuildingTypeCommand
     */
    protected $buildingTypeCommand;
    
    /**
     * @ StarRepository
     */
    protected $starRepository;
    
    /**
     * @ StarCommand
     */
    protected $starCommand;
    
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
    
    public function __construct(
        AdapterInterface        $db,
        AuthController          $authController,
        EventRepository         $eventRepository,
        EventCommand            $eventCommand,
        UserRepository          $userRepository,
        UserCommand             $userCommand,
        BuildingRepository      $buildingRepository,
        BuildingCommand         $buildingCommand,
        BuildingTypeRepository  $buildingTypeRepository,
        BuildingTypeCommand     $buildingTypeCommand,
        StarRepository          $starRepository,
        StarCommand             $starCommand,
        PlanetRepository        $planetRepository,
        PlanetCommand           $planetCommand,
        SputnikRepository       $sputnikRepository,
        SputnikCommand          $sputnikCommand
        )
    {
        $this->dbAdapter                = $db;
        $this->authController           = $authController;
        $this->eventRepository          = $eventRepository;
        $this->eventCommand             = $eventCommand;
        $this->userRepository           = $userRepository;
        $this->userCommand              = $userCommand;
        $this->buildingRepository       = $buildingRepository;
        $this->buildingCommand          = $buildingCommand;
        $this->buildingTypeRepository   = $buildingTypeRepository;
        $this->buildingTypeCommand      = $buildingTypeCommand;
        $this->starRepository           = $starRepository;
        $this->starCommand              = $starCommand;
        $this->planetRepository         = $planetRepository;
        $this->planetCommand            = $planetCommand;
        $this->sputnikRepository        = $sputnikRepository;
        $this->sputnikCommand           = $sputnikCommand;
    }
    
    public function jsreaderAction()
    {
        /**
         * запрос от клиента, в запросе planetid
         */
        $view = new ViewModel([]);
        $view->setTerminal(true);
        
        if($this->authController->isAuthorized()){
            $this->user = $this->authController->getUser();
            try{
                $events = $this->eventRepository->findAllEntities('events.target_planet = ' . $this->params()->fromPost('planetid'))->buffer();
                $result = array();
                foreach($events as $event){
                    $id = $event->getId();
                    $result[$id]['name']                = $event->getName();
                    $result[$id]['description']         = $event->getDescription();
                    $result[$id]['event_type']          = $event->getEventType();
                    $result[$id]['event_begin']         = intval($event->getEventBegin());
                    $result[$id]['event_end']           = intval($event->getEventEnd());
                    $result[$id]['now']                 = time();
                    $result[$id]['targetLevel']         = $event->getTargetLevel();
                    $result[$id]['targetBuildingType']  = $event->getTargetBuildingType() ? $event->getTargetBuildingType()->getId() : 0;
                }
                $view->setVariable('data', array('result' => 'YES', 'auth' => 'YES', 'content' => $result));
            }
            catch(\Exception $e){
                $view->setVariable('data', array('result' => 'ERR', 'auth' => 'YES', 'message' => $e->getMessage()));
            }
        }
        else {
            /**
             * @ пользователь неавторизован
             */
            $view->setVariable('data', array('result' => 'ERR', 'auth' => 'NO', 'message' => 'Пользователь не авторизован'));
        }
        return $view;
    }
    
}
