<?php

namespace Entities\Factory;

use Interop\Container\ContainerInterface;
use Entities\Model\SpaceSheep;
use Entities\Model\SpaceSheepRepository;
use Entities\Model\Hydrator\SpaceSheepHydrator;
use Entities\Model\UserRepository;
use Entities\Model\EventRepository;
use Universe\Model\StarRepository;
use Universe\Model\GalaxyRepository;
use Universe\Model\PlanetSystemRepository;
use Universe\Model\PlanetRepository;
use Universe\Model\SputnikRepository;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Zend\ServiceManager\Factory\FactoryInterface;

class SpaceSheepRepositoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new SpaceSheepRepository(
            $container->get(AdapterInterface::class),
            new SpaceSheepHydrator(
                $container->get(GalaxyRepository::class),
                $container->get(PlanetSystemRepository::class),
                $container->get(PlanetRepository::class),
                $container->get(SputnikRepository::class),
                $container->get(StarRepository::class),
                $container->get(UserRepository::class),
                $container->get(EventRepository::class)),
            new SpaceSheep(null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null)
        );
    }
}
