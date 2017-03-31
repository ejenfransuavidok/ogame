<?php
namespace Cron\Factory;

use Interop\Container\ContainerInterface;
use Universe\Model\PlanetRepository;
use Universe\Model\PlanetCommand;
use Universe\Model\SputnikRepository;
use Universe\Model\SputnikCommand;
use Entities\Model\EventRepository;
use Entities\Model\EventCommand;
use Entities\Model\BuildingTypeRepository;
use Entities\Model\BuildingTypeCommand;
use Entities\Model\UserRepository;
use Entities\Model\UserCommand;
use Entities\Model\BuildingRepository;
use Entities\Model\BuildingCommand;
use Cron\Controller\IndexController;
use App\Controller\AuthController;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new IndexController(
            $container->get(AdapterInterface::class),
            $container->get(EventRepository::class),
            $container->get(EventCommand::class),
            $container->get(BuildingTypeRepository::class),
            $container->get(BuildingTypeCommand::class),
            $container->get(PlanetRepository::class),
            $container->get(PlanetCommand::class),
            $container->get(SputnikRepository::class),
            $container->get(SputnikCommand::class),
            $container->get(UserRepository::class),
            $container->get(UserCommand::class),
            $container->get(BuildingRepository::class),
            $container->get(BuildingCommand::class),
            $container->get(AuthController::class)
        );
    }
}
