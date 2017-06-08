<?php
namespace Cron\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Cron\Controller\CbrController;
use Cron\Classes\Cbr;

class CbrControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new CbrController(
            $container->get(Cbr::class)
        );
    }
}
