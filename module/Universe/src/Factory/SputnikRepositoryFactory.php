<?php

namespace Universe\Factory;

use Interop\Container\ContainerInterface;
use Universe\Model\Sputnik;
use Universe\Model\SputnikRepository;
use Universe\Model\PlanetSystemRepository;
use Universe\Model\PlanetRepository;
use Universe\Model\PlanetTypeRepository;
use Universe\Model\Hydrator\SputnikHydrator;
use Entities\Model\UserRepository;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Zend\ServiceManager\Factory\FactoryInterface;

class SputnikRepositoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new SputnikRepository(
            $container->get(AdapterInterface::class),
            new SputnikHydrator(
                $container->get(PlanetSystemRepository::class), 
                $container->get(PlanetRepository::class), 
                $container->get(PlanetTypeRepository::class),
                $container->get(UserRepository::class)),
            new Sputnik(null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null)
        );
    }
}
