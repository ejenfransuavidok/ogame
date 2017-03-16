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

class Building extends Entity
{
    const TABLE_NAME = 'buildings';
    
    public static $BUILDING_RESOURCE = 1;
    
    public static $DELTA_REFRESH = 10;
    
    /**
     * @ Planet
     */
    protected $planet;
    
    /**
     * @ Sputnik
     */
    protected $sputnik;
    
    /**
     * @ User
     */
    protected $owner;
    
    /**
     * @ int
     */
    protected $level;
    
    /**
     * @ float
     */
    protected $factor;
    
    /**
     * @ int
     */
    protected $produce_metall;
    
    /**
     * @ int
     */
    protected $produce_heavygas;
    
    /**
     * @ int
     */
    protected $produce_ore;
    
    /**
     * @ int
     */
    protected $produce_hydro;
    
    /**
     * @ int
     */
    protected $produce_titan;
    
    /**
     * @ int
     */
    protected $produce_darkmatter;
    
    /**
     * @ int
     */
    protected $produce_redmatter;
    
    /**
     * @ int
     */
    protected $produce_anti;
    
    /**
     * @ int
     */
    protected $produce_electricity;
    
    /**
     * @ int
     */
    protected $consume_metall;
    
    /**
     * @ int
     */
    protected $consume_heavygas;
    
    /**
     * @ int
     */
    protected $consume_ore;
    
    /**
     * @ int
     */
    protected $consume_hydro;
    
    /**
     * @ int
     */
    protected $consume_titan;
    
    /**
     * @ int
     */
    protected $consume_darkmatter;
    
    /**
     * @ int
     */
    protected $consume_redmatter;
    
    /**
     * @ int
     */
    protected $consume_anti;
    
    /**
     * @ int
     */
    protected $consume_electricity;
    
    /**
     * @ int
     */
    protected $type;
    
    /**
     * @ int
     */
    protected $update;
    
    
    public function __construct(
        $name, 
        $description, 
        $planet,
        $sputnik,
        $owner,
        $level,
        $type,
        $update,
        $factor,
        $produce_metall,
        $produce_heavygas,
        $produce_ore,
        $produce_hydro,
        $produce_titan,
        $produce_darkmatter,
        $produce_redmatter,
        $produce_anti,
        $produce_electricity,
        $consume_metall,
        $consume_heavygas,
        $consume_ore,
        $consume_hydro,
        $consume_titan,
        $consume_darkmatter,
        $consume_redmatter,
        $consume_anti,
        $consume_electricity,
        $id=null)
    {
        parent::__construct(self::TABLE_NAME);
        $this->name                 = $name;
        $this->description          = $description;
        $this->planet               = $planet;
        $this->sputnik              = $sputnik;
        $this->owner                = $owner;
        $this->level                = $level;
        $this->type                 = $type;
        $this->update               = $update;
        $this->factor               = $factor;
        $this->produce_metall       = $produce_metall;
        $this->produce_heavygas     = $produce_heavygas;
        $this->produce_ore          = $produce_ore;
        $this->produce_hydro        = $produce_hydro;
        $this->produce_titan        = $produce_titan;
        $this->produce_darkmatter   = $produce_darkmatter;
        $this->produce_redmatter    = $produce_redmatter;
        $this->produce_anti         = $produce_anti;
        $this->produce_electricity  = $produce_electricity;
        $this->consume_metall       = $consume_metall;
        $this->consume_heavygas     = $consume_heavygas;
        $this->consume_ore          = $consume_ore;
        $this->consume_hydro        = $consume_hydro;
        $this->consume_titan        = $consume_titan;
        $this->consume_darkmatter   = $consume_darkmatter;
        $this->consume_redmatter    = $consume_redmatter;
        $this->consume_anti         = $consume_anti;
        $this->consume_electricity  = $consume_electricity;
        $this->id                   = $id;
    }
    
    public function exchangeArray(array $data, $prefix = '')
    {
        $this->id                   = !empty($data[$prefix.'id']) ? $data[$prefix.'id'] : null;
        $this->name                 = !empty($data[$prefix.'name']) ? $data[$prefix.'name'] : null;
        $this->description          = !empty($data[$prefix.'description']) ? $data[$prefix.'description'] : null;
        $this->planet               = !empty($data[$prefix.'planet']) ? $data[$prefix.'planet'] : null;
        $this->sputnik              = !empty($data[$prefix.'sputnik']) ? $data[$prefix.'sputnik'] : null;
        $this->owner                = !empty($data[$prefix.'owner']) ? $data[$prefix.'owner'] : null;
        $this->level                = !empty($data[$prefix.'level']) ? $data[$prefix.'level'] : null;
        $this->type                 = !empty($data[$prefix.'type']) ? $data[$prefix.'type'] : null;
        $this->update               = !empty($data[$prefix.'update']) ? $data[$prefix.'update'] : null;
        $this->factor               = !empty($data[$prefix.'factor']) ? $data[$prefix.'factor'] : null;
        $this->produce_metall       = !empty($data[$prefix.'produce_metall']) ? $data[$prefix.'produce_metall'] : null;
        $this->produce_heavygas     = !empty($data[$prefix.'produce_heavygas']) ? $data[$prefix.'produce_heavygas'] : null;
        $this->produce_ore          = !empty($data[$prefix.'produce_ore']) ? $data[$prefix.'produce_ore'] : null;
        $this->produce_hydro        = !empty($data[$prefix.'produce_hydro']) ? $data[$prefix.'produce_hydro'] : null;
        $this->produce_titan        = !empty($data[$prefix.'produce_titan']) ? $data[$prefix.'produce_titan'] : null;
        $this->produce_darkmatter   = !empty($data[$prefix.'produce_darkmatter']) ? $data[$prefix.'produce_darkmatter'] : null;
        $this->produce_redmatter    = !empty($data[$prefix.'produce_redmatter']) ? $data[$prefix.'produce_redmatter'] : null;
        $this->produce_anti         = !empty($data[$prefix.'produce_anti']) ? $data[$prefix.'produce_anti'] : null;
        $this->produce_electricity  = !empty($data[$prefix.'produce_electricity']) ? $data[$prefix.'produce_electricity'] : null;
        $this->consume_metall       = !empty($data[$prefix.'consume_metall']) ? $data[$prefix.'consume_metall'] : null;
        $this->consume_heavygas     = !empty($data[$prefix.'consume_heavygas']) ? $data[$prefix.'consume_heavygas'] : null;
        $this->consume_ore          = !empty($data[$prefix.'consume_ore']) ? $data[$prefix.'consume_ore'] : null;
        $this->consume_hydro        = !empty($data[$prefix.'consume_hydro']) ? $data[$prefix.'consume_hydro'] : null;
        $this->consume_titan        = !empty($data[$prefix.'consume_titan']) ? $data[$prefix.'consume_titan'] : null;
        $this->consume_darkmatter   = !empty($data[$prefix.'consume_darkmatter']) ? $data[$prefix.'consume_darkmatter'] : null;
        $this->consume_redmatter    = !empty($data[$prefix.'consume_redmatter']) ? $data[$prefix.'consume_redmatter'] : null;
        $this->consume_anti         = !empty($data[$prefix.'consume_anti']) ? $data[$prefix.'consume_anti'] : null;
        $this->consume_electricity  = !empty($data[$prefix.'consume_electricity']) ? $data[$prefix.'consume_electricity'] : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setPlanet($planet)
    {
        $this->planet = $planet;
    }
    
    public function getPlanet()
    {
        return $this->planet;
    }
    
    public function setSputnik($sputnik)
    {
        $this->sputnik = $sputnik;
    }
    
    public function getSputnik()
    {
        return $this->sputnik;
    }
    
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }
    
    public function getOwner()
    {
        return $this->owner;
    }
    
    public function setLevel($level)
    {
        $this->level = $level;
    }
    
    public function getLevel()
    {
        return $this->level;
    }
    
    public function setType($type)
    {
        $this->type = $type;
    }
    
    public function getType()
    {
        return $this->type;
    }
    
    public function setUpdate($update)
    {
        $this->update = $update;
    }
    
    public function getUpdate()
    {
        return $this->update;
    }
    
    public function setFactor($factor)
    {
        $this->factor = $factor;
    }
    
    public function getFactor()
    {
        return $this->factor;
    }
    
    public function setProduceMetall($produce_metall)
    {
        $this->produce_metall = $produce_metall;
    }
    
    public function getProduceMetall()
    {
        return $this->produce_metall;
    }
    
    public function setProduceHeavygas($produce_heavygas)
    {
        $this->produce_heavygas = $produce_heavygas;
    }
    
    public function getProduceHeavygas()
    {
        return $this->produce_heavygas;
    }
    
    public function setProduceOre($produce_ore)
    {
        $this->produce_ore = $produce_ore;
    }
    
    public function getProduceOre()
    {
        return $this->produce_ore;
    }
    
    public function setProduceHydro($produce_hydro)
    {
        $this->produce_hydro = $produce_hydro;
    }
    
    public function getProduceHydro()
    {
        return $this->produce_hydro;
    }
    
    public function setProduceTitan($produce_titan)
    {
        $this->produce_titan = $produce_titan;
    }
    
    public function getProduceTitan()
    {
        return $this->produce_titan;
    }
    
    public function setProduceDarkmatter($produce_darkmatter)
    {
        $this->produce_darkmatter = $produce_darkmatter;
    }
    
    public function getProduceDarkmatter()
    {
        return $this->produce_darkmatter;
    }
    
    public function setProduceRedmatter($produce_redmatter)
    {
        $this->produce_redmatter = $produce_redmatter;
    }
    
    public function getProduceRedmatter()
    {
        return $this->produce_redmatter;
    }
    
    public function setProduceAnti($produce_anti)
    {
        $this->produce_anti = $produce_anti;
    }
    
    public function getProduceAnti()
    {
        return $this->produce_anti;
    }
    
    public function setProduceElectricity($produce_electricity)
    {
        $this->produce_electricity = $produce_electricity;
    }
    
    public function getProduceElectricity()
    {
        return $this->produce_electricity;
    }
    
    /*consume*/
    
    public function setConsumeMetall($consume_metall)
    {
        $this->consume_metall = $consume_metall;
    }
    
    public function getConsumeMetall()
    {
        return $this->consume_metall;
    }
    
    public function setConsumeHeavygas($consume_heavygas)
    {
        $this->consume_heavygas = $consume_heavygas;
    }
    
    public function getConsumeHeavygas()
    {
        return $this->consume_heavygas;
    }
    
    public function setConsumeOre($consume_ore)
    {
        $this->consume_ore = $consume_ore;
    }
    
    public function getConsumeOre()
    {
        return $this->consume_ore;
    }
    
    public function setConsumeHydro($consume_hydro)
    {
        $this->consume_hydro = $consume_hydro;
    }
    
    public function getConsumeHydro()
    {
        return $this->consume_hydro;
    }
    
    public function setConsumeTitan($consume_titan)
    {
        $this->consume_titan = $consume_titan;
    }
    
    public function getConsumeTitan()
    {
        return $this->consume_titan;
    }
    
    public function setConsumeDarkmatter($consume_darkmatter)
    {
        $this->consume_darkmatter = $consume_darkmatter;
    }
    
    public function getConsumeDarkmatter()
    {
        return $this->consume_darkmatter;
    }
    
    public function setConsumeRedmatter($consume_redmatter)
    {
        $this->consume_redmatter = $consume_redmatter;
    }
    
    public function getConsumeRedmatter()
    {
        return $this->consume_redmatter;
    }
    
    public function setConsumeAnti($consume_anti)
    {
        $this->consume_anti = $consume_anti;
    }
    
    public function getConsumeAnti()
    {
        return $this->consume_anti;
    }
    
    public function setConsumeElectricity($consume_electricity)
    {
        $this->consume_electricity = $consume_electricity;
    }
    
    public function getConsumeElectricity()
    {
        return $this->consume_electricity;
    }
    
}
