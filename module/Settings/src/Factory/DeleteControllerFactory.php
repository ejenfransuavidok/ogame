<?php
namespace Settings\Factory;

use Settings\Controller\DeleteController;
use Settings\Model\SettingsCommandInterface;
use Settings\Model\SettingsRepositoryInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class DeleteControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return DeleteController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new DeleteController(
            $container->get(SettingsCommandInterface::class),
            $container->get(SettingsRepositoryInterface::class)
        );
    }
}
