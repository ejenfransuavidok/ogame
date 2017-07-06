<?php

/**
 * 
 * @ author Jigulin V.V.
 * @ 14.03.2017
 * 
 */

namespace Entities\Model;

use RuntimeException;
use Zend\Math\Rand;
use Universe\Model\Entity;
use Universe\Model\Planet;
use Universe\Classes\PlanetCapacity;


class Source extends Entity
{
    /**
     * @ int
     */
    public $amount;
    
    /**
     * @ int
     */
    public $capacity;
    
    /**
     * @ string
     */
    protected $picture;
    
    public function __construct(
        $name, 
        $description, 
        $amount,
        $picture,
        $price,
        $id=null)
    {
        parent::__construct(null);
        $this->name                 = $name;
        $this->description          = $description;
        $this->amount               = $amount;
        $this->picture              = $picture;
        $this->price                = $price;
        $this->id                   = $id;
    }
    
    public function exchangeArray(array $data, $prefix = '')
    {
        $this->id                   = !empty($data[$prefix.'id'])                   ? $data[$prefix.'id'] : null;
        $this->name                 = !empty($data[$prefix.'name'])                 ? $data[$prefix.'name'] : null;
        $this->description          = !empty($data[$prefix.'description'])          ? $data[$prefix.'description'] : null;
        $this->amount               = !empty($data[$prefix.'amount'])               ? $data[$prefix.'amount'] : null;
        $this->picture              = !empty($data[$prefix.'picture'])              ? $data[$prefix.'picture'] : null;
        $this->price                = !empty($data[$prefix.'price'])                ? $data[$prefix.'price'] : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }
    
    public function getPicture()
    {
        return $this->picture;
    }
    
    public function setPrice($price)
    {
        $this->price = $price;
    }
    
    public function getPrice()
    {
        return $this->price;
    }
    
    public function setAmount(&$planet, $amount)
    {
        switch($this->getId()){
            case 'ELECTRICITY':
                $planet->setElectricity($amount);
                break;
            case 'METALL':
                $planet->setMetall($amount);
                break;
            case 'HEAVYGAS':
                $planet->setHeavyGas($amount);
                break;
            case 'ORE':
                $planet->setOre($amount);
                break;
            case 'HYDRO':
                $planet->setHydro($amount);
                break;
            case 'TITAN':
                $planet->setTitan($amount);
                break;
            case 'DARKMATTER':
                $planet->setDarkmatter($amount);
                break;
            case 'REDMATTER':
                $planet->setRedmatter($amount);
                break;
            case 'ANTI':
                $planet->setAnti($amount);
                break;
        }
    }
    
    public function getAmount(Planet $planet)
    {
        $amount = 0;
        switch($this->getId()){
            case 'ELECTRICITY':
                $amount = $planet->getElectricity();
                break;
            case 'METALL':
                $amount = $planet->getMetall();
                break;
            case 'HEAVYGAS':
                $amount = $planet->getHeavyGas();
                break;
            case 'ORE':
                $amount = $planet->getOre();
                break;
            case 'HYDRO':
                $amount = $planet->getHydro();
                break;
            case 'TITAN':
                $amount = $planet->getTitan();
                break;
            case 'DARKMATTER':
                $amount = $planet->getDarkmatter();
                break;
            case 'REDMATTER':
                $amount = $planet->getRedmatter();
                break;
            case 'ANTI':
                $amount = $planet->getAnti();
                break;
        }
        $this->amount = $amount;
        return $amount;
    }
    
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;
    }
    
    public function getCapacity(Planet $planet, PlanetCapacity $planetCapacity)
    {
        $capacity = 1;
        switch($this->getId()){
            case 'ELECTRICITY':
                $capacity = 1;
                break;
            case 'METALL':
                $capacity = $planetCapacity->getMetallCapacity($planet->getId());
                break;
            case 'HEAVYGAS':
                $capacity = $planetCapacity->getHeavyGasCapacity($planet->getId());
                break;
            case 'ORE':
                $capacity = $planetCapacity->getOreCapacity($planet->getId());
                break;
            case 'HYDRO':
                $capacity = $planetCapacity->getHydroCapacity($planet->getId());
                break;
            case 'TITAN':
                $capacity = $planetCapacity->getTitanCapacity($planet->getId());
                break;
            case 'DARKMATTER':
                $capacity = $planetCapacity->getDarkmatterCapacity($planet->getId());
                break;
            case 'REDMATTER':
                $capacity = $planetCapacity->getRedmatterCapacity($planet->getId());
                break;
            case 'ANTI':
                $capacity = 1;
                break;
        }
        $this->setCapacity($capacity);
        return $capacity;
    }
    
}
