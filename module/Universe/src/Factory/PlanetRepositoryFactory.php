<?php

namespace Universe\Factory;

use Interop\Container\ContainerInterface;
use Universe\Model\Planet;
use Universe\Model\PlanetRepository;
use Universe\Model\Hydrator\PlanetHydrator;
use Universe\Model\PlanetSystemRepository;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Zend\ServiceManager\Factory\FactoryInterface;

class PlanetRepositoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new PlanetRepository(
            $container->get(AdapterInterface::class),
            new PlanetHydrator($container->get(PlanetSystemRepository::class)),
            new Planet(null,null,null,null,null,null,null,null,null,null,null,null,null,null,null)
        );
    }
}
