<?php

namespace Entities\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Entities\Model\TechnologyRepository;
use Entities\Controller\TechLoadController;
use Entities\Model\TechnologyCommand;
use Entities\Model\TechnologyConnectionCommand;
use Entities\Model\SpaceSheepCommand;
use Entities\Model\UserRepository;
use Entities\Model\BuildingCommand;
use Entities\Model\BuildingTypeCommand;
use Entities\Model\BuildingTypeRepository;
use Universe\Model\GalaxyRepository;
use Universe\Model\PlanetSystemRepository;
use Universe\Model\StarRepository;
use Universe\Model\PlanetRepository;
use Universe\Model\SputnikRepository;


class TechLoadControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return ListController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new TechLoadController(
            $container->get(TechnologyRepository::class),
            $container->get(TechnologyCommand::class),
            $container->get(TechnologyConnectionCommand::class),
            $container->get(SpaceSheepCommand::class),
            $container->get(GalaxyRepository::class),
            $container->get(PlanetSystemRepository::class),
            $container->get(StarRepository::class),
            $container->get(PlanetRepository::class),
            $container->get(SputnikRepository::class),
            $container->get(UserRepository::class),
            $container->get(BuildingCommand::class),
            $container->get(BuildingTypeCommand::class),
            $container->get(BuildingTypeRepository::class)
        );
    }
}
