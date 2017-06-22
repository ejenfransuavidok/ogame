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
use Eventer\Processor\Finish4DonateProcessor;
use Settings\Model\SettingsRepositoryInterface;


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
    
    /**
     * @SettingsRepositoryInterface $settingsRepository
     */
    private $settingsRepository;
    
    public function __construct(
        BuildingTypeRepository      $buildingTypeRepository,
        BuildingRepository          $buildingRepository,
        PlanetRepository            $planetRepository,
        AuthController              $authController,
        SettingsRepositoryInterface $settingsRepository
        )
    {
        $this->buildingTypeRepository   = $buildingTypeRepository;
        $this->buildingRepository       = $buildingRepository;
        $this->planetRepository         = $planetRepository;
        $this->authController           = $authController;
        $this->settingsRepository       = $settingsRepository;
    }
    
    public function render($planetid)
    {
        $planet = $this->planetRepository->findOneBy('planets.id = ' . $planetid);
        $popup_building = new ViewModel
            ([
                'tabs'                  => $this->createBuildingsIerarchy($planet),
                'objects_types'         => (new ObjectTypesList())->data,
                'buildins_types'        => (new BuildingTypesList())->data
            ]);
        $popup_building->setTemplate('include/popups/popup_building');
        $popup_building->setVariable('buildingRepository', $this->buildingRepository);
        $popup_building->setVariable('planet', $planet);
        return $popup_building;
    }
    
    public function createBuildingsIerarchy($planet)
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
            $building->temp_current_level = $building->getCurrentLevel($planet, null, $this->buildingRepository) + 1;
            $building->temp_building_period = $building->getBuildingPeriodByLevel($building->temp_current_level);
            $building->temp_total_donate_needle = 
                Finish4DonateProcessor::getTotalDonateNeedle($this->settingsRepository, $building, $building->temp_current_level, 1);
            $tabs [$object_type] ['sub_tabs'] [$building_type] [] = $building;
        }
        //print_r($tabs);
        return $tabs;
    }
    
}
