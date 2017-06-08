<?php

namespace Cron\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Cron\Classes\Cbr;
use Settings\Model\SettingsRepositoryInterface;
use Settings\Model\SettingsCommandInterface;

class CbrFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return ListController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Cbr(
            $container->get(SettingsRepositoryInterface::class),
            $container->get(SettingsCommandInterface::class)
        );
    }
}
