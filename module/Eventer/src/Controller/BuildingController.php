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
use Entities\Model\BuildingTypeErrorException;
use Entities\Model\Building;
use Entities\Model\BuildingRepository;
use Entities\Model\BuildingCommand;
use Entities\Model\BuildingType;
use Entities\Model\BuildingTypeRepository;
use Entities\Model\BuildingTypeCommand;
use Universe\Model\StarRepository;
use Universe\Model\StarCommand;
use Universe\Model\PlanetRepository;
use Universe\Model\PlanetCommand;
use Universe\Model\SputnikRepository;
use Universe\Model\SputnikCommand;
use Entities\Classes\EventTypes;
use Eventer\Processor\BuildingProcessor;
use Eventer\Processor\ResourcesCalculator;
use Settings\Model\SettingsRepositoryInterface;
use Eventer\Processor\Finish4DonateProcessor;



class BuildingController extends AbstractActionController
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
    
    /**
     * @ BuildingProcessor
     */
    protected $buildingProcessor;
    
    /**
     * @SettingsRepositoryInterface $settingsRepository
     */
    protected $settingsRepository;

    public function __construct(
        AdapterInterface            $db,
        AuthController              $authController,
        EventRepository             $eventRepository,
        EventCommand                $eventCommand,
        UserRepository              $userRepository,
        UserCommand                 $userCommand,
        BuildingRepository          $buildingRepository,
        BuildingCommand             $buildingCommand,
        BuildingTypeRepository      $buildingTypeRepository,
        BuildingTypeCommand         $buildingTypeCommand,
        StarRepository              $starRepository,
        StarCommand                 $starCommand,
        PlanetRepository            $planetRepository,
        PlanetCommand               $planetCommand,
        SputnikRepository           $sputnikRepository,
        SputnikCommand              $sputnikCommand,
        BuildingProcessor           $buildingProcessor,
        SettingsRepositoryInterface $settingsRepository
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
        $this->buildingProcessor        = $buildingProcessor;
        $this->settingsRepository       = $settingsRepository;
    }
    
    /**
     * @ начать строительство
     * in:
     *      planet
     *      buildingType
     */
    public function initbuildAction()
    {
        $view = new ViewModel([]);
        $view->setTerminal(true);
        $planet = $this->params()->fromPost('planet');
        $buildingType = $this->params()->fromPost('buildingType');
        $this->buildingProcessor->execute($planet, $buildingType, $view);
        return $view;
    }
    
    public function rejectbuildingAction()
    {
        $view = new ViewModel([]);
        $view->setTerminal(true);
        
        $event_id = $this->params()->fromPost('event_id');
        if($this->authController->isAuthorized()){
            $this->user = $this->authController->getUser();
            try{
                $event          = $this->eventRepository->findOneBy('events.id = ' . $event_id);
                $buildingType   = $event->getTargetBuildingType();
                $name           = $event->getName();
                $planet         = $event->getTargetPlanet();
                $level          = $event->getTargetLevel();
                /**
                 * удаляем событие
                 */
                $this->eventCommand->deleteEntity($event);
                /**
                 * вернем ресурсы обратно
                 */
                list($K, $electricity, $metall, $heavygas, $ore, $hydro, $titan, $darkmatter, $redmatter, $anti) 
                    = ResourcesCalculator::resourcesCalcGetBack($buildingType, $planet, $level);
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
                $view->setVariable('data', array('result' => 'YES', 'auth' => 'YES', 'message' => 'Строительство данного объекта отменено'));
            }
            catch(\Exception $e){
                $view->setVariable('data', array('result' => 'ERR', 'auth' => 'YES', 'message' => 'Строительство данного объекта не ведется!', 'error' => $e->getMessage()));
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
    
    public function finishfordonatebuildingAction()
    {
        $view = new ViewModel([]);
        $view->setTerminal(true);
        
        $event_id = $this->params()->fromPost('event_id');
        if($this->authController->isAuthorized()){
            $this->user = $this->authController->getUser();
            try{
                $event = $this->eventRepository->findOneBy('events.id = ' . $event_id);
                Finish4DonateProcessor::execute
                    (
                        $event, 
                        $this->eventCommand,
                        $this->settingsRepository,
                        $this->planetCommand,
                        $this->sputnikCommand,
                        $view
                    );
            }
            catch(\Exception $e){
                $view->setVariable('data', array('result' => 'ERR', 'auth' => 'YES', 'message' => 'Строительство данного объекта не ведется!', 'error' => $e->getMessage()));
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
    
    public function buildingAction()
    {
        $view = new ViewModel([]);
        $view->setTerminal(true);
        
        if($this->authController->isAuthorized()){
            
        }
        
        return $view;
    }
    
}
