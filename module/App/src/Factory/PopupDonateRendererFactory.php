<?php

namespace App\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use App\Renderer\PopupDonateRenderer;
use App\Controller\AuthController;
use Settings\Model\SettingsRepositoryInterface;

class PopupDonateRendererFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return ListController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new PopupDonateRenderer(
            $container->get(AuthController::class),
            $container->get(SettingsRepositoryInterface::class)
        );
    }
}
