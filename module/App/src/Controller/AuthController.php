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
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Entities\Model\UserRepository;
use Entities\Model\UserCommand;
use Entities\Model\User;
use Universe\Model\Planet;
use Universe\Model\PlanetRepository;
use Universe\Model\PlanetCommand;

class UserIsNotAuthorizedException extends \Exception {}
class UserHaveNoAnyPlanetsException extends \Exception {}
class UserEmailDuplicateException extends \Exception {}
class PlanetNameDuplicateException extends \Exception {}

class AuthController extends AbstractActionController
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
    
    public function __construct(
        AdapterInterface $db, 
        UserRepository $userRepository,
        UserCommand $userCommand,
        PlanetRepository $planetRepository,
        PlanetCommand $planetCommand
        )
    {
        $this->dbAdapter = $db;
        $this->userRepository = $userRepository;
        $this->userCommand = $userCommand;
        $this->planetRepository = $planetRepository;
        $this->planetCommand = $planetCommand;
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
    
    public function authAction()
    {
        if(!$this->auth->hasIdentity()){
            /**
             * @ попапы на странице приветствия
             */
            $contacts = new ViewModel([]);
            $contacts->setTemplate('include/popups/contacts');
            $support = new ViewModel([]);
            $support->setTemplate('include/popups/support');
            $rules = new ViewModel([]);
            $rules->setTemplate('include/popups/rules');
            $confident = new ViewModel([]);
            $confident->setTemplate('include/popups/confident');
            $conditions = new ViewModel([]);
            $conditions->setTemplate('include/popups/conditions');
            
            $layout = $this->layout();
            
            $layout->setTemplate('app/layout');
            
            $layout->addChild($contacts,    'popup_contacts')
                ->addChild($support,        'popup_support')
                ->addChild($rules,          'popup_rules')
                ->addChild($confident,      'popup_confident')
                ->addChild($conditions,     'popup_conditions');
            
            $login_form = new ViewModel();
            $login_form->setTemplate('app/auth/login');
            
            $register_form = new ViewModel();
            $register_form->setTemplate('app/auth/register');
            /**
             * @
             */
            $view = new ViewModel([]);
            $view
                ->addChild($login_form, 'login')
                ->addChild($register_form, 'register');
            
            return $view;
        }
        else {
            // тут надо редиректить на первую планету владельца...
            $planets = $this->getUsersPlanets();
            $planet = $planets->current();
            return $this->redirect()->toRoute('app/planet', ['planetid' => $planet->getId()]);
        }
    }
    
    public function doauthAction()
    {
        if(!$this->auth->hasIdentity()){
            $this->authAdapter
                 ->setIdentity($_REQUEST['login'])
                 ->setCredential($_REQUEST['password']);
            $result = $this->auth->authenticate($this->authAdapter);
            if(!$this->auth->hasIdentity()){
                $result = array('message' => 'Логин или пароль неверны', 'result' => 'error');
            }
            else {
                // тут надо редиректить на первую планету владельца...
                $planets = $this->getUsersPlanets();
                $planet = $planets->current();
                $result = array(
                    'message'   => 'Авторизация прошла успешно', 
                    'result'    => 'ok', 
                    'redirect'  => $this->url()->fromRoute('app/planet', ['planetid' => $planet->getId()]),
                    'type'      => 'auth'
                );
            }
        }
        else{
            $result = array('message' => 'Вы уже авторизованы на сайте', 'result' => 'error');
        }
        $view = new ViewModel(['data' => array('result' => $result)]);
        $view->setTerminal(true);
        return $view;
    }
    
    public function dologoutAction()
    {
        if($this->auth->hasIdentity()){
            $this->auth->clearIdentity();
            $result = array('message' => 'Вы вышли из системы', 'result' => 'ok', 'redirect' => $this->url()->fromRoute('app', []));
        }
        else{
            $result = array('message' => 'Вы не авторизованы на сайте', 'result' => 'error');
        }
        $view = new ViewModel(['data' => array('result' => $result)]);
        $view->setTerminal(true);
        return $view;
    }
    
    public function doregisterAction()
    {
        $result = array();
        try{
            $user = $this->userRepository->findOneBy('users.login = ' . $_REQUEST['login']);
            $result = array('message' => 'Пользователь с таким логином уже авторизован на сайте', 'result' => 'error');
        }
        catch(\Exception $e){
        }
        if(!$result){
            try{
                /**
                 * есть юзер с таким мылом в бд ?
                 */
                if($this->userRepository->findBy('users.email LIKE "%' . $_REQUEST['email'] . '%"')->count() == 0){
                    if($this->planetRepository->findBy('planets.name LIKE  "%' . $_REQUEST['planet'] . '%"')->count() == 0){
                        // тут надо редиректить на первую пустую планету...
                        $user = new User(null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
                        $user->setName($_REQUEST['login']);
                        $user->setPassword(md5($_REQUEST['password']));
                        $user->setEmail($_REQUEST['email']);
                        $user = $this->userCommand->insertEntity($user);
                        /**
                         * @ залогиним
                         */
                        $this->authAdapter
                             ->setIdentity($_REQUEST['login'])
                             ->setCredential($_REQUEST['password']);
                        $result = $this->auth->authenticate($this->authAdapter);
                        /**
                         * @ назначим планету
                         */
                        $planets = $this->getFreePlanet();
                        $planet = $planets->current();
                        $planet->setOwner($user);
                        $planet->setName($_REQUEST['planet']);
                        /**
                         * @ дать новым юзерам по 1000 матала и 1000 газа
                         */
                        $planet->setMetall(1000);
                        $planet->setHeavyGas(1000);
                        $planet = $this->planetCommand->updateEntity($planet);
                        $result = array(
                            'message'   => 'Поздравляем! Вы успешно зарегистрированы на нашем сайте!', 
                            'result'    => 'ok',
                            'type'      => 'register',
                            'redirect'  => $this->url()->fromRoute('app/planet', ['planetid' => $planet->getId()])
                        );
                    }
                    else{
                        throw new PlanetNameDuplicateException('Планета с названием ' . $_REQUEST['planet'] . ' уже существует!');
                    }
                }
                else{
                    throw new UserEmailDuplicateException('Пользователь с почтой ' . $_REQUEST['email'] . ' уже зарегистрирован!');
                }
            }
            catch(\Exception $e){
                $result = array('message' => $e->getMessage(), 'result' => 'error');
            }
        }
        $view = new ViewModel(['data' => array('result' => $result)]);
        $view->setTerminal(true);
        return $view;
    }
    
    public function isAuthorized()
    {
        return $this->auth->hasIdentity();
    }
    
    public function getUser()
    {
        if($this->isAuthorized()){
            return $this->userRepository->findOneBy('users.login = "' . $this->auth->getIdentity() . '"');
        }
        else{
            return null;
        }
    }
    
    public function getFreePlanet()
    {
        $planets = $this->planetRepository->findBy('planets.owner IS NULL');
        return $planets;
    }
    
    public function getUsersPlanets()
    {
        if($this->isAuthorized()){
            $user = $this->getUser();
            // вернем все планеты юзера
            $planets = $this->planetRepository->findBy('planets.owner = ' . $user->getId());
            if($planets){
                return $planets;
            }
            else{
                throw new UserHaveNoAnyPlanetsException('User have no any planets');
            }
        }
        else{
            throw new UserIsNotAuthorizedException('User is not authorithed');
        }
    }
    
}
