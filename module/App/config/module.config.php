<?php

namespace App;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [

    'service_manager' => [
        'aliases' => [
        ],
        'factories' => [
            Renderer\PopupFleet1Renderer::class => Factory\PopupFleet1RendererFactory::class,
            Renderer\PopupFleet2Renderer::class => Factory\PopupFleet2RendererFactory::class,
            Renderer\PopupFleet3Renderer::class => Factory\PopupFleet3RendererFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Factory\IndexControllerFactory::class,
            Controller\AuthController::class => Factory\AuthControllerFactory::class,
            Controller\FleetSetupTargetController::class => Factory\FleetSetupTargetControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'app' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/app',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    
                    'planet' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'options' => [
                            'route'    => '/planet/:planetid',
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'index',
                            ],
                            'constraints' => [
                                'planetid' => '\d+',
                            ],
                        ],
                    ],
                    
                    'auth' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'options' => [
                            'route'    => '/auth',
                            'defaults' => [
                                'controller' => Controller\AuthController::class,
                                'action' => 'auth',
                            ],
                        ],
                    ],
                    
                    'doauth' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'options' => [
                            'route'    => '/doauth',
                            'defaults' => [
                                'controller' => Controller\AuthController::class,
                                'action' => 'doauth',
                            ],
                        ],
                    ],
                    
                    'doregister' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'options' => [
                            'route'    => '/doregister',
                            'defaults' => [
                                'controller' => Controller\AuthController::class,
                                'action' => 'doregister',
                            ],
                        ],
                    ],
                    
                    'planetkeep' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'options' => [
                            'route'    => '/planetkeep',
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'planetkeep',
                            ],
                        ],
                    ],
                    
                    'setuptarget' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'options' => [
                            'route'    => '/setuptarget',
                            'defaults' => [
                                'controller' => Controller\FleetSetupTargetController::class,
                                'action' => 'setuptarget',
                            ],
                        ],
                    ],
                    
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_map' => [
            'app/layout'           => __DIR__ . '/../view/layout/layout.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
