<?php

namespace Entities\Factory;

use Interop\Container\ContainerInterface;
use Entities\Model\BuildingType;
use Entities\Model\BuildingTypeRepository;
use Entities\Model\UserRepository;
use Universe\Model\PlanetRepository;
use Universe\Model\SputnikRepository;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Zend\ServiceManager\Factory\FactoryInterface;

class BuildingTypeRepositoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new BuildingTypeRepository(
            $container->get(AdapterInterface::class),
            new ReflectionHydrator(),
            new BuildingType(null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null)
        );
    }
}
