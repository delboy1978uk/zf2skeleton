<?php

return [
    'router' => [
        'routes' => [
            'zfcuser' => [
                'child_routes' => [
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