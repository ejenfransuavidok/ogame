<?php

namespace Flight\Classes;

use Entities\Model\SpaceSheepRepository;
use Entities\Model\SpaceSheep;
use Entities\Model\EventRepository;
use Entities\Model\Event;
use Entities\Model\User;
use App\Controller\AuthController;
use App\Classes\TimeUtils;
use Universe\Model\GalaxyRepository;
use Universe\Model\PlanetSystemRepository;
use Universe\Model\PlanetRepository;
use Universe\Model\SputnikRepository;
use Universe\Model\StarRepository;
use Universe\Model\Planet;
use Universe\Classes\UniversePosition;
use Universe\Classes\UniversePosition_P_PS_G;
use Settings\Model\Setting;
use Settings\Model\SettingsRepositoryInterface;
use Entities\Classes\EventTypes;
use Flight\Classes\FlightOrder;


class FlightCalculator
{
    
    /**
     * @ SpaceSheepRepository
     */
    private $spaceSheepRepository;
    
    /**
     * @ EventRepository
     */
    private $eventRepository;
    
    /**
     * @ GalaxyRepository
     */
    private $galaxyRepository;
    
    /**
     * @ PlanetSystemRepository
     */
    private $planetSystemRepository;
    
    /**
     * @ StarRepository
     */
    private $starRepository;
    
    /**
     * @ PlanetRepository
     */
    private $planetRepository;
    
    /**
     * @ SputnikRepository
     */
    private $sputnikRepository;
    
    /**
     * @ SettingsRepositoryInterface
     */
    private $settingsRepositoryInterface;
    
    /**
     * @ AuthController
     */
    private $authController;
    
    public function __construct(
            AuthController              $authController,
            SpaceSheepRepository        $spaceSheepRepository,
            EventRepository             $eventRepository,
            GalaxyRepository            $galaxyRepository,
            PlanetSystemRepository      $planetSystemRepository,
            StarRepository              $starRepository,
            PlanetRepository            $planetRepository,
            SputnikRepository           $sputnikRepository,
            SettingsRepositoryInterface $settingsRepository
        )
    {
        $this->authController               = $authController;
        $this->spaceSheepRepository         = $spaceSheepRepository;
        $this->eventRepository              = $eventRepository;
        $this->galaxyRepository             = $galaxyRepository;
        $this->planetSystemRepository       = $planetSystemRepository;
        $this->starRepository               = $starRepository;
        $this->planetRepository             = $planetRepository;
        $this->sputnikRepository            = $sputnikRepository;
        $this->settingsRepository           = $settingsRepository;
    }
    
    public function calculate(UniversePosition_P_PS_G $targetUniversePosition, Planet $currentPlanet)
    {
        $flightOrder = new FlightOrder();
        $this->FUEL_DISTANCE_CALC = intval($this->settingsRepository->findSettingByKey ('FUEL_DISTANCE_CALC ')->getText());
        $this->TIME_FACTOR_CALC = intval($this->settingsRepository->findSettingByKey ('TIME_FACTOR_CALC ')->getText());
        $user = $this->authController->getUser();
        $sheeps = $this->spaceSheepRepository->findBy('spacesheeps.owner = ' . $user->getId())->buffer();
        $this->calcFlot($sheeps);
        $flightOrder->setTarget($targetUniversePosition->getPlanet());
        $flightOrder->setDistance(abs($targetUniversePosition->getPlanet()->getCoordinate() - $currentPlanet->getCoordinate()));
        $flightOrder->setPeriod(0);
        $flightOrder->setCanGetTarget(true);
        foreach($sheeps as $sheep) {
            if($flightOrder->getDistance() > $sheep->getDistance()) {
                $flightOrder->setCanGetTarget(false);
            }
            else {
                $time = $sheep->calcTime($this->TIME_FACTOR_CALC, $flightOrder->getDistance());
                $flightOrder->setPeriod(max($time, $flightOrder->getPeriod()));
            }
        }
        if($flightOrder->getCanGetTarget()){
            /**
             * @ флот сможет долететь до цели
             */
            $flightOrder->setTime2OneEnd(TimeUtils::interval2String($flightOrder->getPeriod()));
            /**
             * @ сколько топлива потребят корабли на дорогу в один конец
             */
            /**
             * @ все топливо флота
             */
            $total_fuel = 0;
            /**
             * @ сколько топлива будет потрачено
             */
            $spend_total_fuel = 0;
            /**
             * @ максимальная скорость флота равна минимальной скорости корабля в флоте
             */
            $fleet_velocity = PHP_INT_MAX;
            /**
             * @ суммарная грузоподъемность
             */
            $total_capacity = 0;
            foreach($sheeps as $sheep){
                $total_fuel += $sheep->getFuelRest();
                $spend_total_fuel += $sheep->calcSpendFuelByDistance($flightOrder->getDistance());
                $fleet_velocity = min($fleet_velocity, $sheep->getSpeed());
                $total_capacity += $sheep->getCapacity();
            }
            $flightOrder->setSpendFuelAtOneEnd($spend_total_fuel . '(' . ceil(100 * $spend_total_fuel / $total_fuel) . '%)');
            /**
             * @ скорость полета флота максимальная
             */
            $flightOrder->setSpeed($fleet_velocity);
            /**
             * @ прибытие
             */
            $flightOrder->setArrival(date('Y-m-d H:i:s', time() + $flightOrder->getPeriod()));
            $flightOrder->setComeback(date('Y-m-d H:i:s', time() + 2 * $flightOrder->getPeriod()));
            /**
             * @ грузоподъемность
             */
            $flightOrder->setCapacity($total_capacity);
        }
        return $flightOrder;
    }
    
    /*
     * максимальная дистанция флота будет определяться кораблем с наименьшим пробегом
     */
    public function calcFlot(&$sheeps)
    {
        foreach($sheeps as $sheep) {
            $sheep->calcDistance($this->FUEL_DISTANCE_CALC);
        }
    }
    
}
