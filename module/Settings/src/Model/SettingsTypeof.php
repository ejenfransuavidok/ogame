<?php

namespace Settings\Model;

class SettingsTypeof
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $typeof;

    /**
     * @param string $title
     * @param string $typeof
     * @param int|null $id
     */
    public function __construct($title, $typeof, $id = null)
    {
        $this->title = $title;
        $this->typeof = $typeof;
        $this->id = $id;
    }

    public function exchangeArray(array $data, $prefix = '')
    {
        $this->title    = !empty($data[$prefix.'title']) ? $data[$prefix.'title'] : null;
        $this->typeof   = !empty($data[$prefix.'typeof']) ? $data[$prefix.'typeof'] : null;
        $this->id       = !empty($data[$prefix.'id']) ? $data[$prefix.'id'] : null;
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
    public function getTypeof()
    {
        return $this->typeof;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
}
