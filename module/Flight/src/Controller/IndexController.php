<?php

/**
 * 
 * @ author Jigulin V.V.
 * @ 07.02.2017
 * 
 */

namespace Flight\Controller;

require_once (dirname(dirname(dirname(dirname(__FILE__)))) . '/Entities/src/Classes/EventTypes.php');

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Entities\Model\UserRepository;
use Entities\Model\UserCommand;
use Entities\Model\User;
use Entities\Model\SpaceSheepRepository;
use Entities\Model\SpaceSheepCommand;
use Entities\Model\SpaceSheep;
use Entities\Model\EventRepository;
use Entities\Model\EventCommand;
use Entities\Model\Event;
use Universe\Model\GalaxyRepository;
use Universe\Model\PlanetSystemRepository;
use Universe\Model\PlanetRepository;
use Universe\Model\SputnikRepository;
use Universe\Model\StarRepository;
use Settings\Model\Setting;
use Settings\Model\SettingsRepositoryInterface;
use Entities\Classes\EventTypes;

define('INIT', 'FALSE');

class NoSpaceSheepsInDBase extends \Exception
{
}

class NoSelectExpected extends \Exception
{
}

class IndexController extends AbstractActionController
{
    /**
     * @var AdapterInterface
     */
    private $dbAdapter;
    
    /**
     * @var AuthAdapter
     */
    private $authAdapter;
    
    /**
     * @var AuthenticationService
     */
    private $auth;
    
    /**
     * @ var UserRepository
     */
    private $userRepository;
    
    /**
     * @ var UserCommand
     */
    private $userCommand;
    
    /**
     * @ var GalaxyRepository
     */
    private $galaxyRepository;
    
    /**
     * @ var PlanetSystemRepository
     */
    private $planetSystemRepository;
    
    /**
     * @ var PlanetRepository
     */
    private $planetRepository;
    
    /**
     * @ var SputnikRepository
     */
    private $sputnikRepository;
    
    /**
     * @ var StarRepository
     */
    private $starRepository;
    
    /**
     * @ var SpaceSheepCommand
     */
    private $spaceSheepCommand;
    
    /**
     * @ var SpaceSheepRepository
     */
    private $spaceSheepRepository;
    
    /**
     * @SettingsRepositoryInterface $settingsRepository
     */
    private $settingsRepository;
    
    /**
     * @ EventRepository
     */
    private $eventRepository;
    
    /**
     * @ EventCommand
     */
    private $eventCommand;
    
    
    public function __construct(
        AdapterInterface $db, 
        UserRepository $userRepository,
        UserCommand $userCommand,
        GalaxyRepository $galaxyRepository,
        PlanetSystemRepository $planetSystemRepository,
        PlanetRepository $planetRepository,
        SputnikRepository $sputnikRepository,
        StarRepository $starRepository,
        SpaceSheepCommand $spaceSheepCommand,
        SpaceSheepRepository $spaceSheepRepository,
        SettingsRepositoryInterface $settingsRepository,
        EventRepository $eventRepository,
        EventCommand $eventCommand)
    {
        $this->dbAdapter = $db;
        $this->userRepository = $userRepository;
        $this->userCommand = $userCommand;
        $this->galaxyRepository = $galaxyRepository;
        $this->planetSystemRepository = $planetSystemRepository;
        $this->planetRepository = $planetRepository;
        $this->sputnikRepository = $sputnikRepository;
        $this->starRepository = $starRepository;
        $this->spaceSheepCommand = $spaceSheepCommand;
        $this->spaceSheepRepository = $spaceSheepRepository;
        $this->settingsRepository = $settingsRepository;
        $this->eventRepository = $eventRepository;
        $this->eventCommand = $eventCommand;
        
        $this->authAdapter = new AuthAdapter(
            $this->dbAdapter,
            'users',
            'login',
            'password',
            'MD5(?)'
        );
        $this->auth = new AuthenticationService();
        $this->auth->setStorage(new SessionStorage('someNamespace'));
    }
    
    public function authorizeAction()
    {
        $this->authAdapter
             ->setIdentity($_REQUEST['login'])
             ->setCredential($_REQUEST['password']);
        $result = $this->auth->authenticate($this->authAdapter);
        return $this->redirect()->toRoute('flight', ['action' => 'index']);
    }
    
    private function InstallPositionOfUser(User $user)
    {
        $galaxy = $this->galaxyRepository->findEntity(1);
        $planet_system = $this->planetSystemRepository->findEntity(1);
        $planet = $this->planetRepository->findEntity(1);
        $star = $planet_system->getStar();
        $user->setGalaxy($galaxy);
        $user->setPlanetSystem($planet_system);
        $user->setPlanet($planet);
        $user->setStar($star);
        $user = $this->userCommand->updateEntity($user);
        return $user;
    }
    
    /**
     * назначение юзеру кораблей
     */
    private function SetSheepsForUser(User $user)
    {
        $sheeps = $this->spaceSheepRepository->findBy('spacesheeps.owner IS NULL');
        if(!$sheeps) {
            throw new NoSpaceSheepsInDBase('no space sheeps into data of base');
        }
        else {
            /**
             * Удаляем предыдущие
             */
            try {
                $oldsheeps = $this->spaceSheepRepository->findBy('spacesheeps.owner = ' . $user->getId());
                foreach($oldsheeps as $sheep) {
                    $this->spaceSheepCommand->deleteEntity($sheep);
                }
            }
            catch (\Exception $e) {
            /**
             * кораблей не было
             */
            }
            /**
             * @ добавляем новые корабли
             */
            foreach($sheeps as $sheep) {
                $new = new SpaceSheep(
                    $sheep->getName(),
                    $sheep->getDescription(),
                    $sheep->getSpeed(),
                    $sheep->getCapacity(),
                    $sheep->getFuelConsumption(),
                    $sheep->getFuelTankSize(),
                    $sheep->getAttakPower(),
                    $sheep->getRateOfFire(),
                    $sheep->getTheNumberOfAttakTarget(),
                    $sheep->getSheepSize(),
                    $sheep->getProtection(),
                    $sheep->getNumberOfGuns(),
                    $sheep->getConstructionTime(),
                    $sheep->getFuelRest(),
                    $sheep->getGalaxy(),
                    $sheep->getPlanetSystem(),
                    $sheep->getStar(),
                    $sheep->getPlanet(),
                    $sheep->getSputnik(),
                    $sheep->getOwner()
                    );
                $new->setGalaxy($user->getGalaxy());
                $new->setPlanetSystem($user->getPlanetSystem());
                $new->setPlanet($user->getPlanet());
                $new->setStar($user->getStar());
                $new->setSputnik($user->getSputnik());
                $new->setOwner($user);
                $this->spaceSheepCommand->insertEntity($new);
            }
        }
    }
    
    public function indexAction()
    {
        $sheeps = array();
        if ($this->auth->hasIdentity()) {
            $identity = $this->auth->getIdentity();
            $user = $this->userRepository->findOneBy("users.login = '" . $identity . "'");
            if(INIT == 'TRUE') {
                $user = $this->InstallPositionOfUser($user);
                $this->SetSheepsForUser($user);
            }
            $sheeps = $this->spaceSheepRepository->findBy('spacesheeps.owner = ' . $user->getId());
            $galaxies = $this->galaxyRepository->findAllEntities();
            ///$this->auth->clearIdentity();
            return new ViewModel([
                'auth' => $this->auth, 
                'sheeps' => $sheeps, 
                'galaxies' => $galaxies, 
                'planet_systems' => null,
                'planets' => null,
                'stars' => null,
                'sputniks' => null]);
        }
        
        return new ViewModel(['auth' => $this->auth]);
    }
    
    public function updateAction()
    {
        $name = $_REQUEST['name'];
        $value = $_REQUEST['value'];
        $result = array();
        switch($name) {
            case 'galaxy_select':
                $result['planet_system_select'] = array();
                $result['star_select'] = array();
                $result['planet_select'] = array();
                $result['sputnik_select'] = array();
                $planets_systems = $this->planetSystemRepository->findBy('planet_system.galaxy = ' . $value);
                if(count($planets_systems)) {
                    foreach($planets_systems as $planet_system) {
                        $result['planet_system_select'][] = array('id' => $planet_system->getId(), 'name' => $planet_system->getName());
                    }
                }
                break;
            case 'planet_system_select':
                $result['star_select'] = array();
                $result['planet_select'] = array();
                $result['sputnik_select'] = array();
                $planets =  $this->planetRepository->findBy('planets.planet_system = ' . $value);
                $stars = $this->starRepository->findBy('stars.planet_system = ' . $value);
                if(count($planets)) {
                    foreach($planets as $planet) {
                        $result['planet_select'][] = array('id' => $planet->getId(), 'name' => $planet->getName());
                    }
                }
                if(count($stars)) {
                    foreach($stars as $star) {
                        $result['star_select'][] = array('id' => $star->getId(), 'name' => $star->getName());
                    }
                }
                break;
            case 'planet_select':
                $result['sputnik_select'] = array();
                $sputniks = $this->sputnikRepository->findBy('sputniks.parent_planet = ' . $value);
                if(count($sputniks)) {
                    foreach($sputniks as $sputnik) {
                        $result['sputnik_select'][] = array('id' => $sputnik->getId(), 'name' => $sputnik->getName());
                    }
                }
                break;
            case 'star_select':
                break;
            case 'sputnik_select':
                break;
            default:
                throw new NoSelectExpected(name + ' value unexpected!');
                break;
        }
        die(json_encode(array("result" => $result)));
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
    
    public function createEvent(&$sheeps, $user, $target, $target_type, $event_duration, $event_type)
    {
        foreach($sheeps as $sheep) {
            if($sheep->getEvent() && $sheep->getEvent()->getEventType() == $event_type) {
                die(json_encode(
                    array (
                        'result' => '<p style="color: red">По крайней мере один из кораблей флота - ' . $sheep->getName() . ' уже выполняет это же действие!</p>'
                        )
                    )
                );
            }
        }
        $event = new Event(
            'Перелет на ' . $target->getName(),
            '',
            $user,
            $event_type,
            time(),
            time() + $event_duration * 60,
            null,
            ($target_type == "planet" ? $target : null),
            ($target_type == "sputnik" ? $target : null)
            );
        $event = $this->eventCommand->insertEntity($event);
        foreach($sheeps as $sheep) {
            $sheep->setEvent($event);
            $this->spaceSheepCommand->updateEntity($sheep);
        }
    }
    
    public function calcAction()
    {
        $this->FUEL_DISTANCE_CALC = intval($this->settingsRepository->findSettingByKey ('FUEL_DISTANCE_CALC ')->getText());
        $this->TIME_FACTOR_CALC = intval($this->settingsRepository->findSettingByKey ('TIME_FACTOR_CALC ')->getText());
        $target         = $_REQUEST['target'];
        $galaxy         = $_REQUEST['galaxy'];
        $planet_system  = $_REQUEST['planet_system'];
        $planet         = $_REQUEST['planet'];
        $star           = $_REQUEST['star'];
        $sputnik        = $_REQUEST['sputnik'];
        if ($this->auth->hasIdentity()) {
            $identity = $this->auth->getIdentity();
            $user = $this->userRepository->findOneBy("users.login = '" . $identity . "'");
            $sheeps = $this->spaceSheepRepository->findBy('spacesheeps.owner = ' . $user->getId())->buffer();
            $this->calcFlot($sheeps);
            if($target == 'planet') {
                $target = $this->planetRepository->findEntity(intval($planet));
            }
            else {
                $target = $this->sputnikRepository->findEntity(intval($sputnik));
            }
            $html = '';
            $distance = abs($target->getCoordinate() - $user->getPlanet()->getCoordinate());
            $html .= ('<p>Дистанция полета ' . $distance . ' ед.</p>');
            $float_time = 0;
            $can = true;
            foreach($sheeps as $sheep) {
                $html .= ('<p>' . $sheep->getName() . ' преодолеет максимальное расстояние в ' . $sheep->getDistance() . ' ед.');
                if($distance > $sheep->getDistance()) {
                    $html .= ('<p style="color: red"> > Не долетит!</p>');
                    $can = false;
                }
                else {
                    $time = $sheep->calcTime($this->TIME_FACTOR_CALC, $distance);
                    $float_time = max($time, $float_time);
                    $html .= ('<p style="color: green"> > Долетит! Время на перелет составит ' . $time .  ' минут.</p>');
                }
            }
            if($can){
                $html .= ('<p style="color: green"> > Флот долетит! Время на перелет составит ' . $float_time .  ' минут.</p>');
            }
            else{
                $html .= ('<p style="color: red"> > Флот не долетит!</p>');
            }
            if($can){
                $this->createEvent($sheeps, $user, $target, $_REQUEST['target'], $float_time, EventTypes::$FLOT_RELOCATION);
            }
            $result = array ('result' => $html);
            die (json_encode($result));
        }
        else {
            $result = array ('result' => '<p>Пользователь не авторизован</p>');
            die (json_encode($result));
        }
    }
    
    public function startAction()
    {
        $result = array ('result' => '<p>Пользователь не авторизован</p>');
        die (json_encode($result));
    }
    
    public function GetAllCoordinatesByPlanet($planet, &$star, &$planet_system, &$galaxy)
    {
        $planet_system = $planet->getCelestialParent();
        $star = $planet_system->getStar();
        $galaxy = $planet_system->getGalaxy();
    }
    
    public function GetAllCoordinatesBySputnik($sputnik, &$planet, &$star, &$planet_system, &$galaxy)
    {
        $planet = $sputnik->getParentPlanet();
        $planet_system = $planet->getCelestialParent();
        $star = $planet_system->getStar();
        $galaxy = $planet_system->getGalaxy();
    }
    
    public function checkAction()
    {
        if ($this->auth->hasIdentity()) {
            $identity = $this->auth->getIdentity();
            $user = $this->userRepository->findOneBy("users.login = '" . $identity . "'");
            $events = $this->eventRepository->findBy("events.user = '" . $user->getId() . "'")->buffer();
            if($events){
                foreach($events as $event){
                    $event_begin = $event->getEventBegin();
                    $event_end = $event->getEventEnd();
                    $now = time();
                    if($now > $event_end) {
                        // событие окончено
                        $planet = null;
                        $star = null;
                        $planet_system = null;
                        $galaxy = null;
                        $event->getTargetPlanet() 
                            ? $this->GetAllCoordinatesByPlanet($event->getTargetPlanet(), $star, $planet_system, $galaxy)
                            : $this->GetAllCoordinatesBySputnik($event->getTargetSputnik(), $planet, $star, $planet_system, $galaxy);
                        $sheeps = $this->spaceSheepRepository->findBy('spacesheeps.event = ' . $event->getId())->buffer();
                        if($sheeps){
                            foreach($sheeps as $sheep){
                                // корабль в новой локации, обновим координаты
                                $sheep->setPlanet($event->getTargetPlanet() ? $event->getTargetPlanet() : $planet);
                                $sheep->setSputnik($event->getTargetSputnik() ? $event->getTargetSputnik() : null);
                                $sheep->setStar($star);
                                $sheep->setPlanetSystem($planet_system);
                                $sheep->setGalaxy($galaxy);
                                $sheep->setEvent(null);
                                $this->spaceSheepCommand->updateEntity($sheep);
                            }
                        }
                        // обновим позицию юзера
                        $user->setPlanet($event->getTargetPlanet() ? $event->getTargetPlanet() : $planet);
                        $user->setSputnik($event->getTargetSputnik() ? $event->getTargetSputnik() : null);
                        $user->setStar($star);
                        $user->setPlanetSystem($planet_system);
                        $user->setGalaxy($galaxy);
                        $this->userCommand->updateEntity($user);
                        // удалим само событие
                        $this->eventCommand->deleteEntity($event);
                        die(json_encode(array("message" => "<p>Полет окончен!</p>", "progress" => "100", "end" => true)));
                    }
                    else {
                        // еще не прилетели
                        $progress = ceil(100 * (1 - ($event_end - $now) / ($event_end - $event_begin)));
                        die(json_encode(array("message" => "<p style='color: green;'>Флот в полете.</p>", "progress" => $progress, "end" => false)));
                    }
                }
            }
            else {
                die(json_encode(array("message" => "<p>Активных полетов нет!</p>", "progress" => "0", "end" => false)));
            }
            die(json_encode(array("message" => "<p>Активных полетов нет!</p>", "progress" => "0", "end" => false)));
        }
    }
    
}
