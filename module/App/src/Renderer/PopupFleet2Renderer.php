<?php

namespace App\Renderer;


use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\AdapterInterface;
use App\Controller\AuthController;
use Universe\Model\GalaxyRepository;
use Universe\Model\PlanetSystemRepository;
use Universe\Model\PlanetRepository;
use Universe\Model\Planet;
use Entities\Model\User;
use Entities\Model\Building;
use Entities\Model\BuildingRepository;
use Entities\Model\BuildingTypeRepository;
use Entities\Model\EventRepository;
use Entities\Model\SpaceSheep;
use Entities\Model\SpaceSheepRepository;


class PopupFleet2Renderer
{
    /**
     * @ AuthController
     */
    private $authController;
    
    /**
     * @ GalaxyRepository
     */
    private $galaxyRepository;
    
    /**
     * @ PlanetSystemRepository
     */
    private $planetSystemRepository;
    
    /**
     * @ PlanetRepository
     */
    private $planetRepository;
    
    /**
     * @ BuildingRepository
     */
    private $buildingRepository;
    
    /**
     * @ BuildingTypeRepository
     */
    private $buildingTypeRepository;
    
    /**
     * @ EventRepository
     */
    private $eventRepository;
    
    /**
     * @ SpaceSheepRepository
     */
    private $spaceSheepRepository;
    
    
    public function __construct(
        AuthController          $authController,
        GalaxyRepository        $galaxyRepository,
        PlanetSystemRepository  $planetSystemRepository,
        PlanetRepository        $planetRepository,
        BuildingRepository      $buildingRepository,
        BuildingTypeRepository  $buildingTypeRepository,
        EventRepository         $eventRepository,
        SpaceSheepRepository    $spaceSheepRepository
        )
    {
        $this->authController           = $authController;
        $this->galaxyRepository         = $galaxyRepository;
        $this->planetSystemRepository   = $planetSystemRepository;
        $this->planetRepository         = $planetRepository;
        $this->buildingRepository       = $buildingRepository;
        $this->buildingTypeRepository   = $buildingTypeRepository;
        $this->eventRepository          = $eventRepository;
        $this->spaceSheepRepository     = $spaceSheepRepository;
    }
    
    public function execute(ViewModel &$template, User $user, Planet $currentPlanet)
    {
        if($this->authController->isAuthorized()){
            $mplanets = $this->planetRepository->findBy('planets.owner = ' . $user->getId() . ' AND planets.id <> ' . $currentPlanet->getId())->buffer();
            $template->setVariable('currentPlanet', $currentPlanet);
            $template->setVariable('mplanets', $mplanets);
        }
    }
    
}
