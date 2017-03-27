<?php
namespace Entities\Factory;

use Interop\Container\ContainerInterface;
use Entities\Model\BuildingTypeCommand;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class BuildingTypeCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new BuildingTypeCommand($container->get(AdapterInterface::class));
    }
}
