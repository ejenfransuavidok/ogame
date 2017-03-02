<?php
namespace Universe\Factory;

use Interop\Container\ContainerInterface;
use Universe\Model\SputnikCommand;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class SputnikCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new SputnikCommand($container->get(AdapterInterface::class));
    }
}
