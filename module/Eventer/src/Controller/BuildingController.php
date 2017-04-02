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
        
        /**
         * @ переводим в объекты
         */
        $planet = $this->planetRepository->findOneBy($planet);
        $buildingType = $this->buildingTypeRepository->findOneBy('building_types.id = ' . $buildingType);
        if($this->authController->isAuthorized()){
            $this->user = $this->authController->getUser();
            /**
             * @ выбрать все строительные события, относящиеся к данному юзеру на данной планете
             */
            $events = $this->eventRepository->findAllEntities(
                'events.user = ' . $this->user->getId() .
                ' AND events.target_planet = ' . $planet->getId() .
                ' AND events.targetBuildingType IS NOT NULL'
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
                /**
                 * @ хватит ли ресурсов на планете
                 */
                $K              = $buildingType->getFactor() * $level;
                $metall         = $planet->getMetall()      - $buildingType->getConsumeMetall()     * $K;
                $heavygas       = $planet->getHeavyGas()    - $buildingType->getConsumeHeavygas()   * $K;
                $ore            = $planet->getOre()         - $buildingType->getConsumeOre()        * $K;
                $hydro          = $planet->getHydro()       - $buildingType->getConsumeHydro()      * $K;
                $titan          = $planet->getTitan()       - $buildingType->getConsumeTitan()      * $K;
                $darkmatter     = $planet->getDarkmatter()  - $buildingType->getConsumeDarkmatter() * $K;
                $redmatter      = $planet->getRedmatter()   - $buildingType->getConsumeRedmatter()  * $K;
                $anti           = $planet->getAnti()        - $buildingType->getConsumeAnti()       * $K;
                $electricity    = $planet->getElectricity() - $buildingType->getConsumeElectricity()* $K;
                /*
                print_r(array(
                    'metall'        => $planet->getMetall(), 
                    'hydro'         => $planet->getHydro(), 
                    'heavygas'      => $planet->getHeavyGas(), 
                    'ore'           => $planet->getOre(), 
                    'hydro'         => $planet->getHydro(), 
                    'titan'         => $planet->getTitan(), 
                    'darkmatter'    => $planet->getDarkmatter(), 
                    'redmatter'     => $planet->getRedmatter(),
                    'anti'          => $planet->getAnti(), 
                    'electricity'   => $planet->getElectricity()));
                print_r(array(
                    'metall'        => $buildingType->getConsumeMetall(), 
                    'hydro'         => $buildingType->getConsumeHydro(), 
                    'heavygas'      => $buildingType->getConsumeHeavygas(), 
                    'ore'           => $buildingType->getConsumeOre(), 
                    'hydro'         => $buildingType->getConsumeHydro(), 
                    'titan'         => $buildingType->getConsumeTitan(), 
                    'darkmatter'    => $buildingType->getConsumeDarkmatter(), 
                    'redmatter'     => $buildingType->getConsumeRedmatter(),
                    'anti'          => $buildingType->getConsumeAnti(), 
                    'electricity'   => $buildingType->getConsumeElectricity()));
                print_r(array(
                    'metall'        => $metall, 
                    'hydro'         => $hydro, 
                    'heavygas'      => $heavygas, 
                    'ore'           => $ore, 
                    'hydro'         => $hydro, 
                    'titan'         => $titan, 
                    'darkmatter'    => $darkmatter, 
                    'redmatter'     => $redmatter,
                    'anti'          => $anti, 
                    'electricity'   => $electricity));
                */
                if(
                    $metall      >= 0 && 
                    $heavygas    >= 0 && 
                    $ore         >= 0 && 
                    $hydro       >= 0 && 
                    $titan       >= 0 && 
                    $darkmatter  >= 0 && 
                    $redmatter   >= 0 &&
                    $anti        >= 0 &&
                    $electricity >= 0
                    ) {
                    /**
                     * @ ресурсов хватает - строим
                     */
                    /**
                     * @ время на строительство
                     */
                    $time = intval(ceil($K * $buildingType->getConsumeAll() / 30));
                    $event = new Event(
                        $buildingType->getName(),
                        $buildingType->getDescription(),
                        $this->user,
                        EventTypes::$DO_BUILD_RESOURCES,
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
                     * @ на планете стало меньше ресурсов, электричество изменится при окончании строительства
                     */
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
                    $view->setVariable('data', array('result' => 'YES', 'auth' => 'YES', 'message' => 'Строительство успешно началось!'));
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
                $K              = $buildingType->getFactor() * $level;
                $metall         = $planet->getMetall()      + $buildingType->getConsumeMetall()     * $K;
                $heavygas       = $planet->getHeavyGas()    + $buildingType->getConsumeHeavygas()   * $K;
                $ore            = $planet->getOre()         + $buildingType->getConsumeOre()        * $K;
                $hydro          = $planet->getHydro()       + $buildingType->getConsumeHydro()      * $K;
                $titan          = $planet->getTitan()       + $buildingType->getConsumeTitan()      * $K;
                $darkmatter     = $planet->getDarkmatter()  + $buildingType->getConsumeDarkmatter() * $K;
                $redmatter      = $planet->getRedmatter()   + $buildingType->getConsumeRedmatter()  * $K;
                $anti           = $planet->getAnti()        + $buildingType->getConsumeAnti()       * $K;
                
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
    
    public function buildingAction()
    {
        $view = new ViewModel([]);
        $view->setTerminal(true);
        
        if($this->authController->isAuthorized()){
            
        }
        
        return $view;
    }
    
}
