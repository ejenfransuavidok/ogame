<?php
namespace Eventer\Factory;

use Interop\Container\ContainerInterface;
use App\Controller\AuthController;
use Entities\Model\EventRepository;
use Entities\Model\EventCommand;
use Entities\Model\UserRepository;
use Entities\Model\UserCommand;
use Entities\Model\BuildingRepository;
use Entities\Model\BuildingCommand;
use Entities\Model\BuildingTypeRepository;
use Entities\Model\BuildingTypeCommand;
use Universe\Model\StarRepository;
use Universe\Model\StarCommand;
use Universe\Model\PlanetRepository;
use Universe\Model\PlanetCommand;
use Universe\Model\SputnikRepository;
use Universe\Model\SputnikCommand;
use Eventer\Controller\BuildingController;
use Eventer\Processor\BuildingProcessor;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class BuildingControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new BuildingController(
            $container->get(AdapterInterface::class),
            $container->get(AuthController::class),
            $container->get(EventRepository::class),
            $container->get(EventCommand::class),
            $container->get(UserRepository::class),
            $container->get(UserCommand::class),
            $container->get(BuildingRepository::class),
            $container->get(BuildingCommand::class),
            $container->get(BuildingTypeRepository::class),
            $container->get(BuildingTypeCommand::class),
            $container->get(StarRepository::class),
            $container->get(StarCommand::class),
            $container->get(PlanetRepository::class),
            $container->get(PlanetCommand::class),
            $container->get(SputnikRepository::class),
            $container->get(SputnikCommand::class),
            $container->get(BuildingProcessor::class)
        );
    }
}
