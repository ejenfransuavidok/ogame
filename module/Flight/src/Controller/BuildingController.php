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
use Entities\Model\BuildingRepository;
use Entities\Model\BuildingCommand;
use Entities\Model\Building;
use Universe\Model\GalaxyRepository;
use Universe\Model\PlanetSystemRepository;
use Universe\Model\Planet;
use Universe\Model\PlanetRepository;
use Universe\Model\PlanetCommand;
use Universe\Model\SputnikRepository;
use Universe\Model\StarRepository;
use Settings\Model\Setting;
use Settings\Model\SettingsRepositoryInterface;
use Entities\Classes\EventTypes;

class BuildingController extends AbstractActionController
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
     * @ var PlanetRepository
     */
    private $planetRepository;
    
    /**
     * @ var PlanetCommand
     */
    private $planetCommand;
    
    /**
     * @SettingsRepositoryInterface $settingsRepository
     */
    private $settingsRepository;
    
    /**
     * @ BuildingCommand
     */
    private $buildingCommand;
    
    /**
     * @ BuildingRepository
     */
    private $buildingRepository;
    
    public function __construct(
        AdapterInterface $db, 
        UserRepository $userRepository,
        UserCommand $userCommand,
        PlanetRepository $planetRepository,
        PlanetCommand $planetCommand,
        SettingsRepositoryInterface $settingsRepository,
        BuildingCommand $buildingCommand,
        BuildingRepository $buildingRepository)
    {
        $this->dbAdapter = $db;
        $this->userRepository = $userRepository;
        $this->userCommand = $userCommand;
        $this->planetRepository = $planetRepository;
        $this->planetCommand = $planetCommand;
        $this->settingsRepository = $settingsRepository;
        $this->buildingCommand = $buildingCommand;
        $this->buildingRepository = $buildingRepository;
        
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
    
    public function indexAction()
    {
         if ($this->auth->hasIdentity()) {
            $identity = $this->auth->getIdentity();
            $user = $this->userRepository->findOneBy("users.login = '" . $identity . "'");
            $buildings = $this->buildingRepository->findBy('buildings.owner = ' . $user->getId())->buffer();
            return new ViewModel([
                'auth'      => $this->auth,
                'buildings' => $buildings
            ]);
        }
        return new ViewModel(['auth' => $this->auth]);
    }
    
    public function updateAction()
    {
        $result = array();
        if ($this->auth->hasIdentity()) {
            $identity = $this->auth->getIdentity();
            $user = $this->userRepository->findOneBy("users.login = '" . $identity . "'");
            $buildings = $this->buildingRepository->findBy('buildings.owner = ' . $user->getId())->buffer();
            $now = time();
            foreach($buildings as $building) {
                $update = $building->getUpdate();
                if($now > $update) {
                    $K = intval(ceil(($now - $update) / Building::$DELTA_REFRESH));
                    $planet = $building->getPlanet();
                    $type = $planet->getType();
                    $metall = intval(ceil($K * $type->getMetall() * $building->getProduceMetall() + $planet->getMetall()));
                    $heavygas = intval(ceil($K * $type->getHeavyGas() * $building->getProduceHeavygas() + $planet->getHeavyGas()));
                    $ore = intval(ceil($K * $type->getOre() * $building->getProduceOre() + $planet->getOre()));
                    $hydro = intval(ceil($K * $type->getHydro() * $building->getProduceHydro() + $planet->getHydro()));
                    $titan = intval(ceil($K * $type->getTitan() * $building->getProduceTitan() + $planet->getTitan()));
                    $darkmatter = intval(ceil($K * $type->getDarkmatter() * $building->getProduceDarkmatter() + $planet->getDarkmatter()));
                    $redmatter = intval(ceil($K * $type->getRedmatter() * $building->getProduceRedmatter() + $planet->getRedmatter()));
                    $anti = intval(ceil($K * $type->getAnti() * $building->getProduceAnti() + $planet->getAnti()));
                    /* end */
                    $building->setUpdate($now + Building::$DELTA_REFRESH);
                    $building = $this->buildingCommand->updateEntity($building);
                    $planet->setMetall($metall);
                    $planet->setHeavyGas($heavygas);
                    $planet->setOre($ore);
                    $planet->setHydro($hydro);
                    $planet->setTitan($titan);
                    $planet->setDarkmatter($darkmatter);
                    $planet->setRedmatter($redmatter);
                    $planet->setAnti($anti);
                    $planet = $this->planetCommand->updateEntity($planet);
                    
                    $result[$building->getId()]['metall']       = $building->getProduceMetall()     ?   $metall         : 0;
                    $result[$building->getId()]['heavygas']     = $building->getProduceHeavygas()   ?   $heavygas       : 0;
                    $result[$building->getId()]['ore']          = $building->getProduceOre()        ?   $ore            : 0;
                    $result[$building->getId()]['hydro']        = $building->getProduceHydro()      ?   $hydro          : 0;
                    $result[$building->getId()]['titan']        = $building->getProduceTitan()      ?   $titan          : 0;
                    $result[$building->getId()]['darkmatter']   = $building->getProduceDarkmatter() ?   $darkmatter     : 0;
                    $result[$building->getId()]['redmatter']    = $building->getProduceRedmatter()  ?   $redmatter      : 0;
                    $result[$building->getId()]['anti']         = $building->getProduceAnti()       ?   $anti           : 0;
                }
                else {
                    $result['result']   = 'TIME';
                    $result['time']     = $update;
                    $result['now']      = time();
                    $result['building'] = $building->getName();
                }
            }
        }
        else {
            $result['result'] = 'ERROR';
        }
        $view = new ViewModel(['data' => array('result' => $result)]);
        $view->setTerminal(true);
        return $view;
    }
    
}
