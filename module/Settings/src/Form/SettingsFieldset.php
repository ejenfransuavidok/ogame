<?php
namespace Settings\Form;

use Zend\Form\Fieldset;
use Settings\Model\Setting;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Zend\Form\Element;
use Zend\Validator\StringLength;

class SettingsFieldset extends Fieldset
{
    
    public function init()
    {
        $this->setHydrator(new ReflectionHydrator());
        $this->setObject(new Setting('', '', '', ''));
        
        $this->add([
            'type' => 'hidden',
            'name' => 'id',
        ]);

        $this->add([
            'type'      => 'text',
            'name'      => 'title',
            'options'   => [
                'label' => 'Имя настройки',
            ],
        ]);
        
        $this->add([
            'type'      => 'text',
            'name'      => 'setting_key',
            'options'   => [
                'label' => 'Ключ настройки',
            ],
        ]);
        
        $this->add([
            'type' => Element\Select::class,
            'name' => 'typeof_id',
            'options' => [
                'label' => 'Тип переменной',
                'value_options' => [
                ],
            ],
        ]);

        $this->add([
            'type' => 'textarea',
            'name' => 'text',
            'options' => [
                'label' => 'Содержание настройки',
            ],
        ]);
    }

}
