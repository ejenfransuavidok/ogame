<?php

/**
 * 
 * @ author Jigulin V.V.
 * @ 07.02.2017
 * 
 */

namespace Universe\Model;

class StarType extends Entity
{
    const TABLE_NAME = 'stars_types';
    
    /**
     * 
     * @ string color_eng
     * 
     */
    private $color_eng;
    
    /**
     * 
     * @ string color_rus
     * 
     */
    private $color_rus;
    
    /**
     * 
     * @ int kelvin_min
     * 
     */
    private $kelvin_min;
    
    /**
     * 
     * @ int kelvin_max
     * 
     */
    private $kelvin_max;
    
    /**
     * 
     * @ string star_class
     * 
     */
    private $star_class;
    
    /**
     * 
     * @ int part
     * 
     */
    private $part;
    
    public function __construct($name, $description, $color_eng, $color_rus, $kelvin_min, $kelvin_max, $star_class, $part, $id=null)
    {
        parent::__construct(self::TABLE_NAME);
        $this->name         = $name;
        $this->description  = $description;
        $this->color_eng    = $color_eng;
        $this->color_rus    = $color_rus;
        $this->kelvin_min   = $kelvin_min;
        $this->kelvin_max   = $kelvin_max;
        $this->star_class   = $star_class;
        $this->part         = $part;
    }
    
    public function exchangeArray(array $data, $prefix = '')
    {
        $this->id           = !empty($data[$prefix.'id']) ? $data[$prefix.'id'] : null;
        $this->name         = !empty($data[$prefix.'name']) ? $data[$prefix.'name'] : null;
        $this->description  = !empty($data[$prefix.'description']) ? $data[$prefix.'description'] : null;
        $this->color_eng    = !empty($data[$prefix.'color_eng']) ? $data[$prefix.'color_eng'] : null;
        $this->color_rus    = !empty($data[$prefix.'color_rus']) ? $data[$prefix.'color_rus'] : null;
        $this->kelvin_min   = !empty($data[$prefix.'kelvin_min']) ? $data[$prefix.'kelvin_min'] : null;
        $this->kelvin_max   = !empty($data[$prefix.'kelvin_max']) ? $data[$prefix.'kelvin_max'] : null;
        $this->star_class   = !empty($data[$prefix.'star_class']) ? $data[$prefix.'star_class'] : null;
        $this->part         = !empty($data[$prefix.'part']) ? $data[$prefix.'part'] : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    /**
     * 
     * @return string
     * 
     */
    public function get_color_eng()
    {
        return $this->color_eng;
    }
    
    /**
     * 
     * @param string
     * 
     */
    public function set_color_eng($color_eng)
    {
        return $this->color_eng = $color_eng;
    }
    
    /**
     * 
     * @return string
     * 
     */
    public function get_color_rus()
    {
        return $this->color_rus;
    }
    
    /**
     * 
     * @param string
     * 
     */
    public function set_color_rus($color_rus)
    {
        $this->color_rus = $color_rus;
    }
    
    /**
     * 
     * @return int
     * 
     */
    public function get_kelvin_min()
    {
        return $this->kelvin_min;
    }
     
    /**
     * 
     * @param int
     * 
     */
    public function set_kelvin_min($kelvin_min)
    {
        $this->kelvin_min = $kelvin_min;
    }
    
    /**
     * 
     * @return int
     * 
     */
    public function get_kelvin_max()
    {
        return $this->kelvin_max;
    }
     
    /**
     * 
     * @param int
     * 
     */
    public function set_kelvin_max($kelvin_max)
    {
        $this->kelvin_max = $kelvin_max;
    }
    
    /**
     * 
     * @return string
     * 
     */
    public function get_star_class()
    {
        return $this->star_class;
    }
     
    /**
     * 
     * @param string
     * 
     */
    public function set_star_class($star_class)
    {
        $this->star_class = $star_class;
    }
     
    /**
     * 
     * @return int
     * 
     */
    public function get_part()
    {
        return $this->part;
    }
    
    /**
     * 
     * @param int
     * 
     */
    public function set_part($part)
    {
        $this->part = $part;
    }
}
