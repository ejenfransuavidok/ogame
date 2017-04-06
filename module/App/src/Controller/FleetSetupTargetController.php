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
use Entities\Model\Building;
use Entities\Model\BuildingRepository;
use Entities\Model\BuildingTypeRepository;
use Entities\Model\EventCommand;
use Entities\Model\EventRepository;
use Flight\Classes\FlightCalculator;

class FleetSetupTargetController extends AbstractActionController
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
        GalaxyRepository        $galaxyRepository,
        PlanetSystemRepository  $planetSystemRepository,
        PlanetRepository        $planetRepository,
        BuildingRepository      $buildingRepository,
        BuildingTypeRepository  $buildingTypeRepository,
        EventRepository         $eventRepository,
        EventCommand            $eventCommand,
        FlightCalculator        $flightCalculator
        )
    {
        $this->dbAdapter                = $db;
        $this->authController           = $authController;
        $this->galaxyRepository         = $galaxyRepository;
        $this->planetSystemRepository   = $planetSystemRepository;
        $this->planetRepository         = $planetRepository;
        $this->buildingRepository       = $buildingRepository;
        $this->buildingTypeRepository   = $buildingTypeRepository;
        $this->eventRepository          = $eventRepository;
        $this->eventCommand             = $eventCommand;
        $this->flightCalculator         = $flightCalculator;
        
        $this->coordinateResolver       = new CoordinateResolver($this->galaxyRepository, $this->planetSystemRepository, $this->planetRepository);
    }
    
    public function setuptargetAction()
    {
        $view = new ViewModel([]);
        $view->setTerminal(true);
        if($this->authController->isAuthorized()){
            $current_planet = $this->params()->fromPost('current_planet');
            $target_galaxy = $this->params()->fromPost('target_galaxy');
            $target_planet_system = $this->params()->fromPost('target_planet_system');
            $target_planet = $this->params()->fromPost('target_planet');
//echo ' [current_planet = ' . $current_planet . ' ][target_galaxy = ' . $target_galaxy . ' ][target_planet_system = ' . $target_planet_system . ' ][target_planet = ' . $target_planet . ' ]';
            $universePosition = new UniversePosition();
            $universePosition->setPlanetPosition($target_planet);
            $universePosition->setPlanetSystemPosition($target_planet_system);
            $universePosition->setGalaxyPosition($target_galaxy);
            if($targetUniversePosition = $this->coordinateResolver->resolveByUniversePosition($universePosition)){
                $currentPlanet = $this->planetRepository->findOneBy('planets.id = ' . $current_planet);
                $flightOrder = $this->flightCalculator->calculate($targetUniversePosition, $currentPlanet);
                $view->setVariable(
                    'data', 
                    array(
                        'message'           => 'coordinate resolved',
                        'result'            => 'YES',
                        /**
                         * @ Долетим ли ?
                         */
                        'fstc_CanGetTarget' => $flightOrder->getCanGetTarget(),
                        /**
                         * @ Время полета в одну сторону
                         */
                        'fstc_Time2OneEnd'  => $flightOrder->getTime2OneEnd(),
                        /**
                         * @ Потребление топлива в одну сторону 
                         */
                        'fstc_SpendFuelAtOneEnd' => $flightOrder->getSpendFuelAtOneEnd(),
                        /**
                         * @ Скорость полета (макс 5000) 
                         */
                        'fstc_Speed'        => $flightOrder->getSpeed(),
                        /**
                         * @ Прибытие 
                         */
                        'fstc_Arrival'      => $flightOrder->getArrival(),
                        /**
                         * @ Возврат
                         */
                        'fstc_Comeback'     => $flightOrder->getComeback(),
                        /**
                         * @ Грузоподьемность
                         */
                        'fstc_Capacity'     => $flightOrder->getCapacity(),
                        /**
                         * @ Расстояние в световых годах
                         */
                        'fstc_LightYears'   => floor($flightOrder->getDistance() / 1000)));
            }
            else{
                $view->setVariable('data', array('message' => 'error target'));
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
    
}
