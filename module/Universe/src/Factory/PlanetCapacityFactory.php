<?php

namespace Universe\Factory;

use Universe\Classes\PlanetCapacity;
use Entities\Model\BuildingRepository;
use Settings\Model\Setting;
use Settings\Model\SettingsRepositoryInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class PlanetCapacityFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return ListController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new PlanetCapacity(
            $container->get(SettingsRepositoryInterface::class),
            $container->get(BuildingRepository::class)
        );
    }
}
