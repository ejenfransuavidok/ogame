<?php

namespace Entities\Factory;

use Interop\Container\ContainerInterface;
use Entities\Model\Condition;
use Entities\Model\ConditionRepository;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Zend\ServiceManager\Factory\FactoryInterface;

class ConditionRepositoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ConditionRepository(
            $container->get(AdapterInterface::class),
            new ReflectionHydrator(),
            new Condition(null,null,null)
        );
    }
}
