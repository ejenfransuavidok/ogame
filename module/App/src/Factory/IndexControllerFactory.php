<?php

namespace App\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Db\Adapter\AdapterInterface;
use App\Controller\IndexController;
use App\Controller\AuthController;
use App\Renderer\PopupFleet1Renderer;
use App\Renderer\PopupFleet2Renderer;
use App\Renderer\PopupFleet3Renderer;
use App\Renderer\FleetMoovingActivity;
use Entities\Model\UserRepository;
use Entities\Model\UserCommand;
use Universe\Classes\PlanetCapacity;
use Universe\Model\PlanetRepository;
use Universe\Model\PlanetCommand;
use Entities\Model\BuildingRepository;
use Entities\Model\BuildingTypeRepository;
use Entities\Model\EventCommand;
use Entities\Model\EventRepository;

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
            $container->get(AuthController::class),
            $container->get(PlanetCapacity::class),
            $container->get(BuildingRepository::class),
            $container->get(BuildingTypeRepository::class),
            $container->get(EventRepository::class),
            $container->get(EventCommand::class),
            $container->get(PopupFleet1Renderer::class),
            $container->get(PopupFleet2Renderer::class),
            $container->get(PopupFleet3Renderer::class),
            $container->get(FleetMoovingActivity::class),
            $container->get(PlanetRepository::class),
            $container->get(PlanetCommand::class)
        );
    }
}
