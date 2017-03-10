<?php

/**
 * 
 * @ author Jigulin V.V.
 * @ 07.02.2017
 * 
 */

namespace Universe\Model;

use RuntimeException;
use Zend\Math\Rand;

class Entity
{
    /**
     * 
     * @ string
     * 
     */
    protected $tablename;
    
    /**
     * 
     * @string
     * 
     */
    protected $name;
    
    /**
     * 
     * @int
     * 
     */
    protected $id;
    
    /**
     * 
     * @text
     * 
     */
    protected $description;
    
    /**
     * 
     * @param string $tablename
     * 
     */
    public function __construct($tablename)
    {
        $this->tablename = $tablename;
    }
    
    /**
     * 
     * @param string $tablename
     * 
     */
    public function setTable($tablename)
    {
        $this->tablename = $tablename;
    }
    
    /**
     * 
     * @return string $tablename
     * 
     */
    public function getTable()
    {
        return $this->tablename;
    }
    
    /**
     * 
     * @params string
     *
     */
     public function setName($name)
     {
         $this->name = $name;
     }
     
     /**
      * 
      * @return string
      * 
      */
    public function getName()
    {
        return $this->name;
    }
     
     /**
      * 
      * @return int
      * 
      */
    public function getId()
    {
        return $this->id != '' ? $this->id : 0;
    }
    
    /**
     * 
     * @param int
     * 
     */
    public function setId($id)
    {
        throw RuntimeException('Cannot setup id');
    }
    
    /**
     * 
     * @param text
     * 
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
    
    /**
     * 
     * @return text
     * 
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * 
     * @param void
     * @return void
     * 
     */
    public function setRandomName()
    {
        $this->name = Rand::getString(32, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789');
    }
}
