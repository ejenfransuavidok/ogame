<?php

namespace App\Renderer;

use Zend\View\Model\ViewModel;
use Universe\Model\PlanetRepository;
use Entities\Model\Building;
use Entities\Model\BuildingRepository;
use Entities\Model\BuildingTypeRepository;
use App\Controller\AuthController;


class PopupBuildingRenderer
{
    /**
     * @ BuildingTypeRepository
     */
    private $buildingTypeRepository;
    
    /**
     * @ BuildingRepository
     */
    private $buildingRepository;
    
    /**
     * @ AuthController
     */
    private $authController;
    
    /**
     * @ PlanetRepository
     */
    private $planetRepository;
    
    public function __construct(
        BuildingTypeRepository  $buildingTypeRepository,
        BuildingRepository      $buildingRepository,
        PlanetRepository        $planetRepository,
        AuthController          $authController
        )
    {
        $this->buildingTypeRepository   = $buildingTypeRepository;
        $this->buildingRepository       = $buildingRepository;
        $this->planetRepository         = $planetRepository;
        $this->authController           = $authController;
    }
    
    public function render($planetid)
    {
        $planet = $this->planetRepository->findOneBy('planets.id = ' . $planetid);
        $popup_building = new ViewModel
            ([
                'source_buildings' => $this->buildingTypeRepository->findAllEntities('building_types.type = ' . Building::$BUILDING_RESOURCE)->buffer(),
                'industrial_buildings' => $this->buildingTypeRepository->findAllEntities('building_types.type = ' . Building::$BUILDING_INDUSTRIAL)->buffer()
            ]);
        $popup_building->setTemplate('include/popups/popup_building');
        $popup_building->setVariable('buildingRepository', $this->buildingRepository);
        $popup_building->setVariable('planet', $planet);
        return $popup_building;
    }
    
}
