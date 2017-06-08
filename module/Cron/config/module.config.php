<?php

namespace Cron;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [

    'service_manager' => [
        'aliases' => [
        ],
        'factories' => [
            \App\Controller\AuthController::class => \App\Factory\AuthControllerFactory::class,
            Classes\Cbr::class => Factory\CbrFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Factory\IndexControllerFactory::class,
            Controller\ResourcesController::class => Factory\ResourcesControllerFactory::class,
            Controller\UpdaterController::class => Factory\UpdaterControllerFactory::class,
            Controller\CbrController::class => Factory\CbrControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'cron' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/cron',
                ],
                
                'may_terminate' => false,
                
                'child_routes'  => [
                
                    'planet' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'options' => [
                            'route'    => '/srccalc',
                            'defaults' => [
                                'controller' => Controller\ResourcesController::class,
                                'action' => 'srccalc',
                            ],
                        ],
                    ],
                    
                    'updater' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/updater'
                        ],
                        
                        'may_terminate' => false,
                        
                        'child_routes'  => [
                            
                            'index' => [
                                'type' => \Zend\Router\Http\Segment::class,
                                'options' => [
                                    'route' => '/index',
                                    'defaults' => [
                                        'controller' => Controller\IndexController::class,
                                        'action' => 'index',
                                    ],
                                ],
                            ],
                            
                            'srcupdater' => [
                                'type' => \Zend\Router\Http\Segment::class,
                                'options' => [
                                    'route'    => '/srcupdater',
                                    'defaults' => [
                                        'controller' => Controller\UpdaterController::class,
                                        'action' => 'srcupdater',
                                     ],
                                ],
                            ],
                            
                            'cbr' => [
                                'type' => \Zend\Router\Http\Segment::class,
                                'options' => [
                                    'route'    => '/cbr',
                                    'defaults' => [
                                        'controller' => Controller\CbrController::class,
                                        'action' => 'cbr',
                                     ],
                                ],
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
