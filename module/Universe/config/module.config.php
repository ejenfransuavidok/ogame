<?php

namespace Universe;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Universe\Controller\IndexController;

return [

    'service_manager' => [
        'aliases' => [
        ],
        'factories' => [
            Model\GalaxyCommand::class => Factory\GalaxyCommandFactory::class,
            Model\PlanetSystemCommand::class => Factory\PlanetSystemCommandFactory::class,
            Model\StarCommand::class => Factory\StarCommandFactory::class,
            Model\PlanetCommand::class => Factory\PlanetCommandFactory::class,
            Model\SputnikCommand::class => Factory\SputnikCommandFactory::class,
            Model\StarTypeCommand::class => Factory\StarTypeCommandFactory::class,
            Model\StarTypeRepository::class => Factory\StarTypeRepositoryFactory::class,
            Model\PlanetTypeCommand::class => Factory\PlanetTypeCommandFactory::class,
            Model\PlanetTypeRepository::class => Factory\PlanetTypeRepositoryFactory::class,
            Model\GalaxyRepository::class => Factory\GalaxyRepositoryFactory::class,
            Model\PlanetSystemRepository::class => Factory\PlanetSystemRepositoryFactory::class,
            Model\StarRepository::class => Factory\StarRepositoryFactory::class,
            Model\PlanetRepository::class => Factory\PlanetRepositoryFactory::class,
            Model\SputnikRepository::class => Factory\SputnikRepositoryFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\LoggerController::class => InvokableFactory::class,
            Controller\CreatorController::class => Factory\CreatorControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'universe' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/worldgen',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                ],
            ],
            'logger' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/logger',
                    'defaults' => [
                        'controller' => Controller\LoggerController::class,
                        'action'     => 'logger',
                    ],
                ],
            ],
            'creator' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/creator',
                    'defaults' => [
                        'controller' => Controller\CreatorController::class,
                        'action'     => 'creator',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
