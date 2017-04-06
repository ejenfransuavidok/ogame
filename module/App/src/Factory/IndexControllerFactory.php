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
use Entities\Model\UserRepository;
use Entities\Model\UserCommand;
use Universe\Model\PlanetRepository;
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
            new AuthController(
                $container->get(AdapterInterface::class),
                $container->get(UserRepository::class),
                $container->get(UserCommand::class)
            ),
            $container->get(PlanetRepository::class),
            $container->get(BuildingRepository::class),
            $container->get(BuildingTypeRepository::class),
            $container->get(EventRepository::class),
            $container->get(EventCommand::class),
            $container->get(PopupFleet1Renderer::class),
            $container->get(PopupFleet2Renderer::class),
            $container->get(PopupFleet3Renderer::class)
        );
    }
}
