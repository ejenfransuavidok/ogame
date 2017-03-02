<?php
namespace Settings\Factory;

use Settings\Controller\WriteController;
use Settings\Form\SettingsForm;
use Settings\Model\SettingsCommandInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Settings\Model\SettingsRepositoryInterface;
use Settings\Model\SettingsTypeofRepositoryInterface;

class WriteControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return WriteController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $formManager = $container->get('FormElementManager');
        return new WriteController(
            $container->get(SettingsCommandInterface::class),
            $formManager->get(SettingsForm::class),
            $container->get(SettingsRepositoryInterface::class),
            $container->get(SettingsTypeofRepositoryInterface::class)
        );
    }
}
