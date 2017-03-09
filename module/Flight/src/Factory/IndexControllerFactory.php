<?php

namespace Flight\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Db\Adapter\AdapterInterface;
use Flight\Controller\IndexController;
use Entities\Model\UserRepository;
use Entities\Model\UserCommand;
use Entities\Model\SpaceSheepRepository;
use Entities\Model\SpaceSheepCommand;
use Universe\Model\GalaxyRepository;
use Universe\Model\PlanetSystemRepository;
use Universe\Model\PlanetRepository;
use Universe\Model\SputnikRepository;
use Universe\Model\StarRepository;
use Settings\Model\SettingsRepositoryInterface;


class IndexControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return ListController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new IndexController(
            $container->get(AdapterInterface::class),
            $container->get(UserRepository::class),
            $container->get(UserCommand::class),
            $container->get(GalaxyRepository::class),
            $container->get(PlanetSystemRepository::class),
            $container->get(PlanetRepository::class),
            $container->get(SputnikRepository::class),
            $container->get(StarRepository::class),
            $container->get(SpaceSheepCommand::class),
            $container->get(SpaceSheepRepository::class),
            $container->get(SettingsRepositoryInterface::class)
        );
    }
}
