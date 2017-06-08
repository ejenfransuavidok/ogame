<?php
namespace Eventer\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use App\Controller\AuthController;
use Entities\Model\EventRepository;
use Entities\Model\EventCommand;
use Entities\Model\BuildingTypeRepository;
use Entities\Model\BuildingTypeCommand;
use Universe\Model\PlanetRepository;
use Universe\Model\PlanetCommand;
use Eventer\Processor\BuildingProcessor;
use Entities\Model\BuildingRepository;

class BuildingProcessorFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new BuildingProcessor(
            $container->get(AuthController::class),
            $container->get(EventRepository::class),
            $container->get(EventCommand::class),
            $container->get(PlanetRepository::class),
            $container->get(PlanetCommand::class),
            $container->get(BuildingTypeRepository::class),
            $container->get(BuildingTypeCommand::class),
            $container->get(BuildingRepository::class)
        );
    }
}
