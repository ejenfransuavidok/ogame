<?php
namespace Universe\Factory;

use Interop\Container\ContainerInterface;
use Universe\Model\GalaxyCommand;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class GalaxyCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new GalaxyCommand($container->get(AdapterInterface::class));
    }
}
