<?php



return [
    'bjyauthorize' => [
        'default_role' => 'guest',
        'identity_provider' => 'BjyAuthorize\Provider\Identity\ZfcUserZendDb',
        'role_providers' => [
            'BjyAuthorize\Provider\Role\ZendDb' => [
                'table'                 => 'user_role',
                'identifier_field_name' => 'id',
                'role_id_field'         => 'role_id',
                'parent_role_field'     => 'parent_id',
            ],
        ],

        'guards' => [
            'BjyAuthorize\Guard\Route'=> [
                ['route' => 'zfcuser', 'roles' => ['user']],
                ['route' => 'zfcuser/logout', 'roles' => ['user']],
                ['route' => 'zfcuser/login', 'roles' => ['guest']],
                ['route' => 'zfcuser/register', 'roles' => ['guest']],
                ['route' => 'zfcuser/verify_email', 'roles' => ['guest']],
                ['route' => 'scn-social-auth-user/login', 'roles' => ['guest']],
                ['route' => 'scn-social-auth-user/login/provider', 'roles' => ['guest']],
                ['route' => 'scn-social-auth-hauth', 'roles' => ['guest']],
                ['route' => 'scn-social-auth-user/authenticate/provider', 'roles' => ['guest']],
                ['route' => 'scn-social-auth-user', 'roles' => ['guest', 'user']],
                ['route' => 'scn-social-auth-user/logout', 'roles' => ['user']],

                // app routes
                ['route' => 'home', 'roles' => ['guest', 'user']],
                ['route' => 'verify-mail-sent', 'roles' => ['guest']],
                ['route' => 'activated', 'roles' => ['guest']],
            ],
        ],
    ],
];