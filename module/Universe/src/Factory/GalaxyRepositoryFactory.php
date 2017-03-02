<?php

namespace Universe\Factory;

use Interop\Container\ContainerInterface;
use Universe\Model\Galaxy;
use Universe\Model\GalaxyRepository;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Zend\ServiceManager\Factory\FactoryInterface;

class GalaxyRepositoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new GalaxyRepository(
            $container->get(AdapterInterface::class),
            new ReflectionHydrator(),
            new Galaxy('','','',0)
        );
    }
}
