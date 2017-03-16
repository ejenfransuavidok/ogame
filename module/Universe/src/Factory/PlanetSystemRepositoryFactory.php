<?php

namespace Universe\Factory;

use Interop\Container\ContainerInterface;
use Universe\Model\PlanetSystem;
use Universe\Model\PlanetSystemRepository;
use Universe\Model\Hydrator\PlanetSystemHydrator;
use Universe\Model\StarTypeRepository;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Zend\ServiceManager\Factory\FactoryInterface;

class PlanetSystemRepositoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new PlanetSystemRepository(
            $container->get(AdapterInterface::class),
            new PlanetSystemHydrator($container->get(StarTypeRepository::class)),
            new PlanetSystem(null,null,null,null,null,null,null)
        );
    }
}
