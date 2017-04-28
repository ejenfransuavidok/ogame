<?php

namespace App\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Db\Adapter\AdapterInterface;
use App\Renderer\PopupFleet2Renderer;
use App\Controller\AuthController;
use Entities\Model\UserRepository;
use Entities\Model\UserCommand;
use Universe\Model\GalaxyRepository;
use Universe\Model\PlanetSystemRepository;
use Universe\Model\PlanetRepository;
use Entities\Model\BuildingRepository;
use Entities\Model\BuildingTypeRepository;
use Entities\Model\EventRepository;
use Entities\Model\SpaceSheepRepository;

class PopupFleet2RendererFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return ListController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new PopupFleet2Renderer(
            $container->get(AuthController::class),
            $container->get(GalaxyRepository::class),
            $container->get(PlanetSystemRepository::class),
            $container->get(PlanetRepository::class),
            $container->get(BuildingRepository::class),
            $container->get(BuildingTypeRepository::class),
            $container->get(EventRepository::class),
            $container->get(SpaceSheepRepository::class)
        );
    }
}
