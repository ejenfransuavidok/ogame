<?php

namespace Flight;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Flight\Controller\IndexController;

return [

    'service_manager' => [
        'aliases' => [
        ],
        'factories' => [
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Factory\IndexControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'flight' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/flight',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                
                    'authorize' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'options' => [
                            'route'    => '/authorize',
                            'defaults' => [
                                'action' => 'authorize',
                            ],
                        ],
                    ],    
                
                    'update_selectors' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'options' => [
                            'route'    => '/update_selectors',
                            'defaults' => [
                                'action' => 'update',
                            ],
                        ],
                    ],
                    
                    'calc' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'options' => [
                            'route'    => '/calc',
                            'defaults' => [
                                'action' => 'calc',
                            ],
                        ],
                    ],
                    
                    'start' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'options' => [
                            'route'    => '/start',
                            'defaults' => [
                                'action' => 'start',
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
