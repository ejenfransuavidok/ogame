<?php

namespace Cron\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\AdapterInterface;
use Universe\Model\PlanetRepository;
use Universe\Model\PlanetCommand;
use Universe\Model\SputnikRepository;
use Universe\Model\SputnikCommand;
use Entities\Model\UserRepository;
use Entities\Model\UserCommand;
use Entities\Model\BuildingRepository;
use Entities\Model\BuildingCommand;
use App\Controller\AuthController;

class UpdaterController extends AbstractActionController
{
    
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
     * @ AuthController
     */
    private $authController;
    
    public function __construct(
        AdapterInterface    $db, 
        PlanetRepository    $planetRepository,
        PlanetCommand       $planetCommand,
        SputnikRepository   $sputnikRepository,
        SputnikCommand      $sputnikCommand,
        UserRepository      $userRepository,
        UserCommand         $userCommand,
        BuildingRepository  $buildingRepository,
        BuildingCommand     $buildingCommand,
        AuthController      $authController
        )
    {
        $this->dbAdapter = $db;
        $this->planetRepository = $planetRepository;
        $this->planetCommand = $planetCommand;
        $this->sputnikRepository = $sputnikRepository;
        $this->sputnikCommand = $sputnikCommand;
        $this->userRepository = $userRepository;
        $this->userCommand = $userCommand;
        $this->buildingRepository = $buildingRepository;
        $this->buildingCommand = $buildingCommand;
        $this->authController = $authController;
    }
    
    /**
     * @ обновление ресурсов текущего юзера, текущей планеты
     */
    public function srcupdaterAction()
    {
        $view = new ViewModel([]);
        $view->setTerminal(true);
        
        $result = array();
        
        if($this->authController->isAuthorized()){
            $user = $this->authController->getUser(); 
            try{
                $planet_id = $this->params()->fromPost('current_planet');
                if(!$planet_id){
                    throw new \Exception('planet id did not set');
                }
                else{
                    /**
                     * @ вернем планету
                     */
                    $planet = $this->planetRepository->findOneBy($planet_id);
                    $result = array();
                    $result['metall']       = $planet->getMetall()     ?   $planet->getMetall()         : 0;
                    $result['heavygas']     = $planet->getHeavygas()   ?   $planet->getHeavygas()       : 0;
                    $result['ore']          = $planet->getOre()        ?   $planet->getOre()            : 0;
                    $result['hydro']        = $planet->getHydro()      ?   $planet->getHydro()          : 0;
                    $result['titan']        = $planet->getTitan()      ?   $planet->getTitan()          : 0;
                    $result['darkmatter']   = $planet->getDarkmatter() ?   $planet->getDarkmatter()     : 0;
                    $result['redmatter']    = $planet->getRedmatter()  ?   $planet->getRedmatter()      : 0;
                    $result['anti']         = $planet->getAnti()       ?   $planet->getAnti()           : 0;
                    $result['electricity']  = $planet->getElectricity()?   $planet->getElectricity()    : 0;
                }
            }
            catch(\Exception $e){
                $view->setVariable('data', array('result' => 'ERR', 'auth' => 'YES', 'message' => $e->getMessage()));
                return $view;
            }
        }
        else{
            $view->setVariable('data', array('result' => 'ERR', 'auth' => 'NO', 'message' => 'Пользователь не авторизован'));
            return $view;
        }
        $view->setVariable('data', array('result' => 'YES', 'auth' => 'YES', 'values' => $result));
        return $view;
    }
    
}
