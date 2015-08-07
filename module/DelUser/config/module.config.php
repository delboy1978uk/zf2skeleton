<?php

return [
    'router' => [
        'routes' => [
            'activated' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/user/activated',
                    'defaults' => [
                        'controller' => 'PhlySimplePage\Controller\Page',
                        'template'   => 'del-user/activated',
                        // optionally set a specific layout for this page
                        //'layout'     => 'layout/some-layout',
                    ],
                ],
            ],
            'verify-mail-sent' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/user/thanks-for-registering',
                    'defaults' => [
                        'controller' => 'PhlySimplePage\Controller\Page',
                        'template'   => 'del-user/verify-mail-sent',
                    ],
                ],
            ],
            'forgot-password' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/user/forgot-password[/:email]',
                    'defaults' => [
                        'controller' => 'DelUser\Controller\Index',
                        'action' => 'forgot-password',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'DelUser\Controller\Index' => 'DelUser\Controller\IndexController'
        ],
    ],
    'view_manager' => [
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ],
];