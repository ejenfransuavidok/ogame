<?php
namespace Universe\Factory;

use Interop\Container\ContainerInterface;
use Universe\Model\StarTypeCommand;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class StarTypeCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new StarTypeCommand($container->get(AdapterInterface::class));
    }
}
