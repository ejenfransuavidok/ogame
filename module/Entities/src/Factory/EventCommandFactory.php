<?php
namespace Entities\Factory;

use Interop\Container\ContainerInterface;
use Entities\Model\EventCommand;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class EventCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new EventCommand($container->get(AdapterInterface::class));
    }
}
