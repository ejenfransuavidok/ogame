<?php
namespace Universe\Factory;

use Interop\Container\ContainerInterface;
use Universe\Model\StarCommand;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class StarCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new StarCommand($container->get(AdapterInterface::class));
    }
}
