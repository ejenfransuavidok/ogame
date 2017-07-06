<?php

namespace Entities\Factory;

use Interop\Container\ContainerInterface;
use Entities\Model\Source;
use Entities\Model\SourceRepository;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Zend\ServiceManager\Factory\FactoryInterface;
use Settings\Model\SettingsRepositoryInterface;

class SourceRepositoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new SourceRepository(
            $container->get(AdapterInterface::class),
            new ReflectionHydrator(),
            new Source(0,0,0,0,0,0),
            $container->get(SettingsRepositoryInterface::class)
        );
    }
}
