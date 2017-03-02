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
    
    public function __construct(AdapterInterface $db)
    {
        $this->dbAdapter = $db;
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
            
            //$this->auth->clearIdentity();
        }
        return new ViewModel(['auth' => $this->auth, 'user' => 'Привет!']);
    }
}
