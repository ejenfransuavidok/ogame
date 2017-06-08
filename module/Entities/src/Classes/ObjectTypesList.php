<?php

namespace Entities\Classes;

class ObjectTypesList extends CustomTypesList
{
    
    public $OTYPE_RESOURCES = 1;
    public $OTYPE_PLANTS = 2;
    public $OTYPE_FLEET = 3;
    public $OTYPE_DEFENCE = 4;
    public $OTYPE_SCIENCE = 5;
    
    public function __construct()
    {
        $this->types ['Флот']               = $this->OTYPE_FLEET;
        $this->types ['Защита']             = $this->OTYPE_DEFENCE;
        $this->types ['Ресурсы']            = $this->OTYPE_RESOURCES;
        $this->types ['Заводы']             = $this->OTYPE_PLANTS;
        $this->types ['Наука']              = $this->OTYPE_SCIENCE;
    
        $this->data[$this->OTYPE_FLEET]     = array('name' => 'Fleet',      'image' => '/img/ico/simp-ico-fleet.svg',       'building_tab' => 'building_fleets');
        $this->data[$this->OTYPE_DEFENCE]   = array('name' => 'Defense',    'image' => '/img/ico/simp-ico-defense.svg',     'building_tab' => 'building_defences');
        $this->data[$this->OTYPE_RESOURCES] = array('name' => 'Resources',  'image' => '/img/ico/simp-ico-resources.svg',   'building_tab' => 'building_resources');
        $this->data[$this->OTYPE_PLANTS]    = array('name' => 'Plants',     'image' => '/img/ico/simp-ico-factories.svg',   'building_tab' => 'building_industrials');
        $this->data[$this->OTYPE_SCIENCE]   = array('name' => 'Science',    'image' => '/img/ico/simp-ico-technology.svg',  'building_tab' => 'building_sciences');
    }
    
}
