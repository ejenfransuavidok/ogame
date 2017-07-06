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
            Renderer\FleetMoovingActivity::class => Factory\FleetMoovingActivityFactory::class,
            Renderer\PopupBlackmarketRenderer::class => Factory\PopupBlackmarketRendererFactory::class,
            Renderer\PopupBigBlackmarketRenderer::class => Factory\PopupBigBlackmarketRendererFactory::class,
            Renderer\PopupBuyRenderer::class => Factory\PopupBuyRendererFactory::class,
            Renderer\PopupDonateRenderer::class => Factory\PopupDonateRendererFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Factory\IndexControllerFactory::class,
            Controller\AuthController::class => Factory\AuthControllerFactory::class,
            Controller\FleetSetupTargetController::class => Factory\FleetSetupTargetControllerFactory::class,
            Controller\FleetLauncherController::class => Factory\FleetLauncherControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'app' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                    /**
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    */
                        'controller' => Controller\AuthController::class,
                        'action' => 'auth',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    
                    'planet' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'options' => [
                            'route'    => 'planet/:planetid',
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'index',
                            ],
                            'constraints' => [
                                'planetid' => '\d+',
                            ],
                        ],
                    ],
                 /*   
                    'auth' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'options' => [
                            'route'    => 'auth',
                            'defaults' => [
                                'controller' => Controller\AuthController::class,
                                'action' => 'auth',
                            ],
                        ],
                    ],
                 */   
                    'doauth' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'options' => [
                            'route'    => 'doauth',
                            'defaults' => [
                                'controller' => Controller\AuthController::class,
                                'action' => 'doauth',
                            ],
                        ],
                    ],
                    
                    'dologout' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'options' => [
                            'route'    => 'dologout',
                            'defaults' => [
                                'controller' => Controller\AuthController::class,
                                'action' => 'dologout',
                            ],
                        ],
                    ],
                    
                    'doregister' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'options' => [
                            'route'    => 'doregister',
                            'defaults' => [
                                'controller' => Controller\AuthController::class,
                                'action' => 'doregister',
                            ],
                        ],
                    ],
                    
                    'planetkeep' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'options' => [
                            'route'    => 'planetkeep',
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'planetkeep',
                            ],
                        ],
                    ],
                    
                    'setuptarget' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'options' => [
                            'route'    => 'setuptarget',
                            'defaults' => [
                                'controller' => Controller\FleetSetupTargetController::class,
                                'action' => 'setuptarget',
                            ],
                        ],
                    ],
                    
                    'fleetlaunch' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'options' => [
                            'route'    => 'fleetlaunch',
                            'defaults' => [
                                'controller' => Controller\FleetLauncherController::class,
                                'action' => 'fleetlaunch',
                            ],
                        ],
                    ],
                    
                    'fleetforwardpopupupdater' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'options' => [
                            'route'    => 'fleetforwardpopupupdater',
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'fleetforwardpopupupdater',
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
    'translator' => [
        'locale' => 'en',
        'translation_file_patterns' => [
            [
                'type'     => 'phparray',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => 'lang.array.en.php',
            ],
        ],
    ],
];
