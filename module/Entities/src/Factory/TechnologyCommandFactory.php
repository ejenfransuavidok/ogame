<?php
namespace Entities\Factory;

use Interop\Container\ContainerInterface;
use Entities\Model\TechnologyCommand;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class TechnologyCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new TechnologyCommand($container->get(AdapterInterface::class));
    }
}
