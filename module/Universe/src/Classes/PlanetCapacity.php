<?php

namespace Universe\Classes;

use Entities\Model\BuildingRepository;
use Settings\Model\Setting;
use Settings\Model\SettingsRepositoryInterface;

class PlanetCapacity
{
    const DEFAULT_AMOUNT = 10000;
    
    /**
     * @ BuildingRepository
     */
    private $buildingRepository;
    
    /**
     * @ SettingsRepositoryInterface
     */
    private $settingsRepository;
    
    public function __construct
        (
        SettingsRepositoryInterface $settingsRepository,
        BuildingRepository $buildingRepository
        )
    {
        $this->settingsRepository = $settingsRepository;
        $this->buildingRepository = $buildingRepository;
    }
    
    public function getMetallCapacity($planet_id)
    {
        $PLANET_METALL_CAPACITY = intval(
            $this->settingsRepository->findSettingByKey ('PLANET_METALL_CAPACITY')->getText() ?
            $this->settingsRepository->findSettingByKey ('PLANET_METALL_CAPACITY')->getText() : self::DEFAULT_AMOUNT
                                        );
        $total_capacity = $PLANET_METALL_CAPACITY;
        if($buildings = $this->buildingRepository->findAllEntities('buildings.planet = ' . $planet_id)->buffer()){
            foreach($buildings as $building){
                $total_capacity += $building->getCapacityMetall();
            }
        }
        return $total_capacity;
    }
    
    public function getHeavyGasCapacity($planet_id)
    {
        $PLANET_HEAVYGAS_CAPACITY = intval(
            $this->settingsRepository->findSettingByKey ('PLANET_HEAVYGAS_CAPACITY')->getText() ?
            $this->settingsRepository->findSettingByKey ('PLANET_HEAVYGAS_CAPACITY')->getText() : self::DEFAULT_AMOUNT
                                        );
        $total_capacity = $PLANET_HEAVYGAS_CAPACITY;
        if($buildings = $this->buildingRepository->findAllEntities('buildings.planet = ' . $planet_id)->buffer()){
            foreach($buildings as $building){
                $total_capacity += $building->getCapacityHeavygas();
            }
        }
        return $total_capacity;
    }
    
    public function getOreCapacity($planet_id)
    {
        $PLANET_ORE_CAPACITY = intval(
            $this->settingsRepository->findSettingByKey ('PLANET_ORE_CAPACITY')->getText() ?
            $this->settingsRepository->findSettingByKey ('PLANET_ORE_CAPACITY')->getText() : self::DEFAULT_AMOUNT
                                        );
        $total_capacity = $PLANET_ORE_CAPACITY ;
        if($buildings = $this->buildingRepository->findAllEntities('buildings.planet = ' . $planet_id)->buffer()){
            foreach($buildings as $building){
                $total_capacity += $building->getCapacityOre();
            }
        }
        return $total_capacity;
    }
    
    public function getHydroCapacity($planet_id)
    {
        $PLANET_HYDRO_CAPACITY = intval(
            $this->settingsRepository->findSettingByKey ('PLANET_HYDRO_CAPACITY')->getText() ?
            $this->settingsRepository->findSettingByKey ('PLANET_HYDRO_CAPACITY')->getText() : self::DEFAULT_AMOUNT
                                        );
        $total_capacity = $PLANET_HYDRO_CAPACITY;
        if($buildings = $this->buildingRepository->findAllEntities('buildings.planet = ' . $planet_id)->buffer()){
            foreach($buildings as $building){
                $total_capacity += $building->getCapacityHydro();
            }
        }
        return $total_capacity;
    }
    
    public function getTitanCapacity($planet_id)
    {
        $PLANET_TITAN_CAPACITY = intval(
            $this->settingsRepository->findSettingByKey ('PLANET_TITAN_CAPACITY')->getText() ?
            $this->settingsRepository->findSettingByKey ('PLANET_TITAN_CAPACITY')->getText() : self::DEFAULT_AMOUNT
                                        );
        $total_capacity = $PLANET_TITAN_CAPACITY;
        if($buildings = $this->buildingRepository->findAllEntities('buildings.planet = ' . $planet_id)->buffer()){
            foreach($buildings as $building){
                $total_capacity += $building->getCapacityTitan();
            }
        }
        return $total_capacity;
    }
    
    public function getDarkmatterCapacity($planet_id)
    {
        $PLANET_DARKMATTER_CAPACITY = intval(
            $this->settingsRepository->findSettingByKey ('PLANET_DARKMATTER_CAPACITY')->getText() ?
            $this->settingsRepository->findSettingByKey ('PLANET_DARKMATTER_CAPACITY')->getText() : self::DEFAULT_AMOUNT
                                        );
        $total_capacity = $PLANET_DARKMATTER_CAPACITY;
        if($buildings = $this->buildingRepository->findAllEntities('buildings.planet = ' . $planet_id)->buffer()){
            foreach($buildings as $building){
                $total_capacity += $building->getCapacityDarkmatter();
            }
        }
        return $total_capacity;
    }
    
    public function getRedmatterCapacity($planet_id)
    {
        $PLANET_REDMATTER_CAPACITY = intval(
            $this->settingsRepository->findSettingByKey ('PLANET_REDMATTER_CAPACITY')->getText() ?
            $this->settingsRepository->findSettingByKey ('PLANET_REDMATTER_CAPACITY')->getText() : self::DEFAULT_AMOUNT
                                        );
        $total_capacity = $PLANET_REDMATTER_CAPACITY;
        if($buildings = $this->buildingRepository->findAllEntities('buildings.planet = ' . $planet_id)->buffer()){
            foreach($buildings as $building){
                $total_capacity += $building->getCapacityRedmatter();
            }
        }
        return $total_capacity;
    }
    
}
