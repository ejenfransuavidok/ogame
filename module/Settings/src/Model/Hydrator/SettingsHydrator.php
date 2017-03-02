<?php
namespace Settings\Model\Hydrator;

use Settings\Model\Setting;
use Settings\Model\SettingsTypeof;
use Zend\Hydrator\Exception\BadMethodCallException;
use Zend\Hydrator\HydratorInterface;

class SettingsHydrator implements HydratorInterface
{
    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  Employee $object
     *
     * @return Settings
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof Setting) {
            throw new \BadMethodCallException(sprintf(
                '%s expects the provided $object:'.get_class($object).' to be a PHP Setting object)',
                __METHOD__
            ));
        }
        // get a clean employee object and get the data from the db result (no prefix here!)
        $setting = new Setting('', '', '', '');
        $setting->exchangeArray($data);
        // this is an example when you have a foreign key relation to the employee table itself (see the prefix)
        $settingsTypeof = new SettingsTypeof('', '');
        $settingsTypeof->exchangeArray($data, 'settings_types.');
        $setting->setTypeof ($settingsTypeof);
        return $setting;
    }
    /**
     * @param Settings $object
     *
     * @return mixed
     */
    public function extract($object)
    {
        if (!is_callable([$object, 'getArrayCopy'])) {
            throw new BadMethodCallException(
                sprintf('%s expects the provided object to implement getArrayCopy()', __METHOD__)
            );
        }
        return $object->getArrayCopy();
    }
}
