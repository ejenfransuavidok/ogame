<?php

/**
 * 
 * @ author Jigulin V.V.
 * @ 07.02.2017
 * 
 */
 
/**
 * любой контейнер вписан в квадрат, со стороной равной диаметру
 * ключевая координата - координата левого нижнего угла (декартовы
 * координаты)
 */

namespace Universe\Model;

use Universe\Model\Star;

class PlanetSystem extends CelestialContainer
{
    const TABLE_NAME = 'planet_system';
    
    /**
     * 
     * @Star
     * 
     */
    private $star;
    
    /**
     * 
     * @Galaxy
     * 
     */
    private $galaxy;
    
    /**
     * @ int
     */
    protected $index;
    
    public function __construct($name, $description, $basis, $size, $star, $galaxy, $index, $id=null)
    {
        parent::__construct(self::TABLE_NAME);
        $this->name         = $name;
        $this->description  = $description;
        $this->basis        = $basis;
        $this->size         = $size;
        $this->star         = $star;
        $this->galaxy       = $galaxy;
        $this->index        = $index;
        $this->id           = $id;
    }
    
    public function exchangeArray(array $data, $prefix = '')
    {
        $this->id           = !empty($data[$prefix.'id']) ? $data[$prefix.'id'] : null;
        $this->name         = !empty($data[$prefix.'name']) ? $data[$prefix.'name'] : null;
        $this->description  = !empty($data[$prefix.'description']) ? $data[$prefix.'description'] : null;
        $this->basis        = !empty($data[$prefix.'basis']) ? $data[$prefix.'basis'] : null;
        $this->size         = !empty($data[$prefix.'size']) ? $data[$prefix.'size'] : null;
        $this->star         = !empty($data[$prefix.'star']) ? $data[$prefix.'star'] : null;
        $this->galaxy       = !empty($data[$prefix.'galaxy']) ? $data[$prefix.'galaxy'] : null;
        $this->index        = !empty($data[$prefix.'index']) ? $data[$prefix.'index'] : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    /**
     * 
     * @param Star
     * 
     */
    public function setStar($star)
    {
        $this->star = $star;
    }
    
    /**
     * 
     * @return Star
     * 
     */
    public function getStar()
    {
        return $this->star;
    }
    
    /**
     * 
     * @param Galaxy
     * 
     */
    public function setGalaxy($galaxy)
    {
        $this->galaxy = $galaxy;
    }
    
    /**
     * 
     * @return Galaxy
     * 
     */
    public function getGalaxy()
    {
        return $this->galaxy;
    }
    
    /**
     * @ param int
     */
    public function setIndex($index)
    {
        $this->index = $index;
    }
    
    /**
     * @ int
     */
    public function getIndex()
    {
        return $this->index;
    }
    
}
