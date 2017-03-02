<?php

namespace Universe\Factory;

use Interop\Container\ContainerInterface;
use Universe\Model\StarType;
use Universe\Model\StarTypeRepository;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Zend\ServiceManager\Factory\FactoryInterface;

class StarTypeRepositoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new StarTypeRepository(
            $container->get(AdapterInterface::class),
            new ReflectionHydrator(),
            new StarType('','','','','','','','')
        );
    }
}
