<?php

namespace App\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use App\Renderer\PopupBuyRenderer;
use App\Controller\AuthController;
use Settings\Model\SettingsRepositoryInterface;

class PopupBuyRendererFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return ListController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new PopupBuyRenderer(
            $container->get(AuthController::class),
            $container->get(SettingsRepositoryInterface::class)
        );
    }
}
