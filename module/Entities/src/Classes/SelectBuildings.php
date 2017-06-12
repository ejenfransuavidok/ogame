<?php

namespace Entities\Classes;

use Universe\Model\Planet;
use Entities\Model\BuildingRepository;

class SelectBuildings
{
    /**
     * @ выбор ресурсных зданий (зданий вырабатывающих ресурсы)
     */
    static public function selectResourcesBuildings(Planet $planet, BuildingRepository $buildingRepository)
    {
        $btl = new BuildingTypesList();
        return
        $buildingRepository->findBy('buildings.planet = ' . $planet->getId() . ' AND building_types_alias.building_type IN (' . implode(",", $btl->getResourcesBuildingsList()) . ')')->buffer();
    }
}
