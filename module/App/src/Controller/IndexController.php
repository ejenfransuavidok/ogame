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
use Universe\Model\PlanetRepository;

class IndexController extends AbstractActionController
{
    /**
     * @var AdapterInterface
     */
    private $dbAdapter;
    
    /**
     * @ AuthController
     */
    private $authController;
    
    /**
     * @ PlanetRepository
     */
    private $planetRepository;
    
    public function __construct(
        AdapterInterface $db, 
        AuthController $authController,
        PlanetRepository $planetRepository
        )
    {
        $this->dbAdapter = $db;
        $this->authController = $authController;
        $this->planetRepository = $planetRepository;
    }
    
    public function indexAction()
    {
        if($this->authController->isAuthorized()){
            
            $planetid = $this->params()->fromRoute('planetid');
            
            $planet = $this->planetRepository->findOneBy('planets.id = ' . $planetid);
            
            $this->user = $this->authController->getUser();
            
            $layout = $this->layout();
            
            $layout->setTemplate('app/layout');
            
            $header = new ViewModel([
                'planets' => $this->planetRepository->findBy('planets.owner = ' . $this->user->getId() . ' AND planets.id != ' . $planet->getId())->buffer(),
                'planet'  => $planet
            ]);
            $header->setTemplate('include/header');
            
            $game = new ViewModel(['planet' => $planet]);
            $game->setTemplate('include/game');
            
            $view = new ViewModel([]);
            $view
                ->addChild($header, 'header')
                ->addChild($game, 'game');
            
            return $view;
        }
        else{
            return $this->redirect()->toRoute('app/auth', ['action' => 'auth']);
        }
    }
    
    public function getMyPlanets()
    {
        
    }
    
}
