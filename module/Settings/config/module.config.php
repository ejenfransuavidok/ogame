<?php

namespace Settings;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    /*
     * контроллер Controller\ListController::class будет искаться на Фабрике
     * объект Zend\ServiceManager\Factory\InvokableFactory будет искать его в
     * контроллерах (папка controller)
     * после переделки - указываем прямой путь к классу Factory\ListControllerFactory::class,
     * который создает новый экземпляр класса Controller\ListController
     */
    'service_manager' => [
        'aliases' => [
            Model\SettingsRepositoryInterface::class => Model\ZendDbSqlRepository::class,
            Model\SettingsCommandInterface::class => Model\ZendDbSqlCommand::class,
        ],
        'factories' => [
            Model\SettingsRepository::class => InvokableFactory::class,
            Model\SettingsTypeofRepositoryInterface::class => Factory\SettingsTypeofRepositoryFactory::class,
            Model\ZendDbSqlRepository::class => Factory\ZendDbSqlRepositoryFactory::class,
            Model\SettingsCommand::class => InvokableFactory::class,
            Model\ZendDbSqlCommand::class => Factory\ZendDbSqlCommandFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\ListController::class => Factory\ListControllerFactory::class,
            Controller\WriteController::class => Factory\WriteControllerFactory::class,
            Controller\DeleteController::class => Factory\DeleteControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'settings' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/settings',
                    'defaults' => [
                        'controller' => Controller\ListController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'detail' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/detail/:id',
                            'defaults' => [
                                'action' => 'detail',
                            ],
                            'constraints' => [
                                'id' => '[1-9]\d*',
                            ],
                        ],
                    ],
                    'add' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/add',
                            'defaults' => [
                                'controller' => Controller\WriteController::class,
                                'action'     => 'add',
                            ],
                        ],
                    ],
                    'edit' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/edit/:id',
                            'defaults' => [
                                'controller' => Controller\WriteController::class,
                                'action'     => 'edit',
                            ],
                            'constraints' => [
                                'id' => '[1-9]\d*',
                            ],
                        ],
                    ],
                    'delete' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/delete/:id',
                            'defaults' => [
                                'controller' => Controller\DeleteController::class,
                                'action'     => 'delete',
                            ],
                            'constraints' => [
                                'id' => '[1-9]\d*',
                            ],
                        ],
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
