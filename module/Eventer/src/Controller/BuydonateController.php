<?php

namespace Eventer\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\AdapterInterface;
use App\Controller\AuthController;
use Entities\Model\UserRepository;
use Entities\Model\UserCommand;
use Universe\Model\PlanetRepository;
use Universe\Model\PlanetCommand;
use Universe\Model\SputnikRepository;
use Universe\Model\SputnikCommand;
use Settings\Model\SettingsRepositoryInterface;


class BuydonateController extends AbstractActionController
{
    
    /**
     * @ AuthController
     */
    protected $authController;
    
    /**
     * @ UserRepository
     */
    protected $userRepository;
    
    /**
     * @ UserCommand
     */
    protected $userCommand;
    
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
    
    /**
     * @SettingsRepositoryInterface $settingsRepository
     */
    protected $settingsRepository;

    public function __construct(
        AdapterInterface            $db,
        AuthController              $authController,
        UserRepository              $userRepository,
        UserCommand                 $userCommand,
        PlanetRepository            $planetRepository,
        PlanetCommand               $planetCommand,
        SputnikRepository           $sputnikRepository,
        SputnikCommand              $sputnikCommand,
        SettingsRepositoryInterface $settingsRepository
        )
    {
        $this->dbAdapter                = $db;
        $this->authController           = $authController;
        $this->userRepository           = $userRepository;
        $this->userCommand              = $userCommand;
        $this->planetRepository         = $planetRepository;
        $this->planetCommand            = $planetCommand;
        $this->sputnikRepository        = $sputnikRepository;
        $this->sputnikCommand           = $sputnikCommand;
        $this->settingsRepository       = $settingsRepository;
    }
    
    public function buydonateAction()
    {
        $view = new ViewModel([]);
        $view->setTerminal(true);
        if($this->authController->isAuthorized()){
            try{
                $this->user = $this->authController->getUser();
                $planet = $this->params()->fromPost('planet');
                $amount = $this->params()->fromPost('amount');
                $planet = $this->planetRepository->findEntity($planet);
                $donate = intval($planet->getAnti()) + $amount;
                $planet->setAnti($donate);
                $planet = $this->planetCommand->updateEntity($planet);
                $view->setVariable('data', array('result' => 'YES', 'auth' => 'YES', 'message' => sprintf('Донат в сумме %s куплен.', $amount)));
            }
            catch(\Exception $e){
                $view->setVariable('data', array('result' => 'ERR', 'auth' => 'YES', 'message' => 'Произошла ошибка!', 'error' => $e->getMessage()));
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
