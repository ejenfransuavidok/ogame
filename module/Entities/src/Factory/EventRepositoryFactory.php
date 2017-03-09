<?php

namespace Entities\Factory;

use Interop\Container\ContainerInterface;
use Entities\Model\Event;
use Entities\Model\EventRepository;
use Entities\Model\Hydrator\EventHydrator;
use Entities\Model\UserRepository;
use Universe\Model\StarRepository;
use Universe\Model\GalaxyRepository;
use Universe\Model\PlanetSystemRepository;
use Universe\Model\PlanetRepository;
use Universe\Model\SputnikRepository;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Zend\ServiceManager\Factory\FactoryInterface;

class EventRepositoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new EventRepository(
            $container->get(AdapterInterface::class),
            new EventHydrator(
                $container->get(PlanetRepository::class),
                $container->get(SputnikRepository::class),
                $container->get(StarRepository::class),
                $container->get(UserRepository::class)),
            new Event(null,null,null,null,null,null,null,null,null,null)
        );
    }
}
