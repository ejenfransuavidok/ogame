<?php

namespace App\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Db\Adapter\AdapterInterface;
use App\Controller\AuthController;
use Entities\Model\UserRepository;
use Entities\Model\UserCommand;

class AuthControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new AuthController(
            $container->get(AdapterInterface::class),
            $container->get(UserRepository::class),
            $container->get(UserCommand::class)
        );
    }
}
