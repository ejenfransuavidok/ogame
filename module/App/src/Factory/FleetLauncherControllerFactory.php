<?php

namespace App\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Db\Adapter\AdapterInterface;
use App\Controller\FleetLauncherController;
use App\Controller\AuthController;
use Entities\Model\SpaceSheepRepository;
use Entities\Model\SpaceSheepCommand;
use Entities\Model\UserRepository;
use Entities\Model\UserCommand;
use Universe\Model\GalaxyRepository;
use Universe\Model\PlanetSystemRepository;
use Universe\Model\PlanetRepository;
use Entities\Model\EventCommand;
use Entities\Model\EventRepository;
use Flight\Classes\FlightCalculator;

class FleetLauncherControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return ListController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new FleetLauncherController(
            $container->get(AdapterInterface::class),
            new AuthController(
                $container->get(AdapterInterface::class),
                $container->get(UserRepository::class),
                $container->get(UserCommand::class)
            ),
            $container->get(SpaceSheepRepository::class),
            $container->get(SpaceSheepCommand::class),
            $container->get(GalaxyRepository::class),
            $container->get(PlanetSystemRepository::class),
            $container->get(PlanetRepository::class),
            $container->get(EventRepository::class),
            $container->get(EventCommand::class),
            $container->get(FlightCalculator::class)
        );
    }
}
