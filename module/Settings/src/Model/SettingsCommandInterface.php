<?php

namespace Settings\Model;

interface SettingsCommandInterface
{
    /**
     * Persist a new setting in the system.
     *
     * @param Post $setting The setting to insert; may or may not have an identifier.
     * @return Post The inserted setting, with identifier.
     */
    public function insertSetting(Setting $setting);

    /**
     * Update an existing setting in the system.
     *
     * @param Post $setting The setting to update; must have an identifier.
     * @return Post The updated setting.
     */
    public function updateSetting(Setting $setting);

    /**
     * Delete a setting from the system.
     *
     * @param Post $setting The setting to delete.
     * @return bool
     */
    public function deleteSetting(Setting $setting);
}
