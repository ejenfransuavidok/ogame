<?php

namespace App\Renderer;

use Zend\View\Model\ViewModel;
use Universe\Model\PlanetRepository;
use Entities\Model\Building;
use Entities\Model\BuildingRepository;
use Entities\Model\BuildingTypeRepository;
use Entities\Classes\ObjectTypesList;
use Entities\Classes\BuildingTypesList;
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
                'tabs'                  => $this->createBuildingsIerarchy(),
                'objects_types'         => (new ObjectTypesList())->data,
                'buildins_types'        => (new BuildingTypesList())->data
            ]);
        $popup_building->setTemplate('include/popups/popup_building');
        $popup_building->setVariable('buildingRepository', $this->buildingRepository);
        $popup_building->setVariable('planet', $planet);
        return $popup_building;
    }
    
    public function createBuildingsIerarchy()
    {
        $tabs = new ObjectTypesList();
        $buildings_types = new BuildingTypesList();
        $tabs = $tabs->data;
        $buildings_types = $buildings_types->data;
        $buildings = $this->buildingTypeRepository->findAllEntities()->buffer();
        foreach($buildings as $building){
            $object_type = $building->getObjectType();
            $building_type = $building->getBuildingType();
            if(! isset($tabs [$object_type] ['sub_tabs']) ){
                $tabs [$object_type] ['sub_tabs'] = array();
            }
            if(! isset($tabs [$object_type] ['sub_tabs'][$building_type])){
                $tabs [$object_type] ['sub_tabs'] [$building_type] = array ();
            }
            $tabs [$object_type] ['sub_tabs'] [$building_type] [] = $building;
        }
        //print_r($tabs);
        return $tabs;
    }
    
}
