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

class TechnologyConnection extends Entity
{
    const TABLE_NAME = 'technologies_connections';
    
    /**
     * 
     * @Technology
     * 
     */
    private $tech_1;
    
    /**
     * 
     * @Technology
     * 
     */
    private $tech_2;
    
    /**
     * 
     * @boolean
     * 
     */
    private $conn;
    
    public function __construct($tech_1, $tech_2, $conn, $id=null)
    {
        parent::__construct(self::TABLE_NAME);
        $this->tech_1       = $tech_1;
        $this->tech_2       = $tech_2;
        $this->conn         = $conn;
        $this->id           = $id;
    }
    
    public function exchangeArray(array $data, $prefix = '')
    {
        $this->id           = !empty($data[$prefix.'id']) ? $data[$prefix.'id'] : null;
        $this->tech_1       = !empty($data[$prefix.'tech_1']) ? $data[$prefix.'tech_1'] : null;
        $this->tech_2       = !empty($data[$prefix.'tech_2']) ? $data[$prefix.'tech_2'] : null;
        $this->conn         = !empty($data[$prefix.'conn']) ? $data[$prefix.'conn'] : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    public function getTech_1()
    {
        return $this->tech_1;
    }
    
    public function setTech_1($tech_1)
    {
        return $this->tech_1 = $tech_1;
    }
    
    public function getTech_2()
    {
        return $this->tech_2;
    }
    
    public function setTech_2($tech_2)
    {
        return $this->tech_2 = $tech_2;
    }
    
    public function getConn()
    {
        return $this->conn;
    }
    
    public function setConn($conn)
    {
        return $this->conn = $conn;
    }
}
