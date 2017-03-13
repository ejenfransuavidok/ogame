<?php
namespace Universe\Factory;

use Interop\Container\ContainerInterface;
use Universe\Model\PlanetTypeCommand;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class PlanetTypeCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new PlanetTypeCommand($container->get(AdapterInterface::class));
    }
}
