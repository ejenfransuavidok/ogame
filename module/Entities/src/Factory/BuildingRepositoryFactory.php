<?php

namespace Entities\Factory;

use Interop\Container\ContainerInterface;
use Entities\Model\Building;
use Entities\Model\BuildingRepository;
use Entities\Model\Hydrator\BuildingHydrator;
use Entities\Model\UserRepository;
use Universe\Model\PlanetRepository;
use Universe\Model\SputnikRepository;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Zend\ServiceManager\Factory\FactoryInterface;

class BuildingRepositoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new BuildingRepository(
            $container->get(AdapterInterface::class),
            new BuildingHydrator(
                $container->get(PlanetRepository::class),
                $container->get(SputnikRepository::class),
                $container->get(UserRepository::class)),
            new Building(null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null)
        );
    }
}
