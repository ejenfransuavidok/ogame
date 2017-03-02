<?php

namespace Entities\Factory;

use Interop\Container\ContainerInterface;
use Entities\Model\Technology;
use Entities\Model\TechnologyRepository;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Zend\ServiceManager\Factory\FactoryInterface;

class TechnologyRepositoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new TechnologyRepository(
            $container->get(AdapterInterface::class),
            new ReflectionHydrator(),
            new Technology(null,null,null)
        );
    }
}
