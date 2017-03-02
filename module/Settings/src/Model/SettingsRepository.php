<?php

namespace Settings\Model;

use DomainException;

class SettingsRepository implements SettingsRepositoryInterface
{
    private $data = [
        1 => [
            'id'    => 1,
            'title' => 'Hello World #1',
            'text'  => 'This is our first blog post!',
        ],
        2 => [
            'id'    => 2,
            'title' => 'Hello World #2',
            'text'  => 'This is our second blog post!',
        ],
        3 => [
            'id'    => 3,
            'title' => 'Hello World #3',
            'text'  => 'This is our third blog post!',
        ],
        4 => [
            'id'    => 4,
            'title' => 'Hello World #4',
            'text'  => 'This is our fourth blog post!',
        ],
        5 => [
            'id'    => 5,
            'title' => 'Hello World #5',
            'text'  => 'This is our fifth blog post!',
        ],
    ];
    /**
     * {@inheritDoc}
     */
    public function findAllSettings()
    {
        return array_map(function ($setting) {
            return new Setting(
                $setting['title'],
                $setting['text'],
                $setting['id']
            );
        }, $this->data);
    }

    /**
     * {@inheritDoc}
     */
    public function findSetting($id)
    {
        if (! isset($this->data[$id])) {
            throw new DomainException(sprintf('Setting by id "%s" not found', $id));
        }

        return new Setting(
            $this->data[$id]['title'],
            $this->data[$id]['text'],
            $this->data[$id]['id']
        );
    }
}
