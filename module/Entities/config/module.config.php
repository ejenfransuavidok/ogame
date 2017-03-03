<?php

namespace Entities;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Entities\Controller\IndexController;

return [

    'service_manager' => [
        'aliases' => [
        ],
        'factories' => [
            Model\TechnologyCommand::class => Factory\TechnologyCommandFactory::class,
            Model\TechnologyRepository::class => Factory\TechnologyRepositoryFactory::class,
            Model\ConditionCommand::class => Factory\ConditionCommandFactory::class,
            Model\ConditionRepository::class => Factory\ConditionRepositoryFactory::class,
            Model\TechnologyConnectionCommand::class => Factory\TechnologyConnectionCommandFactory::class,
            Model\TechnologyConnectionRepository::class => Factory\TechnologyConnectionRepositoryFactory::class,
            Model\SpaceSheepCommand::class => Factory\SpaceSheepCommandFactory::class,
            Model\SpaceSheepRepository::class => Factory\SpaceSheepRepositoryFactory::class,
            Model\UserCommand::class => Factory\UserCommandFactory::class,
            Model\UserRepository::class => Factory\UserRepositoryFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Factory\IndexControllerFactory::class,
            Controller\TechLoadController::class => Factory\TechLoadControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'entities' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/entities',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                ],
            ],
            'techload' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/techload',
                    'defaults' => [
                        'controller' => Controller\TechLoadController::class,
                        'action'     => 'techload',
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
