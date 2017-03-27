<?php
namespace Cron\Factory;

use Interop\Container\ContainerInterface;
use Universe\Model\PlanetRepository;
use Universe\Model\PlanetCommand;
use Universe\Model\SputnikRepository;
use Entities\Model\UserRepository;
use Entities\Model\BuildingRepository;
use Entities\Model\BuildingCommand;
use Cron\Controller\ResourcesController;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ResourcesControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ResourcesController(
            $container->get(AdapterInterface::class),
            $container->get(PlanetRepository::class),
            $container->get(PlanetCommand::class),
            $container->get(SputnikRepository::class),
            $container->get(UserRepository::class),
            $container->get(BuildingRepository::class),
            $container->get(BuildingCommand::class)
        );
    }
}
