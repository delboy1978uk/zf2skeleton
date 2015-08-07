<?php

return [
    'router' => [
        'routes' => [
            'zfcuser' => [
                'child_routes' => [
                    'verify-mail-sent' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/thanks-for-registering',
                            'defaults' => [
                                'controller' => 'PhlySimplePage\Controller\Page',
                                'template'   => 'del-user/user/verify-mail-sent',
                            ],
                        ],
                    ],
                    'activated' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/activated',
                            'defaults' => [
                                'controller' => 'PhlySimplePage\Controller\Page',
                                'template'   => 'del-user/user/activated',
                                // optionally set a specific layout for this page
                                //'layout'     => 'layout/some-layout',
                            ],
                        ],
                    ],
                    'forgot-password' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/forgot-password/:email',
                            'defaults' => [
                                'controller' => 'zfcuser',
                                'action' => 'forgot-password',
                            ],
                        ],
                    ],
                    'reset-password' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/reset-password/:id/:token',
                            'defaults' => [
                                'controller' => 'zfcuser',
                                'action' => 'reset-password',
                            ],
                        ],
                    ],
                    'password-updated' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/password-updated',
                            'defaults' => [
                                'controller' => 'PhlySimplePage\Controller\Page',
                                'template'   => 'del-user/user/password-updated',
                            ],
                        ],
                    ],
                ],
            ],

        ],
    ],
    'view_manager' => [
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ],
];