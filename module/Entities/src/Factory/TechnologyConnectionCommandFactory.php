<?php
namespace Entities\Factory;

use Interop\Container\ContainerInterface;
use Entities\Model\TechnologyCommand;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Entities\Model\TechnologyConnectionCommand;

class TechnologyConnectionCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new TechnologyConnectionCommand($container->get(AdapterInterface::class));
    }
}
