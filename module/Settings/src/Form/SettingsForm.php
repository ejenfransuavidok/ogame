<?php
namespace Settings\Form;

use Zend\Form\Form;

class SettingsForm extends Form
{
    
    private $repositoryTypeof;
    
    public function init()
    {
        $this->add([
            'name' => 'setting',
            'type' => SettingsFieldset::class,
            'options' => [
                'use_as_base_fieldset' => true,
            ],
        ]);

        $this->add([
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Добавить новую настройку',
            ],
        ]);
    }
    
    public function setRepositoryTypeof ($repositoryTypeof)
    {
        $this->repositoryTypeof = $repositoryTypeof;
        $fieldset = $this->get('setting');
        $typeof_id = $fieldset->get('typeof_id');
        $array = array ();
        foreach ($this->repositoryTypeof->findAllSettingsTypeof() as $row) {
            $array [$row->getId()] = $row->getTitle();
        }
        $typeof_id->setValueOptions ($array);
    }
    
}
