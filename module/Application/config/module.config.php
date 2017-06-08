<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Album\Controller\AlbumController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => \Zend\Router\Http\Literal::class,
                'options' => [
                    'route'    => '/working',
                    'defaults' => [
                        'controller' => Controller\IndexController::class, // <-- change here
                        'action'     => 'index',
                    ],
                ],
            ],
            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
    'navigation' => [
        'default' => [
            [
                'label' => 'Главная',
                'route' => 'home',
            ],
            [
                'label' => 'Настройки',
                'route' => 'settings',
                'pages' => [
                    [
                        'label'  => 'Добавить',
                        'route'  => 'settings/add',
                        'action' => 'add',
                    ],
                    [
                        'label'  => 'Редактировать',
                        'route'  => 'settings/edit',
                        'action' => 'edit',
                    ],
                    [
                        'label'  => 'Удалить',
                        'route'  => 'settings/delete',
                        'action' => 'delete',
                    ],
                    [
                        'label'  => 'Детальный просмотр',
                        'route'  => 'settings/detail',
                        'action' => 'detail',
                    ],
                ],
            ],
            [
                'label' => 'Генерация вселенной',
                'route' => 'universe',
            ],
            [
                'label' => 'Генерация сущностей',
                'route' => 'entities',
            ],
            [
                'label' => 'Тестовые полеты',
                'route' => 'flight',
            ],
            [
                'label' => 'Мои здания',
                'route' => 'flight/buildings',
            ],
        ],
    ],
];
