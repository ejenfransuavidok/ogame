<?php

namespace Universe\Model;

interface EntityRepositoryInterface
{
    /**
     * Return a set of all blog settingss that we can iterate over.
     *
     * Each entry should be a Settings instance.
     *
     * @return Settings[]
     */
    public function findAllEntities();

    /**
     * Return a single blog settings.
     *
     * @param  int $id Identifier of the settings to return.
     * @return Settings
     */
    public function findEntity($id);
}
