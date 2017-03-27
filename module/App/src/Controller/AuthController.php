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
    
    public function __construct(
        AdapterInterface $db, 
        UserRepository $userRepository,
        UserCommand $userCommand
        )
    {
        $this->dbAdapter = $db;
        $this->userRepository = $userRepository;
        $this->userCommand = $userCommand;
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
            $layout = $this->layout();
            
            $layout->setTemplate('app/layout');
            
            $login_form = new ViewModel();
            $login_form->setTemplate('app/auth/login');
            
            $register_form = new ViewModel();
            $register_form->setTemplate('app/auth/register');
            
            $view = new ViewModel([]);
            $view
                ->addChild($login_form, 'login')
                ->addChild($register_form, 'register');
            
            return $view;
        }
        else {
            return $this->redirect()->toRoute('app', ['action' => 'index']);
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
                $result = array('message' => 'Авторизация прошла успешно', 'result' => 'ok', 'redirect' => $this->url()->fromRoute('app', ['action' => 'index']));
            }
        }
        else{
            $result = array('message' => 'Вы уже авторизованы на сайте', 'result' => 'error');
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
                $user = new User(null,null,null,null,null,null,null,null,null,null,null,null);
                $user->setName($_REQUEST['login']);
                $user->setPassword(md5($_REQUEST['password']));
                $user->setEmail($_REQUEST['email']);
                $user = $this->userCommand->insertEntity($user);
                $result = array('message' => 'Поздравляем! Вы успешно зарегистрированы на нашем сайте!', 'result' => 'ok');
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
    
}
