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
use Universe\Model\GalaxyRepository;
use Universe\Model\PlanetSystemRepository;
use Universe\Model\PlanetRepository;
use Universe\Model\SputnikRepository;
use Universe\Model\StarRepository;


define('INIT', 'TRUE');

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
    
    public function __construct(
        AdapterInterface $db, 
        UserRepository $userRepository,
        UserCommand $userCommand,
        GalaxyRepository $galaxyRepository,
        PlanetSystemRepository $planetSystemRepository,
        PlanetRepository $planetRepository,
        SputnikRepository $sputnikRepository,
        StarRepository $starRepository)
    {
        $this->dbAdapter = $db;
        $this->userRepository = $userRepository;
        $this->userCommand = $userCommand;
        $this->galaxyRepository = $galaxyRepository;
        $this->planetSystemRepository = $planetSystemRepository;
        $this->planetRepository = $planetRepository;
        $this->sputnikRepository = $sputnikRepository;
        $this->starRepository = $starRepository;
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
    
    public function indexAction()
    {
        if ($this->auth->hasIdentity()) {
            $identity = $this->auth->getIdentity();
            $user = $this->userRepository->findOneBy("users.login = '" . $identity . "'");
            if(INIT == 'TRUE') {
                $user = $this->InstallPositionOfUser($user);
            }
            ///$this->auth->clearIdentity();
        }
        return new ViewModel(['auth' => $this->auth, 'user' => 'Привет!']);
    }
}
