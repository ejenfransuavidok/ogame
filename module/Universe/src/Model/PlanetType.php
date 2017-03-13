<?php

/**
 * 
 * @ author Jigulin V.V.
 * @ 12.03.2017
 * 
 */

namespace Universe\Model;

class PlanetType extends Entity
{
    
    const TABLE_NAME = 'planets_types';
    
    /**
     * @ int
     */
    private $probability;
    
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
    
    
    public function __construct(
        $name, 
        $description,
        $probability,
        $mineral_metall,
        $mineral_heavygas,
        $mineral_ore,
        $mineral_hydro,
        $mineral_titan,
        $mineral_darkmatter,
        $mineral_redmatter,
        $mineral_anti,
        $id=null)
    {
        parent::__construct(self::TABLE_NAME);
        $this->name                 = $name;
        $this->description          = $description;
        $this->probability          = $probability;
        $this->mineral_metall       = $mineral_metall;
        $this->mineral_heavygas     = $mineral_heavygas;    
        $this->mineral_ore          = $mineral_ore;
        $this->mineral_hydro        = $mineral_hydro;
        $this->mineral_titan        = $mineral_titan;
        $this->mineral_darkmatter   = $mineral_darkmatter;
        $this->mineral_redmatter    = $mineral_redmatter;
        $this->mineral_anti         = $mineral_anti;
        $this->id                   = $id;
    }
    
    public function exchangeArray(array $data, $prefix = '')
    {
        $this->id                   = !empty($data[$prefix.'id']) ? $data[$prefix.'id'] : null;
        $this->name                 = !empty($data[$prefix.'name']) ? $data[$prefix.'name'] : null;
        $this->description          = !empty($data[$prefix.'description']) ? $data[$prefix.'description'] : null;
        $this->probability          = !empty($data[$prefix.'probability']) ? $data[$prefix.'probability'] : null;
        $this->mineral_metall       = !empty($data[$prefix.'mineral_metall']) ? $data[$prefix.'mineral_metall'] : null;
        $this->mineral_heavygas     = !empty($data[$prefix.'mineral_heavygas']) ? $data[$prefix.'mineral_heavygas'] : null;
        $this->mineral_ore          = !empty($data[$prefix.'mineral_ore']) ? $data[$prefix.'mineral_ore'] : null;
        $this->mineral_hydro        = !empty($data[$prefix.'mineral_hydro']) ? $data[$prefix.'mineral_hydro'] : null;
        $this->mineral_titan        = !empty($data[$prefix.'mineral_titan']) ? $data[$prefix.'mineral_titan'] : null;
        $this->mineral_darkmatter   = !empty($data[$prefix.'mineral_darkmatter']) ? $data[$prefix.'mineral_darkmatter'] : null;
        $this->mineral_redmatter    = !empty($data[$prefix.'mineral_redmatter']) ? $data[$prefix.'mineral_redmatter'] : null;
        $this->mineral_anti         = !empty($data[$prefix.'mineral_anti']) ? $data[$prefix.'mineral_anti'] : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    public function setMetall($metall)
    {
        $this->mineral_metall = $metall;
    }
    
    public function getMetall()
    {
        return $this->mineral_metall;
    }
    
    public function setHeavyGas($heavygas)
    {
        $this->mineral_heavygas = $heavygas;
    }
    
    public function getHeavyGas()
    {
        return $this->mineral_heavygas;
    }
    
    public function setOre($ore)
    {
        $this->mineral_ore = $ore;
    }
    
    public function getOre()
    {
        return $this->mineral_ore;
    }
    
    public function setHydro($hydro)
    {
        $this->mineral_hydro = $hydro;
    }
    
    public function getHydro()
    {
        return $this->mineral_hydro;
    }
    
    public function setTitan($titan)
    {
        $this->mineral_titan = $titan;
    }
    
    public function getTitan()
    {
        return $this->mineral_titan;
    }
    
    public function setDarkmatter($darkmatter)
    {
        $this->mineral_darkmatter = $darkmatter;
    }
    
    public function getDarkmatter()
    {
        return $this->mineral_darkmatter;
    }
 
    public function setRedmatter($redmatter)
    {
        $this->mineral_redmatter = $redmatter;
    }
    
    public function getRedmatter()
    {
        return $this->mineral_redmatter;
    }
    
    public function setAnti($anti)
    {
        $this->mineral_anti = $anti;
    }
    
    public function getAnti()
    {
        return $this->mineral_anti;
    }
    
    public function setProbability($probability)
    {
        $this->probability = $probability;
    }
    
    public function getProbability()
    {
        return $this->probability;
    }
    
}
