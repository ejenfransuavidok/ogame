<?php

namespace Eventer;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use App\Controller\AuthController;
use App\Factory\AuthControllerFactory;

return [

    'service_manager' => [
        'aliases' => [
        ],
        'factories' => [
            AuthController::class => AuthControllerFactory::class,
            Processor\BuildingProcessor::class => Factory\BuildingProcessorFactory::class,
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
                    
                    'finishfordonatebuilding' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'options' => [
                            'route'    => '/finishfordonatebuilding',
                            'defaults' => [
                                'controller' => Controller\BuildingController::class,
                                'action' => 'finishfordonatebuilding',
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
