<?php

namespace Settings\Model;

use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;

class Setting implements InputFilterAwareInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $title;
    
    /**
     * @var int
     */
    private $typeof_id;
    
    /**
     * @var int
     */
    private $typeof;
    
    /**
     * @var varchar(20)
     */
    private $setting_key;
    
    private $inputFilter;
    
    /**
     * @param string $title
     * @param string $text
     * @param int|null $id
     */
    public function __construct($title, $text, $setting_key, $typeof_id, $id = null)
    {
        $this->title = $title;
        $this->text = $text;
        $this->setting_key = $setting_key;
        $this->typeof_id = $typeof_id;
        $this->id = $id;
    }
    
    public function exchangeArray(array $data, $prefix = '')
    {
        $this->title        = !empty($data[$prefix.'title']) ? $data[$prefix.'title'] : null;
        $this->text         = !empty($data[$prefix.'text']) ? $data[$prefix.'text'] : null;
        $this->setting_key  = !empty($data[$prefix.'setting_key']) ? $data[$prefix.'setting_key'] : null;
        $this->typeof_id    = !empty($data[$prefix.'typeof_id']) ? $data[$prefix.'typeof_id'] : null;
        $this->id           = !empty($data[$prefix.'id']) ? $data[$prefix.'id'] : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
    
    /**
     *
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     *
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    /**
     * @return varchar(20)
     */
    public function getKey()
    {
        return $this->setting_key;
    }
    
    /**
     * @return int
     */
     public function getTypeof_id ()
     {
         return $this->typeof_id;
     }
     
     public function getTypeof ()
     {
         return $this->typeof;
     }
     
    public function setTypeof ($typeof)
    {
        $this->typeof = $typeof;
        return $this;
    }
    
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }
        
        $inputFilter = new InputFilter();
        
        $inputFilter->add([
            'name' => 'title',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);
        
        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
}
