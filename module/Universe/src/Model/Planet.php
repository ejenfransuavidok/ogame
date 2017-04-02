<?php

/**
 * 
 * @ author Jigulin V.V.
 * @ 07.02.2017
 * 
 */

namespace Universe\Model;

use Universe\Classes\Prettier;

class Planet extends CelestialBody
{
    const TABLE_NAME = 'planets';
    
    /**
     * 
     * position of planet into planets system 1...N
     * @int
     * 
     */
    private $position;
    
    /**
     * 
     * @пригодность для жизни
     * 
     */
    private $livable;
    
    /**
     * @ metall
     */
    private $mineral_metall;
    
    /**
     * @ heavygas
     */
    private $mineral_heavygas;
    
    /**
     * @ ore
     */
    private $mineral_ore;
    
    /**
     * @ hydro
     */
    private $mineral_hydro;
    
    /**
     * @ titan
     */
    private $mineral_titan;
    
    /**
     * @ darkmatter
     */
    private $mineral_darkmatter;
    
    /**
     * @ redmatter
     */
    private $mineral_redmatter;
    
    /**
     * @ anti
     */
    private $mineral_anti;
    
    /**
     * @ electricity
     */
    private $electricity;
    
    /**
     * @ PlanetType
     */
    private $type;
    
    /**
     * @ User
     */
    private $owner;
    
    public function __construct(
        $name, 
        $description,
        $type,
        $coordinate, 
        $size, 
        $position,
        $livable, 
        $planet_system, 
        $mineral_metall,
        $mineral_heavygas,
        $mineral_ore,
        $mineral_hydro,
        $mineral_titan,
        $mineral_darkmatter,
        $mineral_redmatter,
        $mineral_anti,
        $electricity,
        $owner,
        $id=null)
    {
        parent::__construct(self::TABLE_NAME);
        $this->name                 = $name;
        $this->description          = $description;
        $this->type                 = $type;
        $this->coordinate           = $coordinate;
        $this->size                 = $size;
        $this->position             = $position;
        $this->livable              = $livable;
        $this->celestialParent      = $planet_system;
        $this->mineral_metall       = $mineral_metall;
        $this->mineral_heavygas     = $mineral_heavygas;    
        $this->mineral_ore          = $mineral_ore;
        $this->mineral_hydro        = $mineral_hydro;
        $this->mineral_titan        = $mineral_titan;
        $this->mineral_darkmatter   = $mineral_darkmatter;
        $this->mineral_redmatter    = $mineral_redmatter;
        $this->mineral_anti         = $mineral_anti;
        $this->electricity          = $electricity;
        $this->owner                = $owner;
        $this->id                   = $id;
    }
    
    public function exchangeArray(array $data, $prefix = '')
    {
        $this->id                   = !empty($data[$prefix.'id']) ? $data[$prefix.'id'] : null;
        $this->name                 = !empty($data[$prefix.'name']) ? $data[$prefix.'name'] : null;
        $this->type                 = !empty($data[$prefix.'type']) ? $data[$prefix.'type'] : null;
        $this->description          = !empty($data[$prefix.'description']) ? $data[$prefix.'description'] : null;
        $this->coordinate           = !empty($data[$prefix.'coordinate']) ? $data[$prefix.'coordinate'] : null;
        $this->size                 = !empty($data[$prefix.'size']) ? $data[$prefix.'size'] : null;
        $this->position             = !empty($data[$prefix.'position']) ? $data[$prefix.'position'] : null;
        $this->livable              = !empty($data[$prefix.'livable']) ? $data[$prefix.'livable'] : null;
        $this->celestialParent      = !empty($data[$prefix.'planet_system']) ? $data[$prefix.'planet_system'] : null;
        $this->mineral_metall       = !empty($data[$prefix.'mineral_metall']) ? $data[$prefix.'mineral_metall'] : null;
        $this->mineral_heavygas     = !empty($data[$prefix.'mineral_heavygas']) ? $data[$prefix.'mineral_heavygas'] : null;
        $this->mineral_ore          = !empty($data[$prefix.'mineral_ore']) ? $data[$prefix.'mineral_ore'] : null;
        $this->mineral_hydro        = !empty($data[$prefix.'mineral_hydro']) ? $data[$prefix.'mineral_hydro'] : null;
        $this->mineral_titan        = !empty($data[$prefix.'mineral_titan']) ? $data[$prefix.'mineral_titan'] : null;
        $this->mineral_darkmatter   = !empty($data[$prefix.'mineral_darkmatter']) ? $data[$prefix.'mineral_darkmatter'] : null;
        $this->mineral_redmatter    = !empty($data[$prefix.'mineral_redmatter']) ? $data[$prefix.'mineral_redmatter'] : null;
        $this->mineral_anti         = !empty($data[$prefix.'mineral_anti']) ? $data[$prefix.'mineral_anti'] : null;
        $this->electricity          = !empty($data[$prefix.'electricity']) ? $data[$prefix.'electricity'] : null;
        $this->owner                = !empty($data[$prefix.'owner']) ? $data[$prefix.'owner'] : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    /**
     * 
     * @param int
     * 
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }
    
    /**
     * 
     * @return int
     * 
     */
    public function getPosition()
    {
        return $this->position;
    }
    
    /**
     * 
     * @param int
     * 
     */
    public function setLivable($livable)
    {
        $this->livable = $livable;
    }
    
    /**
     * 
     * @return int
     * 
     */
    public function getLivable()
    {
        return $this->livable;
    }
    
    public function setMetall($metall)
    {
        $this->mineral_metall = $metall;
    }
    
    public function getMetall()
    {
        return $this->mineral_metall != null ? $this->mineral_metall : 0;
    }
    
    public function setHeavyGas($heavygas)
    {
        $this->mineral_heavygas = $heavygas;
    }
    
    public function getHeavyGas()
    {
        return $this->mineral_heavygas != null ? $this->mineral_heavygas : 0;
    }
    
    public function setOre($ore)
    {
        $this->mineral_ore = $ore;
    }
    
    public function getOre()
    {
        return $this->mineral_ore != null ? $this->mineral_ore : 0;
    }
    
    public function setHydro($hydro)
    {
        $this->mineral_hydro = $hydro;
    }
    
    public function getHydro()
    {
        return $this->mineral_hydro != null ? $this->mineral_hydro : 0;
    }
    
    public function setTitan($titan)
    {
        $this->mineral_titan = $titan;
    }
    
    public function getTitan()
    {
        return $this->mineral_titan != null ? $this->mineral_titan : 0;
    }
    
    public function setDarkmatter($darkmatter)
    {
        $this->mineral_darkmatter = $darkmatter;
    }
    
    public function getDarkmatter()
    {
        return $this->mineral_darkmatter != null ? $this->mineral_darkmatter : 0;
    }
 
    public function setRedmatter($redmatter)
    {
        $this->mineral_redmatter = $redmatter;
    }
    
    public function getRedmatter()
    {
        return $this->mineral_redmatter != null ? $this->mineral_redmatter : 0;
    }
    
    public function setAnti($anti)
    {
        $this->mineral_anti = $anti;
    }
    
    public function getAnti()
    {
        return $this->mineral_anti != null ? $this->mineral_anti : 0;
    }
    
    public function setElectricity($electricity)
    {
        $this->electricity = $electricity;
    }
    
    public function getElectricity()
    {
        return $this->electricity != null ? $this->electricity : 0;
    }
    
    public function setType($type)
    {
        $this->type = $type;
    }
    
    public function getType()
    {
        return $this->type;
    }
    
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }
    
    public function getOwner()
    {
        return $this->owner;
    }
    
    /**
     * @ pretty
     */
    public function getMetallPretty()
    {
        return Prettier::doIt($this->mineral_metall != null ? $this->mineral_metall : 0);
    }
    
    public function getHeavyGasPretty()
    {
        return Prettier::doIt($this->mineral_heavygas != null ? $this->mineral_heavygas : 0);
    }
    
    public function getOrePretty()
    {
        return Prettier::doIt($this->mineral_ore != null ? $this->mineral_ore : 0);
    }
    
    public function getHydroPretty()
    {
        return Prettier::doIt($this->mineral_hydro != null ? $this->mineral_hydro : 0);
    }
    
    public function getTitanPretty()
    {
        return Prettier::doIt($this->mineral_titan != null ? $this->mineral_titan : 0);
    }
    
    public function getDarkmatterPretty()
    {
        return Prettier::doIt($this->mineral_darkmatter != null ? $this->mineral_darkmatter : 0);
    }
    
    public function getRedmatterPretty()
    {
        return Prettier::doIt($this->mineral_redmatter != null ? $this->mineral_redmatter : 0);
    }
    
    public function getAntiPretty()
    {
        return Prettier::doIt($this->mineral_anti != null ? $this->mineral_anti : 0);
    }
    
    public function getElectricityPretty()
    {
        return Prettier::doIt($this->electricity != null ? $this->electricity : 0);
    }
}
