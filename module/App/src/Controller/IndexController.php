<?php

/**
 * 
 * @ author Jigulin V.V.
 * @ 10.03.2017
 * 
 */

namespace App\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\AdapterInterface;
use Universe\Model\PlanetRepository;
use Universe\Model\PlanetCommand;
use Universe\Classes\PlanetCapacity;
use Entities\Model\Building;
use Entities\Model\BuildingRepository;
use Entities\Model\BuildingTypeRepository;
use Entities\Model\EventCommand;
use Entities\Model\EventRepository;
use App\Renderer\PopupFleet1Renderer;
use App\Renderer\PopupFleet2Renderer;
use App\Renderer\PopupFleet3Renderer;
use App\Renderer\FleetMoovingActivity;
use App\Renderer\PlanetKeepRenderer;
use App\Renderer\PopupBuildingRenderer;
use App\Renderer\PopupBlackmarketRenderer;
use App\Renderer\PopupBuyRenderer;
use App\Renderer\PopupDonateRenderer;
use Settings\Model\SettingsRepositoryInterface;


class IndexController extends AbstractActionController
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
     * @ PlanetRepository
     */
    private $planetRepository;
    
    /**
     * @ BuildingRepository
     */
    private  $buildingRepository;
    
    /**
     * @ BuildingTypeRepository
     */
    private $buildingTypeRepository;
    
    /**
     * @ EventRepository
     */
    private $eventRepository;
    
    /**
     * @ EventCommand
     */
    private $eventCommand;
    
    /**
     * @PopupFleet1Renderer
     */
    private $popupFleet1Renderer;
    
    /**
     * @PopupFleet2Renderer
     */
    private $popupFleet2Renderer;
    
    /**
     * @PopupFleet3Renderer
     */
    private $popupFleet3Renderer;
    
    /**
     * @ FleetMoovingActivity
     */
    private $fleetMoovingActivity;
    
    /**
     * @ PlanetKeepRenderer
     */
    private $planetKeepRenderer;
    
    /**
     * @ PopupBuildingRenderer
     */
    private $popupBuildingRenderer;
    
    /**
     * @ PlanetCapacity;
     */
    private $planetCapacity;
    
    /**
     * @ PopupBlackmarketRenderer
     */
    private $popupBlackmarketRenderer;
    
    /**
     * @ PopupBuyRenderer
     */
    private $popupBuyRenderer;
    
    /**
     * @ PopupDonateRenderer;
     */
    private $popupDonateRenderer;
    
    /**
     * @SettingsRepositoryInterface $settingsRepository
     */
    private $settingsRepository;
    
    public function __construct(
        AdapterInterface            $db,
        AuthController              $authController,
        PlanetCapacity              $planetCapacity,
        BuildingRepository          $buildingRepository,
        BuildingTypeRepository      $buildingTypeRepository,
        EventRepository             $eventRepository,
        EventCommand                $eventCommand,
        PopupFleet1Renderer         $popupFleet1Renderer,
        PopupFleet2Renderer         $popupFleet2Renderer,
        PopupFleet3Renderer         $popupFleet3Renderer,
        FleetMoovingActivity        $fleetMoovingActivity,
        PlanetRepository            $planetRepository,
        PlanetCommand               $planetCommand,
        PopupBlackmarketRenderer    $popupBlackmarketRenderer,
        PopupBuyRenderer            $popupBuyRenderer,
        PopupDonateRenderer         $popupDonateRenderer,
        SettingsRepositoryInterface $settingsRepository
        )
    {
        $this->dbAdapter                = $db;
        $this->authController           = $authController;
        $this->planetCapacity           = $planetCapacity;
        $this->buildingRepository       = $buildingRepository;
        $this->buildingTypeRepository   = $buildingTypeRepository;
        $this->eventRepository          = $eventRepository;
        $this->eventCommand             = $eventCommand;
        $this->popupFleet1Renderer      = $popupFleet1Renderer;
        $this->popupFleet2Renderer      = $popupFleet2Renderer;
        $this->popupFleet3Renderer      = $popupFleet3Renderer;
        $this->fleetMoovingActivity     = $fleetMoovingActivity;
        $this->planetRepository         = $planetRepository;
        $this->planetCommand            = $planetCommand;
        $this->popupBlackmarketRenderer = $popupBlackmarketRenderer;
        $this->popupBuyRenderer         = $popupBuyRenderer;
        $this->popupDonateRenderer      = $popupDonateRenderer;
        $this->settingsRepository       = $settingsRepository;
        
        $this->planetKeepRenderer       = new PlanetKeepRenderer($this->eventRepository, $this->authController);
        $this->popupBuildingRenderer    = new PopupBuildingRenderer(
                                                    $this->buildingTypeRepository, 
                                                    $this->buildingRepository, 
                                                    $this->planetRepository,
                                                    $this->authController,
                                                    $this->settingsRepository);
        /*
        $loc = $this->getServiceLocator();
        $translator = $loc->get('translator');
        $lang = 'en';
        $translator->addTranslationFile("phparray", dirname(dirname(__FILE__)) . '/language/lang.array.' . $lang . '.php');
        $loc->get('ViewHelperManager')->get('translate')->setTranslator($translator);
        */
    }
    
    public function indexAction()
    {
        if($this->authController->isAuthorized()){
            
            $planetid = $this->params()->fromRoute('planetid');
            
            $planet = $this->planetRepository->findOneBy('planets.id = ' . $planetid);
            
            $this->user = $this->authController->getUser();
            
            $layout = $this->layout();
            
            $layout->setTemplate('app/layout');
            
            $donate = new ViewModel([]);
            $donate->setTemplate('include/popups/donate');
            $this->popupDonateRenderer->execute($donate, $this->user);
            
            $layout->addChild($donate, 'popup_donate');
            
            $header = new ViewModel([
                'planets' => $this->planetRepository->findBy('planets.owner = ' . $this->user->getId() . ' AND planets.id != ' . $planet->getId())->buffer(),
                'planet'  => $planet,
                'capacity'=> $this->planetCapacity,
                'user'    => $this->user,
                'settings'=> $this->settingsRepository
            ]);
            $header->setTemplate('include/header');
            /**
             * @ aside
             */
            $aside = new ViewModel([]);
            $aside->setTemplate('include/aside');
            /**
             * @ 
             */
            $blackmarket = new ViewModel([]);
            $blackmarket->setTemplate('include/popups/blackmarket');
            $this->popupBlackmarketRenderer->execute($blackmarket, $this->user);
            /**
             * @ 
             */
            $buy = new ViewModel([]);
            $buy->setTemplate('include/popups/buy');
            $this->popupBuyRenderer->execute($buy, $this->user);
            /**
             * @ парсинг круга на главной 
             */
            $planetkeep = $this->planetKeepRenderer->render($planetid);

            $popup_building = $this->popupBuildingRenderer->render($planetid);
            /**
             * @ парсинг всплывающего окна флота
             */
            $fleet_forward_1 = new ViewModel([]);
            $fleet_forward_1->setTemplate('include/popups/popup_fleet_1');
            $this->popupFleet1Renderer->execute($fleet_forward_1, $this->user, $planet);
            
            $fleet_forward_2 = new ViewModel([]);
            $fleet_forward_2->setTemplate('include/popups/popup_fleet_2');
            $this->popupFleet2Renderer->execute($fleet_forward_2, $this->user, $planet);
            
            $fleet_forward_3 = new ViewModel([]);
            $fleet_forward_3->setTemplate('include/popups/popup_fleet_3');
            $this->popupFleet3Renderer->execute($fleet_forward_3);
            /**
             * 
             */
            $fleet_mooving_activity = new ViewModel([]);
            $fleet_mooving_activity->setTemplate('include/fleet_mooving_activity');
            $this->fleetMoovingActivity->execute($fleet_mooving_activity);
            /**
             * 
             */
            $game = new ViewModel(['planet' => $planet]);
            $game->setTemplate('include/game');
            $game
                ->addChild($planetkeep, 'planetkeep')
                ->addChild($popup_building, 'popup_building')
                ->addChild($fleet_forward_1, 'fleet_forward_1')
                ->addChild($fleet_forward_2, 'fleet_forward_2')
                ->addChild($fleet_forward_3, 'fleet_forward_3')
                ->addChild($fleet_mooving_activity, 'fleet_mooving_activity')
                ->addChild($blackmarket, 'blackmarket')
                ->addChild($buy, 'buy');
            
            $view = new ViewModel([]);
            $view
                ->addChild($header, 'header')
                ->addChild($aside, 'aside')
                ->addChild($game, 'game');
            
            return $view;
        }
        else{
            return $this->redirect()->toRoute('app', ['action' => 'auth']);
        }
    }
    
    public function planetkeepAction()
    {
        $view = new ViewModel([]);
        $view->setTerminal(true);
        if($this->authController->isAuthorized()){
            $planetid   = $this->params()->fromPost('planetid');
            $this->user = $this->authController->getUser();
            $producesrc = new ViewModel([]);
            $result = $this->parsePlanetkeep($planetid);
            $planetkeep = $result['planetkeep'];
            $event_id   = $result['event_id'];
            $begin      = $result['begin'];
            $end        = $result['end'];
            $now        = $result['now'];
            $view->setVariable('result', 'YES');
            $view->setVariable('auth', 'YES');
            $view->setVariable('message', '');
            $view->setVariable('event_id', $event_id);
            $view->setVariable('begin', $begin);
            $view->setVariable('end', $end);
            $view->setVariable('now', $now);
            $view->addChild($planetkeep, 'planetkeep');
            /**
             * @ обновим также строительное окно
             */
            $planet = $this->planetRepository->findEntity($planetid);
            $popup_building = new ViewModel
                ([
                    'source_buildings' => $this->buildingTypeRepository->findAllEntities('building_types.type = ' . Building::$BUILDING_RESOURCE)->buffer(),
                    'industrial_buildings' => $this->buildingTypeRepository->findAllEntities('building_types.type = ' . Building::$BUILDING_INDUSTRIAL)->buffer()
                ]);
            $popup_building->setTemplate('include/popups/popup_building');
            $popup_building->setVariable('buildingRepository', $this->buildingRepository);
            $popup_building->setVariable('planet', $planet);
            $view->addChild($popup_building, 'popup_building');
        }
        else{
            $view->setVariable('result', 'ERR');
            $view->setVariable('auth', 'NO');
            $view->setVariable('message', 'Пользователь не авторизован');
        }
        
        return $view;
    }
    
    private function parsePlanetkeep($planetid)
    {
        $producefleet = new ViewModel([]);
        $producefleet->setTemplate('include/producefleet');
        $producedefence = new ViewModel([]);
        $producedefence->setTemplate('include/producedefence');
            
        $producesrc = new ViewModel([]);
        $event = $this->checkResourcesBuildingEvent($producesrc, $planetid);
        $producesrc->setTemplate('include/producesrc');
            
        $produceindustrial = new ViewModel([]);
        $produceindustrial->setTemplate('include/produceindustrial');
        
        $producetech = new ViewModel([]);
        $producetech->setTemplate('include/producetech');
        $planetkeep = new ViewModel([]);
        $planetkeep->setTemplate('include/planetkeep');
        $planetkeep
            ->addChild($producefleet, 'producefleet')
            ->addChild($producedefence, 'producedefence')
            ->addChild($producesrc, 'producesrc')
            ->addChild($produceindustrial, 'produceindustrial')
            ->addChild($producetech, 'producetech');
        return array(
            'planetkeep'=> $planetkeep, 
            'event_id'  => $event ? $event->getId() : 0, 
            'begin'     => $event ? $event->getEventBegin() : 0, 
            'end'       => $event ? $event->getEventEnd() : 0,
            'now'       => time());
    }
    
    private function checkResourcesBuildingEvent(ViewModel &$producesrc, $planetid)
    {
        $is_building = false;
        $event = 0;
        $progress = 0;
        $minutes = "00";
        $seconds = "00";
        $hours = "00";
        try{
            $event = $this->eventRepository->findOneBy(
                    'events.user = ' . $this->user->getId() .
                    ' AND events.target_planet = ' . $planetid .
                    ' AND events.targetBuildingType IS NOT NULL'
                    );
        }
        catch(\Exception $e){
            $event = false;
        }
        if($event){
            $is_building = true;
            $interval = $event->getEventEnd() - $event->getEventBegin();
            $progress = ceil(100 - 100*($event->getEventEnd() - time()) / $interval);
            $begin = $event->getEventBegin();
            $end = $event->getEventEnd();
            $rest = intval($event->getEventEnd() - time());
            if($rest < 0){
                $hours = "00";
                $minutes = "00";
                $seconds = "00";
            }
            else {
                $hours = floor($rest / 3600);
                $minutes = floor(($rest - 3600 * $hours) / 60);
                $seconds = intval($rest - 3600 * $hours - 60 * $minutes);
                if($hours < 10)
                    $hours = "0".$hours;
                if($minutes < 10)
                    $minutes = "0".$minutes;
                if($seconds < 10)
                    $seconds = "0".$seconds;
            }
        }
        $producesrc->setVariable('is_building', $is_building);
        $producesrc->setVariable('event', $event);
        $producesrc->setVariable('progress', $progress);
        $producesrc->setVariable('hours', $hours);
        $producesrc->setVariable('minutes', $minutes);
        $producesrc->setVariable('seconds', $seconds);
        return $event;
    }
    
    public function fleetforwardpopupupdaterAction()
    {
        $view = new ViewModel([]);
        $view->setTerminal(true);
        if($this->authController->isAuthorized()){
            try{
                $this->user = $this->authController->getUser();
                $current_planet = $this->params()->fromPost('current_planet');
                $planet = $this->planetRepository->findOneBy('planets.id = ' . $current_planet);
                
                $fleet_forward_1 = new ViewModel([]);
                $fleet_forward_1->setTemplate('include/popups/popup_fleet_1');
                $this->popupFleet1Renderer->execute($fleet_forward_1, $this->user, $planet);
                $fleet_forward_2 = new ViewModel([]);
                $fleet_forward_2->setTemplate('include/popups/popup_fleet_2');
                $this->popupFleet2Renderer->execute($fleet_forward_2, $this->user, $planet);
                $fleet_forward_3 = new ViewModel([]);
                $fleet_forward_3->setTemplate('include/popups/popup_fleet_3');
                $this->popupFleet3Renderer->execute($fleet_forward_3);
                $fleet_mooving_activity = new ViewModel([]);
                $fleet_mooving_activity->setTemplate('include/fleet_mooving_activity');
                $this->fleetMoovingActivity->execute($fleet_mooving_activity);
                
                $view
                    ->addChild($fleet_forward_1,        'fleet_forward_1')
                    ->addChild($fleet_forward_2,        'fleet_forward_2')
                    ->addChild($fleet_forward_3,        'fleet_forward_3')
                    ->addChild($fleet_mooving_activity, 'fleet_mooving_activity');
                $view->setVariable('result', 'YES');
                $view->setVariable('auth', 'YES');
                $view->setVariable('message', 'good');
            }
            catch(\Exception $e){
                $view->setVariable('result', 'ERR');
                $view->setVariable('auth', 'YES');
                $view->setVariable('message', $e->getMessage());
            }
        }
        else{
            $view->setVariable('result', 'ERR');
            $view->setVariable('auth', 'NO');
            $view->setVariable('message', 'Пользователь не авторизован');
        }
        return $view;
    }
    
    public function fleetmoovingupdaterAction()
    {
        $view = new ViewModel([]);
        $view->setTerminal(true);
        if($this->authController->isAuthorized()){
            try{
                $view->setVariable('result', 'YES');
                $view->setVariable('auth', 'YES');
                $view->setVariable('message', 'good');
            }
            catch(\Exception $e){
                $view->setVariable('result', 'ERR');
                $view->setVariable('auth', 'YES');
                $view->setVariable('message', $e->getMessage());
            }
        }
        else{
            $view->setVariable('result', 'ERR');
            $view->setVariable('auth', 'NO');
            $view->setVariable('message', 'Пользователь не авторизован');
        }
        return $view;
    }
    
}
