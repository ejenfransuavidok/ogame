<?php
namespace Universe\Factory;

use Interop\Container\ContainerInterface;
use Universe\Model\PlanetSystemCommand;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class PlanetSystemCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new PlanetSystemCommand($container->get(AdapterInterface::class));
    }
}
