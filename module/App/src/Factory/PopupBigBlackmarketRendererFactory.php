<?php

namespace App\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use App\Renderer\PopupBigBlackmarketRenderer;
use App\Controller\AuthController;
use Settings\Model\SettingsRepositoryInterface;
use Entities\Model\SourceRepository;
use Universe\Classes\PlanetCapacity;


class PopupBigBlackmarketRendererFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return ListController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new PopupBigBlackmarketRenderer(
            $container->get(AuthController::class),
            $container->get(SettingsRepositoryInterface::class),
            $container->get(SourceRepository::class),
            $container->get(PlanetCapacity::class)
        );
    }
}
