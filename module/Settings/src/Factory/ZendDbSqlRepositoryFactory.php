<?php

namespace Settings\Factory;

use Interop\Container\ContainerInterface;
use Settings\Model\Setting;
use Settings\Model\ZendDbSqlRepository;
use Settings\Model\Hydrator\SettingsHydrator;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Zend\ServiceManager\Factory\FactoryInterface;

class ZendDbSqlRepositoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ZendDbSqlRepository(
            $container->get(AdapterInterface::class),
            new SettingsHydrator(),
            new Setting('', '', '', '')
        );
    }
}
