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
use Entities\Model\BuildingTypeRepository;


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
    
    /**
     * @ BuildingTypeRepository
     */
    private $buildingTypeRepository;
    
    
    
    public function __construct(
        AdapterInterface $db,
        AuthController $authController,
        PlanetRepository $planetRepository,
        BuildingTypeRepository $buildingTypeRepository
        )
    {
        $this->dbAdapter = $db;
        $this->authController = $authController;
        $this->planetRepository = $planetRepository;
        $this->buildingTypeRepository = $buildingTypeRepository;
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
                'planet'  => $planet,
                'user'    => $this->user
            ]);
            $header->setTemplate('include/header');
            /**
             * 
             */
            $producefleet = new ViewModel([]);
            $producefleet->setTemplate('include/producefleet');
            $producedefence = new ViewModel([]);
            $producedefence->setTemplate('include/producedefence');
            $producesrc = new ViewModel([]);
            $producesrc->setTemplate('include/producesrc');
            $produceindustrial = new ViewModel([]);
            $produceindustrial->setTemplate('include/produceindustrial');
            $producetech = new ViewModel([]);
            $producetech->setTemplate('include/producetech');
            $planetkeep = new ViewModel([]);
            $planetkeep->setTemplate('include/planetkeep');
            $planetkeep
                ->addChild($producefleet, 'producefleet')
                ->addChild($producedefence, 'producedefence')
                ->addChild($producesrc, 'producesrc')
                ->addChild($produceindustrial, 'produceindustrial')
                ->addChild($producetech, 'producetech');
            /**
             * 
             */
            $popup_building = new ViewModel(['source_buildings' => $this->buildingTypeRepository->findAllEntities()->buffer()]);
            $popup_building->setTemplate('include/popups/popup_building');
            
            $game = new ViewModel(['planet' => $planet]);
            $game->setTemplate('include/game');
            $game
                ->addChild($planetkeep, 'planetkeep')
                ->addChild($popup_building, 'popup_building');
            
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
    
}
