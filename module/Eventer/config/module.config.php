<?php

namespace Eventer;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [

    'service_manager' => [
        'aliases' => [
        ],
        'factories' => [
            \App\Controller\AuthController::class => \App\Factory\AuthControllerFactory::class
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\BuildingController::class => Factory\BuildingControllerFactory::class,
            Controller\JsreaderController::class => Factory\JsreaderControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'eventer' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/eventer',
                ],
                
                'may_terminate' => false,
                
                'child_routes'  => [
                
                    'building' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'options' => [
                            'route'    => '/building',
                            'defaults' => [
                                'controller' => Controller\BuildingController::class,
                                'action' => 'building',
                            ],
                        ],
                    ],
                    
                    'initbuild' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'options' => [
                            'route'    => '/initbuild',
                            'defaults' => [
                                'controller' => Controller\BuildingController::class,
                                'action' => 'initbuild',
                            ],
                        ],
                    ],
                    
                    'rejectbuilding' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'options' => [
                            'route'    => '/rejectbuilding',
                            'defaults' => [
                                'controller' => Controller\BuildingController::class,
                                'action' => 'rejectbuilding',
                            ],
                        ],
                    ],
                    
                    'jsreader' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'options' => [
                            'route'    => '/jsreader',
                            'defaults' => [
                                'controller' => Controller\JsreaderController::class,
                                'action' => 'jsreader',
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