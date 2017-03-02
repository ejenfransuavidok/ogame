<?php

namespace Entities\Factory;

use Interop\Container\ContainerInterface;
use Entities\Model\Technology;
use Entities\Model\TechnologyRepository;
use Entities\Model\Hydrator\TechnologyConnectionHydrator;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Zend\ServiceManager\Factory\FactoryInterface;

class TechnologyConnectionRepositoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new TechnologyRepository(
            $container->get(AdapterInterface::class),
            new TechnologyConnectionHydrator(TechnologyRepository::class),
            new TechnologyConnection(null,null,null,null)
        );
    }
}
