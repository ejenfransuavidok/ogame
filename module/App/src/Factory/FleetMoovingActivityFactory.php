<?php

namespace App\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Db\Adapter\AdapterInterface;
use App\Renderer\FleetMoovingActivity;
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

class FleetMoovingActivityFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return ListController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new FleetMoovingActivity(
            $container->get(AdapterInterface::class),
            $container->get(AuthController::class),
            $container->get(SpaceSheepRepository::class),
            $container->get(SpaceSheepCommand::class),
            $container->get(GalaxyRepository::class),
            $container->get(PlanetSystemRepository::class),
            $container->get(PlanetRepository::class),
            $container->get(EventRepository::class),
            $container->get(EventCommand::class)
        );
    }
}
