<?php

/**
 * 
 * @ author Jigulin V.V.
 * @ 07.02.2017
 * 
 */

namespace Flight\Controller;

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
use Universe\Model\GalaxyRepository;
use Universe\Model\PlanetSystemRepository;
use Universe\Model\PlanetRepository;
use Universe\Model\SputnikRepository;
use Universe\Model\StarRepository;


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
        SpaceSheepRepository $spaceSheepRepository)
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
    
    public function calcAction()
    {
        $target         = $_REQUEST['target'];
        $galaxy         = $_REQUEST['galaxy'];
        $planet_system  = $_REQUEST['planet_system'];
        $planet         = $_REQUEST['planet'];
        $star           = $_REQUEST['star'];
        $sputnik        = $_REQUEST['sputnik'];
        if ($this->auth->hasIdentity()) {
            $identity = $this->auth->getIdentity();
            $user = $this->userRepository->findOneBy("users.login = '" . $identity . "'");
            if($target == 'planet') {
                $target = $this->planetRepository->findEntity(intval($planet));
            }
            else {
                $target = $this->sputnikRepository->findEntity(intval($sputnik));
                $parent_planet = $target->getParentPlanet();
                $target = $parent_planet;
            }
            $html = '';
            $distance = abs($target->getCoordinate() - $user->getPlanet()->getCoordinate());
            $html .= ('<p>Дистанция полета ' . $distance . ' ед.</p>');
            
            $result = array ('result' => $html);
            die (json_encode($result));
        }
        else {
            $result = array ('result' => 'Пользователь не авторизован');
            die (json_encode($result));
        }
    }
    
}
