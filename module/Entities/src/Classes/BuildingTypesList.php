<?php

namespace Entities\Classes;

class BuildingTypesList extends CustomTypesList
{
    
    public $BTYPE_RESOURCE = 1;
    public $BTYPE_ECONOMIC = 2;
    public $BTYPE_SHIPYARD = 3;
    public $BTYPE_LABORATORY = 4;
    public $BTYPE_SCHOOL = 5;
        
    public function __construct()
    {
        $this->types ['Ресурсное здание']   = $this->BTYPE_RESOURCE;
        $this->types ['Экономика']          = $this->BTYPE_ECONOMIC;
        $this->types ['Верфи']              = $this->BTYPE_SHIPYARD;
        $this->types ['Лаборатории']        = $this->BTYPE_LABORATORY;
        $this->types ['Училища']            = $this->BTYPE_SCHOOL;
    }
    
}
