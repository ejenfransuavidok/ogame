<?php

namespace Eventer\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\AdapterInterface;
use App\Controller\AuthController;
use Entities\Model\UserRepository;
use Entities\Model\UserCommand;
use Entities\Model\SourceRepository;
use Entities\Model\Source;
use Universe\Model\PlanetRepository;
use Universe\Model\PlanetCommand;
use Universe\Model\SputnikRepository;
use Universe\Model\SputnikCommand;
use Universe\Classes\PlanetCapacity;
use Settings\Model\SettingsRepositoryInterface;


class AmountIsAboveCapacity extends \Exception {};
class AmountIsAboveNeedlAmount extends \Exception {};
class HaveNoEnoughDonate extends \Exception {};

class BuysourcesController extends AbstractActionController
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
    
    /**
     * @SourceRepository
     */
    protected $sourceRepository;
    
    /**
     * @ PlanetCapacity
     */
    protected $planetCapacity;
    

    public function __construct(
        AdapterInterface            $db,
        AuthController              $authController,
        UserRepository              $userRepository,
        UserCommand                 $userCommand,
        PlanetRepository            $planetRepository,
        PlanetCommand               $planetCommand,
        SputnikRepository           $sputnikRepository,
        SputnikCommand              $sputnikCommand,
        SettingsRepositoryInterface $settingsRepository,
        SourceRepository            $sourceRepository,
        PlanetCapacity              $planetCapacity
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
        $this->sourceRepository         = $sourceRepository;
        $this->planetCapacity           = $planetCapacity;
    }
    
    public function buysourcesAction()
    {
        $view = new ViewModel([]);
        $view->setTerminal(true);
        if($this->authController->isAuthorized()){
            try{
                $this->user = $this->authController->getUser();
                $planet = $this->params()->fromPost('planet');
                $source_fullness_percents_up_to = floatval($this->params()->fromPost('source_fullness_percents_up_to'));
                $source_id = $this->params()->fromPost('source_id');
                
                $planet = $this->planetRepository->findEntity($planet);
                $source = $this->sourceRepository->findEntity(strtoupper($source_id));
                $capacity = floatval($source->getCapacity($planet, $this->planetCapacity));
                $amount = floatval($source->getAmount($planet));
                $price = $source->getPrice();
                
                if($amount > $capacity){
                    throw new AmountIsAboveCapacity(sprintf('Amount of %s is %d, but capacity is %d!!!'), $source_id, $amount, $capacity);
                }
                $amount_needle = $capacity * $source_fullness_percents_up_to / 100;
                if($amount > $amount_needle){
                    throw new AmountIsAboveNeedlAmount(sprintf('Amount of %s is %d, but needle only is %d!!!'), $source_id, $amount, $amount_needle);
                }
                $amount_delta = $amount_needle - $amount;
                $donate_needle = floatval($amount_delta) * floatval($source->getPrice());
                $donate_have = $planet->getAnti();
                if($donate_needle > $donate_have){
                    throw new HaveNoEnoughDonate(sprintf('You have %d of donate, but you need %d!!!', $donate_have, $donate_needle));
                }
                $amount_total = $amount_needle;
                $source->setAmount($planet, $amount_total);
                $donate_total = $donate_have - $donate_needle;
                $planet->setAnti($donate_total);
                $planet = $this->planetCommand->updateEntity($planet);
                
                $view->setVariable('data', array('result' => 'YES', 'auth' => 'YES', 'message' => sprintf('Донат в сумме %s куплен.', $source_fullness_percents_up_to)));
            }
            catch(\Exception $e){
                $view->setVariable('data', array('result' => 'ERR', 'auth' => 'YES', 'message' => 'Произошла ошибка! ' . $e->getMessage(), 'error' => $e->getMessage()));
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
