<?php

namespace Universe\Factory;

use Interop\Container\ContainerInterface;
use Universe\Model\PlanetType;
use Universe\Model\PlanetTypeRepository;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Zend\ServiceManager\Factory\FactoryInterface;

class PlanetTypeRepositoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new PlanetTypeRepository(
            $container->get(AdapterInterface::class),
            new ReflectionHydrator(),
            new PlanetType(null,null,null,null,null,null,null,null,null,null,null)
        );
    }
}
