<?php
namespace Eventer\Factory;

use Interop\Container\ContainerInterface;
use App\Controller\AuthController;
use Entities\Model\UserRepository;
use Entities\Model\UserCommand;
use Entities\Model\SourceRepository;
use Entities\Model\Source;
use Universe\Model\PlanetRepository;
use Universe\Model\PlanetCommand;
use Universe\Model\SputnikRepository;
use Universe\Model\SputnikCommand;
use Universe\Classes\PlanetCapacity;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Settings\Model\SettingsRepositoryInterface;
use Eventer\Controller\BuysourcesController;



class BuysourcesControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new BuysourcesController(
            $container->get(AdapterInterface::class),
            $container->get(AuthController::class),
            $container->get(UserRepository::class),
            $container->get(UserCommand::class),
            $container->get(PlanetRepository::class),
            $container->get(PlanetCommand::class),
            $container->get(SputnikRepository::class),
            $container->get(SputnikCommand::class),
            $container->get(SettingsRepositoryInterface::class),
            $container->get(SourceRepository::class),
            $container->get(PlanetCapacity::class)
        );
    }
}
