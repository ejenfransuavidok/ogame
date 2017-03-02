<?php
namespace Entities\Factory;

use Interop\Container\ContainerInterface;
use Entities\Model\ConditionCommand;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ConditionCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ConditionCommand($container->get(AdapterInterface::class));
    }
}
