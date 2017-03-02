<?php
namespace Entities\Factory;

use Interop\Container\ContainerInterface;
use Entities\Model\UserCommand;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class UserCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new UserCommand($container->get(AdapterInterface::class));
    }
}
