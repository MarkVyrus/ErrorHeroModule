<?php

namespace ErrorHeroModule;

use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Log;

return [

    'controllers' => [
        'factories' => [
            Controller\ErrorPreviewController::class => InvokableFactory::class,
        ],
    ],

    'router' => [
        'routes' => [
            'newsletter' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/error-preview[/:action]',
                    'defaults' => [
                        'controller' => Controller\ErrorPreviewController::class,
                        'action' => 'exception',
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'abstract_factories' => [
            Log\LoggerAbstractServiceFactory::class,
        ],
        'factories' => [
            Listener\Mvc::class => Listener\MvcFactory::class,
            Handler\Logging::class => Handler\LoggingFactory::class,
        ],
    ],

    'view_manager' => [
        'template_map' => [
           'error-hero-module/error-default' => __DIR__.'/../view/error-hero-module/error-default.phtml',
       ],
    ],

];
