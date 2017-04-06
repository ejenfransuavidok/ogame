<?php

namespace Flight\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Entities\Model\SpaceSheepRepository;
use Entities\Model\EventRepository;
use Universe\Model\GalaxyRepository;
use Universe\Model\PlanetSystemRepository;
use Universe\Model\PlanetRepository;
use Universe\Model\SputnikRepository;
use Universe\Model\StarRepository;
use Settings\Model\SettingsRepositoryInterface;
use Flight\Classes\FlightCalculator;
use App\Controller\AuthController;


class FlightCalculatorFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return ListController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new FlightCalculator(
            $container->get(AuthController::class),
            $container->get(SpaceSheepRepository::class),
            $container->get(EventRepository::class),
            $container->get(GalaxyRepository::class),
            $container->get(PlanetSystemRepository::class),
            $container->get(StarRepository::class),
            $container->get(PlanetRepository::class),
            $container->get(SputnikRepository::class),
            $container->get(SettingsRepositoryInterface::class)
        );
    }
}
