<?php

namespace Flight\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Db\Adapter\AdapterInterface;
use Flight\Controller\BuildingController;
use Entities\Model\UserRepository;
use Entities\Model\UserCommand;
use Entities\Model\BuildingRepository;
use Entities\Model\BuildingCommand;
use Universe\Model\PlanetRepository;
use Universe\Model\PlanetCommand;
use Settings\Model\SettingsRepositoryInterface;


class BuildingControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return ListController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new BuildingController(
            $container->get(AdapterInterface::class),
            $container->get(UserRepository::class),
            $container->get(UserCommand::class),
            $container->get(PlanetRepository::class),
            $container->get(PlanetCommand::class),
            $container->get(SettingsRepositoryInterface::class),
            $container->get(BuildingCommand::class),
            $container->get(BuildingRepository::class)
        );
    }
}
