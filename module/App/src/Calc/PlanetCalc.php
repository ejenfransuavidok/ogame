<?php

namespace App\Calc;

use User\Model\UserCommand;
use User\Model\UserRepository;
use User\Model\User;
use Universe\Model\PlanetCommand;
use Universe\Model\PlanetRepository;
use Universe\Model\Planet;

class PlanetCalc
{
    
    /**
     * @ User
     */
    protected $owner;
    
    /**
     * @ Planet
     */
    protected $planet;
    
    public function calcPlanetResources($owner, $planet)
    {
        $this->owner = $owner;
        $this->planet = $planet;
    }
    
}
