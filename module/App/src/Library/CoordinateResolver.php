<?php

namespace App\Library;

use Universe\Model\Planet;
use Universe\Model\GalaxyRepository;
use Universe\Model\PlanetSystemRepository;
use Universe\Model\PlanetRepository;
use Universe\Classes\UniversePosition;
use Universe\Classes\UniversePosition_P_PS_G;

class CoordinateResolver
{
    
    /**
     * @ GalaxyRepository
     */
    private $galaxyRepository;
    
    /**
     * @ PlanetSystemRepository
     */
    private $planetSystemRepository;
    
    /**
     * @ PlanetRepository
     */
    private $planetRepository;
    
    public function __construct(GalaxyRepository $galaxyRepository, PlanetSystemRepository $planetSystemRepository, PlanetRepository $planetRepository)
    {
        $this->galaxyRepository = $galaxyRepository;
        $this->planetSystemRepository = $planetSystemRepository;
        $this->planetRepository = $planetRepository;
    }
    
    public function resolveByPlanet(Planet $planet)
    {
        $planet_position = $planet->getPosition();
        $planet_system = $planet->getPlanetSystem();
        $galaxy = $planet_system->getGalaxy();
        $universePosition = new UniversePosition();
        $universePosition->setPlanetPosition($planet_position);
        $universePosition->setPlanetSystemPosition($planet_system->getIndex());
        $universePosition->setGalaxyPosition($galaxy->getIndex());
        return $universePosition;
    }
    
    public function resolveByUniversePosition(UniversePosition $universePosition)
    {
        $planet_position = $universePosition->getPlanetPosition();
        $planet_system_position = $universePosition->getPlanetSystemPosition();
        $galaxy_position = $universePosition->getGalaxyPosition();
        
        //print_r(array($planet_position, $planet_system_position, $galaxy_position)); 
        //echo 'planets.position = ' . $planet_position . ' AND planets.planet_system = ' . $planet_system_position;
        try{
            if($galaxy = $this->galaxyRepository->findOneBy('galaxies.id = ' . $galaxy_position)){
                if($planet_system = $this->planetSystemRepository->findOneBy(
                    'planet_system.index = ' . $planet_system_position .
                    ' AND planet_system.galaxy = ' . $galaxy->getId())){
                    if($planet = $this->planetRepository->findOneBy('planets.position = ' . $planet_position . ' AND planets.planet_system = ' . $planet_system->getId())){
                        $universePosition_p_ps_g = new UniversePosition_P_PS_G();
                        $universePosition_p_ps_g->setPlanet($planet);
                        $universePosition_p_ps_g->setPlanetSystem($planet_system);
                        $universePosition_p_ps_g->setGalaxy($galaxy);
                        return $universePosition_p_ps_g;
                    }
                }
            }
            return false;
        }
        catch (\Exception $e){
            return false;
        }
    }
    
}
