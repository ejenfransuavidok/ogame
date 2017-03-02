<?php
namespace Entities\Factory;

use Interop\Container\ContainerInterface;
use Entities\Model\SpaceSheepCommand;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class SpaceSheepCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new SpaceSheepCommand($container->get(AdapterInterface::class));
    }
}
