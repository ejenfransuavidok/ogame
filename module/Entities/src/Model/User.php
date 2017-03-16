<?php

/**
 * 
 * @ author Jigulin V.V.
 * @ 27.02.2017
 * 
 */

namespace Entities\Model;

use RuntimeException;
use Zend\Math\Rand;
use Universe\Model\Entity;

class User extends Entity
{
    const TABLE_NAME = 'users';
    
    /**
     * @var string
     */
    protected $password;
    
    /**
     * @var email
     */
    protected $email;
    
    /**
     * @var string
     */
    protected $firstname;
    
    /**
     * @var lastname
     */
    protected $lastname;
    
    /**
     * @var Galaxy
     */
    protected $galaxy;
    
    /**
     * @var PlanetSystem
     */
    protected $planet_system;
    
    /**
     * @var Planet
     */
    protected $planet;
    
    /**
     * @var Sputnik
     */
    protected $sputnik;
    
    /**
     * @var Star
     */
    protected $star;
    
    /* sources */
    
    /**
     * @ int
     */
    protected $amount_of_metall;
    
    /**
     * @ int
     */
    protected $amount_of_heavygas;
    
    /**
     * @ int
     */
    protected $amount_of_ore;
    
    /**
     * @ int
     */
    protected $amount_of_hydro;
    
    /**
     * @ int
     */
    protected $amount_of_titan;
    
    /**
     * @ int
     */
    protected $amount_of_darkmatter;
    
    /**
     * @ int
     */
    protected $amount_of_redmatter;
    
    /**
     * @ int
     */
    protected $amount_of_anti;
    
    /**
     * @ int
     */
    protected $amount_of_electricity;
    
    public function __construct(
        $name, 
        $description, 
        $password, 
        $email, 
        $firstname, 
        $lastname, 
        $amount_of_metall,
        $amount_of_heavygas,
        $amount_of_ore,
        $amount_of_hydro,
        $amount_of_titan,
        $amount_of_darkmatter,
        $amount_of_redmatter,
        $amount_of_anti,
        $amount_of_electricity,
        $id=null)
    {
        parent::__construct(self::TABLE_NAME);
        $this->name                  = $name;
        $this->description           = $description;
        $this->password              = $password;
        $this->email                 = $email;
        $this->firstname             = $firstname;
        $this->lastname              = $lastname;
        $this->amount_of_metall      = $amount_of_metall;
        $this->amount_of_heavygas    = $amount_of_heavygas;
        $this->amount_of_ore         = $amount_of_ore;
        $this->amount_of_hydro       = $amount_of_hydro;
        $this->amount_of_titan       = $amount_of_titan;
        $this->amount_of_darkmatter  = $amount_of_darkmatter;
        $this->amount_of_redmatter   = $amount_of_redmatter;
        $this->amount_of_anti        = $amount_of_anti;
        $this->amount_of_electricity = $amount_of_electricity;
        $this->id                    = $id;
    }
    
    public function exchangeArray(array $data, $prefix = '')
    {
        $this->id                    = !empty($data[$prefix.'id']) ? $data[$prefix.'id'] : null;
        $this->name                  = !empty($data[$prefix.'login']) ? $data[$prefix.'login'] : null;
        $this->description           = !empty($data[$prefix.'description']) ? $data[$prefix.'description'] : null;
        $this->password              = !empty($data[$prefix.'password']) ? $data[$prefix.'password'] : null;
        $this->email                 = !empty($data[$prefix.'email']) ? $data[$prefix.'email'] : null;
        $this->firstname             = !empty($data[$prefix.'firstname']) ? $data[$prefix.'firstname'] : null;
        $this->lastname              = !empty($data[$prefix.'lastname']) ? $data[$prefix.'lastname'] : null;
        $this->amount_of_metall      = !empty($data[$prefix.'amount_of_metall']) ? $data[$prefix.'amount_of_metall'] : null;
        $this->amount_of_heavygas    = !empty($data[$prefix.'amount_of_heavygas']) ? $data[$prefix.'amount_of_heavygas'] : null;
        $this->amount_of_ore         = !empty($data[$prefix.'amount_of_ore']) ? $data[$prefix.'amount_of_ore'] : null;
        $this->amount_of_hydro       = !empty($data[$prefix.'amount_of_hydro']) ? $data[$prefix.'amount_of_hydro'] : null;
        $this->amount_of_titan       = !empty($data[$prefix.'amount_of_titan']) ? $data[$prefix.'amount_of_titan'] : null;
        $this->amount_of_darkmatter  = !empty($data[$prefix.'amount_of_darkmatter']) ? $data[$prefix.'amount_of_darkmatter'] : null;
        $this->amount_of_redmatter   = !empty($data[$prefix.'amount_of_redmatter']) ? $data[$prefix.'amount_of_redmatter'] : null;
        $this->amount_of_anti        = !empty($data[$prefix.'amount_of_anti']) ? $data[$prefix.'amount_of_anti'] : null;
        $this->amount_of_electricity = !empty($data[$prefix.'amount_of_electricity']) ? $data[$prefix.'amount_of_electricity'] : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    public function setPassword($password)
    {
        $this->password = $password;
    }
    
    public function getPassword()
    {
        return $this->password;
    }
    
    public function setEmail($email)
    {
        $this->email = $email;
    }
    
    public function getEmail()
    {
        return $this->email;
    }
    
    public function setFirstName($firstname)
    {
        $this->firstname = $firstname;
    }
    
    public function getFirstName()
    {
        return $this->firstname;
    }
    
    public function setLastName($lastname)
    {
        $this->lastname = $lastname;
    }
    
    public function getLastName()
    {
        return $this->lastname;
    }
    
    public function setAmountOfMetall($amount_of_metall)
    {
        $this->amount_of_metall = $amount_of_metall;
    }
    
    public function getAmountOfMetall()
    {
        return $this->amount_of_metall;
    }
    
    public function setAmountOfHeavygas($amount_of_heavygas)
    {
        $this->amount_of_heavygas = $amount_of_heavygas;
    }
    
    public function getAmountOfHeavygas()
    {
        return $this->amount_of_heavygas;
    }
    
    public function setAmountOfOre($amount_of_ore)
    {
        $this->amount_of_ore = $amount_of_ore;
    }
    
    public function getAmountOfOre()
    {
        return $this->amount_of_ore;
    }
    
    public function setAmountOfHydro($amount_of_hydro)
    {
        $this->amount_of_hydro = $amount_of_hydro;
    }
    
    public function getAmountOfHydro()
    {
        return $this->amount_of_hydro;
    }
    
    public function setAmountOfTitan($amount_of_titan)
    {
        $this->amount_of_titan = $amount_of_titan;
    }
    
    public function getAmountOfTitan()
    {
        return $this->amount_of_titan;
    }
    
    public function setAmountOfDarkmatter($amount_of_darkmatter)
    {
        $this->amount_of_darkmatter = $amount_of_darkmatter;
    }
    
    public function getAmountOfDarkmatter()
    {
        return $this->amount_of_darkmatter;
    }
    
    public function setAmountOfRedmatter($amount_of_redmatter)
    {
        $this->amount_of_redmatter = $amount_of_redmatter;
    }
    
    public function getAmountOfRedmatter()
    {
        return $this->amount_of_redmatter;
    }

    public function setAmountOfAnti($amount_of_anti)
    {
        $this->amount_of_anti = $amount_of_anti;
    }
    
    public function getAmountOfAnti()
    {
        return $this->amount_of_anti;
    }
    
    public function setAmountOfElectricity($amount_of_electricity)
    {
        $this->amount_of_electricity = $amount_of_electricity;
    }
    
    public function getAmountOfElectricity()
    {
        return $this->amount_of_electricity;
    }
    
}
