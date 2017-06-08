<?php

namespace Entities\Classes;

class CustomTypesList
{
    public $TYPE_UNDEFINED = -1;
    public $types = array();

    public function decodeTypeStr($key)
    {
        return isset($this->types[$key]) ? $this->types[$key] : $this->TYPE_UNDEFINED;
    }
}
