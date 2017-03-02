<?php

namespace Settings\Factory;

use Interop\Container\ContainerInterface;
use Settings\Model\SettingsTypeof;
use Settings\Model\SettingsTypeofRepository;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Zend\ServiceManager\Factory\FactoryInterface;

class SettingsTypeofRepositoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new SettingsTypeofRepository(
            $container->get(AdapterInterface::class),
            new ReflectionHydrator(),
            new SettingsTypeof('', '')
        );
    }
}
