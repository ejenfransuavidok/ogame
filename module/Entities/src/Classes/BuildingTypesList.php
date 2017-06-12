<?php

namespace Entities\Classes;

class BuildingTypesList extends CustomTypesList
{
    
    public $BTYPE_RESOURCE = 1;
    public $BTYPE_ECONOMIC = 2;
    public $BTYPE_SHIPYARD = 3;
    public $BTYPE_LABORATORY = 4;
    public $BTYPE_SCHOOL = 5;
    public $BTYPE_ORE = 6;
    public $BTYPE_POWERSUPPLY = 7;
    public $BTYPE_REPOSITORY = 8;
        
    public function __construct()
    {
        $this->types ['Ресурсное здание']   = $this->BTYPE_RESOURCE;
        $this->types ['Экономика']          = $this->BTYPE_ECONOMIC;
        $this->types ['Верфи']              = $this->BTYPE_SHIPYARD;
        $this->types ['Лаборатории']        = $this->BTYPE_LABORATORY;
        $this->types ['Училища']            = $this->BTYPE_SCHOOL;
        $this->types ['Шахты']              = $this->BTYPE_ORE;
        $this->types ['Электростанции']     = $this->BTYPE_POWERSUPPLY;
        $this->types ['Хранилища']          = $this->BTYPE_REPOSITORY;
        
        $this->data[$this->BTYPE_RESOURCE]      = array('name' => 'Resources',      'building_tab' => 'tab_resources');
        $this->data[$this->BTYPE_ECONOMIC]      = array('name' => 'Economics',      'building_tab' => 'tab_economics');
        $this->data[$this->BTYPE_SHIPYARD]      = array('name' => 'Shipyards',      'building_tab' => 'tab_shipyards');
        $this->data[$this->BTYPE_LABORATORY]    = array('name' => 'Laboratories',   'building_tab' => 'tab_industrials');
        $this->data[$this->BTYPE_SCHOOL]        = array('name' => 'Shools',         'building_tab' => 'tab_shcools');
        $this->data[$this->BTYPE_ORE]           = array('name' => 'Ores',           'building_tab' => 'tab_ores');
        $this->data[$this->BTYPE_POWERSUPPLY]   = array('name' => 'Powersupply',    'building_tab' => 'tab_powersupply');
        $this->data[$this->BTYPE_REPOSITORY]    = array('name' => 'Repositories',   'building_tab' => 'tab_repository');
    }
    
    public function getResourcesBuildingsList()
    {
        /**
         * эти здания относятся к ресурсным сейчас...
         */
        return array($this->BTYPE_RESOURCE, $this->BTYPE_ORE, $this->BTYPE_POWERSUPPLY);
    }
}
