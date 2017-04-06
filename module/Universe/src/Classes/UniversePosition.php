<?php

namespace Universe\Classes;

use Universe\Model\Galaxy;
use Universe\Model\PlanetSystem;
use Universe\Model\Planet;

class UniversePosition
{
    /**
     * @ int
     */
    private $planetPosition;
    
    /**
     * @ int
     */
    private $planetSystemPosition;
    
    /**
     * @ int
     */
    private $galaxyPosition;
    
    public function setPlanetPosition($planetPosition)
    {
        $this->planetPosition = $planetPosition;
    }
    
    public function getPlanetPosition()
    {
        return $this->planetPosition;
    }
    
    public function setPlanetSystemPosition($planetSystemPosition)
    {
        $this->planetSystemPosition = $planetSystemPosition;
    }
    
    public function getPlanetSystemPosition()
    {
        return $this->planetSystemPosition;
    }
    
    public function setGalaxyPosition($galaxyPosition)
    {
        $this->galaxyPosition = $galaxyPosition;
    }
    
    public function getGalaxyPosition()
    {
        return $this->galaxyPosition;
    }

}

class UniversePosition_P_PS_G
{
    /**
     * @ Planet
     */
    private $planet;
    
    /**
     * @ PlanetSystem
     */
    private $planetSystem;
    
    /**
     * @ Galaxy
     */
    private $galaxy;
    
    
    
    public function setGalaxy($galaxy)
    {
        $this->galaxy = $galaxy;
    }
    
    public function getGalaxy()
    {
        return $this->galaxy;
    }
    
    public function setPlanetSystem($planetSystem)
    {
        $this->planetSystem = $planetSystem;
    }
    
    public function getPlanetSystem()
    {
        return $this->planetSystem;
    }
    
    public function setPlanet($planet)
    {
        $this->planet = $planet;
    }
    
    public function getPlanet()
    {
        return $this->planet;
    }
    
    
    
}
