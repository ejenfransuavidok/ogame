<?php

namespace Universe\Factory;

use Interop\Container\ContainerInterface;
use Universe\Model\Star;
use Universe\Model\StarRepository;
use Universe\Model\Hydrator\StarHydrator;
use Universe\Model\StarTypeRepository;
use Universe\Model\PlanetSystemRepository;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Zend\ServiceManager\Factory\FactoryInterface;

class StarRepositoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new StarRepository(
            $container->get(AdapterInterface::class),
            new StarHydrator($container->get(StarTypeRepository::class), $container->get(PlanetSystemRepository::class)),
            new Star(null,null,null,null,null,null)
        );
    }
}
