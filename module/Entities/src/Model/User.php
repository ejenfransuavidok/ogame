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
    
    public function __construct($name, $description, $password, $email, $firstname, $lastname, $galaxy, $planet_system, $planet, $sputnik, $star, $id=null)
    {
        parent::__construct(self::TABLE_NAME);
        $this->name             = $name;
        $this->description      = $description;
        $this->password         = $password;
        $this->email            = $email;
        $this->firstname        = $firstname;
        $this->lastname         = $lastname;
        $this->galaxy           = $galaxy;
        $this->planet_system    = $planet_system;
        $this->planet           = $planet;
        $this->sputnik          = $sputnik;
        $this->star             = $star;
        $this->id               = $id;
    }
    
    public function exchangeArray(array $data, $prefix = '')
    {
        $this->id               = !empty($data[$prefix.'id']) ? $data[$prefix.'id'] : null;
        $this->name             = !empty($data[$prefix.'login']) ? $data[$prefix.'login'] : null;
        $this->description      = !empty($data[$prefix.'description']) ? $data[$prefix.'description'] : null;
        $this->password         = !empty($data[$prefix.'password']) ? $data[$prefix.'password'] : null;
        $this->email            = !empty($data[$prefix.'email']) ? $data[$prefix.'email'] : null;
        $this->firstname        = !empty($data[$prefix.'firstname']) ? $data[$prefix.'firstname'] : null;
        $this->lastname         = !empty($data[$prefix.'lastname']) ? $data[$prefix.'lastname'] : null;
        $this->galaxy           = !empty($data[$prefix.'galaxy']) ? $data[$prefix.'galaxy'] : null;
        $this->planet_system    = !empty($data[$prefix.'planet_system']) ? $data[$prefix.'planet_system'] : null;
        $this->planet           = !empty($data[$prefix.'planet']) ? $data[$prefix.'planet'] : null;
        $this->sputnik          = !empty($data[$prefix.'sputnik']) ? $data[$prefix.'sputnik'] : null;
        $this->star             = !empty($data[$prefix.'star']) ? $data[$prefix.'star'] : null;
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
    
    public function getEmail($email)
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
    
    public function setGalaxy($galaxy)
    {
        $this->galaxy = $galaxy;
    }
    
    public function getGalaxy()
    {
        return $this->galaxy;
    }
    
    public function setPlanetSystem($planet_system)
    {
        $this->planet_system = $planet_system;
    }
    
    public function getPlanetSystem()
    {
        return $this->planet_system;
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
    
    public function setStar($star)
    {
        $this->star = $star;
    }
    
    public function getStar()
    {
        return $this->star;
    }
}
