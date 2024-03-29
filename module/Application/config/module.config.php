<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'home',
                    ],
                ],
            ],
            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action][/:id][/:status]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                        'id'     => '',
                        'status'     => '',
                    ],
                ],
            ],
            'application:menu' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application/menu[/:id][/:status]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'menu',
                        'id'     => '',
                        'status'     => '',
                    ],
                ],
            ],
            'application:kitchen-orders' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application/kitchen-orders[/:id][/:status]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'kitchenOrders',
                        'id'     => '',
                        'status'     => '',
                    ],
                ],
            ],
            'entry' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/entry[/:action][/:id][/:status]',
                    'defaults' => [
                        'controller' => Controller\EntryController::class,
                        'action'     => 'index',
                        'id'     => '',
                        'status'     => '',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\EntryController::class => InvokableFactory::class,
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
            'application/index/menu' => __DIR__ . '/../view/application/index/menu.phtml',
            'application/index/carticon' => __DIR__ . '/../view/application/index/cartitem.phtml',
            'application/index/cart' => __DIR__ . '/../view/application/index/cart.phtml',
            'application/index/menuitem' => __DIR__ . '/../view/application/index/menuitem.phtml',
            'application/index/orderitem' => __DIR__ . '/../view/application/index/orderitem.phtml',
            'application/index/orderstatus.phtml' => __DIR__ . '/../view/application/index/orderstatus.phtml',
            'application/index/status-table' => __DIR__ . '/../view/application/index/status-table.phtml',
            'application/index/kitchen-orders' => __DIR__ . '/../view/application/index/kitchen-orders.phtml',
            'application/index/order-name' => __DIR__ . '/../view/application/index/order-name.phtml',
            'entry/entryform' => __DIR__ . '/../view/application/entry/entryform.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
