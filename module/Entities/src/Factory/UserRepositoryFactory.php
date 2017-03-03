<?php

namespace Entities\Factory;

use Interop\Container\ContainerInterface;
use Universe\Model\GalaxyRepository;
use Universe\Model\PlanetSystemRepository;
use Universe\Model\PlanetRepository;
use Universe\Model\SputnikRepository;
use Universe\Model\StarRepository;
use Entities\Model\Hydrator\UserHydrator;
use Entities\Model\User;
use Entities\Model\UserRepository;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Zend\ServiceManager\Factory\FactoryInterface;

class UserRepositoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new UserRepository(
            $container->get(AdapterInterface::class),
            new UserHydrator(
                $container->get(GalaxyRepository::class), 
                $container->get(PlanetSystemRepository::class),
                $container->get(PlanetRepository::class),
                $container->get(SputnikRepository::class),
                $container->get(StarRepository::class)
            ),
            new User(null,null,null,null,null,null,null,null,null,null,null)
        );
    }
}
