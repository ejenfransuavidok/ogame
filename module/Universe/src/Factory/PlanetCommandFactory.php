<?php
namespace Universe\Factory;

use Interop\Container\ContainerInterface;
use Universe\Model\PlanetCommand;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class PlanetCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new PlanetCommand($container->get(AdapterInterface::class));
    }
}
