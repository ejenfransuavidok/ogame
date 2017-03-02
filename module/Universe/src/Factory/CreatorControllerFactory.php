<?php

namespace Universe\Factory;

use Universe\Controller\CreatorController;
use Universe\Model\GalaxyCommand;
use Universe\Model\PlanetSystemCommand;
use Universe\Model\StarCommand;
use Universe\Model\PlanetCommand;
use Universe\Model\SputnikCommand;
use Universe\Model\StarTypeCommand;
use Universe\Model\StarTypeRepository;
use Universe\Model\GalaxyRepository;
use Universe\Model\PlanetSystemRepository;
use Universe\Model\StarRepository;
use Universe\Model\PlanetRepository;
use Universe\Model\SputnikRepository;
use Settings\Model\SettingsRepositoryInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class CreatorControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return ListController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new CreatorController(
            $container->get(SettingsRepositoryInterface::class), 
            $container->get(GalaxyCommand::class),
            $container->get(PlanetSystemCommand::class),
            $container->get(StarCommand::class),
            $container->get(PlanetCommand::class),
            $container->get(SputnikCommand::class),
            $container->get(StarTypeCommand::class),
            $container->get(StarTypeRepository::class),
            $container->get(GalaxyRepository::class),
            $container->get(PlanetSystemRepository::class),
            $container->get(StarRepository::class),
            $container->get(PlanetRepository::class),
            $container->get(SputnikRepository::class)
        );
    }
}
