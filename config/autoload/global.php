<?php

/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */
return [
    'apigility' => [
        'config' => [
            'url' => 'http://11.35.100.48:8001/apps/ws/died_wsv5/public/',
            'grant_type' => 'password',
            'client_id' => 'testclient',
            'client_secret' => 'testpass',
            'debug' => false
//            'auth_url' => 'auth',
        ],
    ],
    'soap' => [
        'config' => [
            'url' => 'http://192.168.210.187:8080/prjMINEDUPP/ReniecWS?wsdl',
            'username' => 'USRDIEDDES',
            'password' => '3aad29b8',
            'ip' => '192.168.210.103',
            'debug' => false
        ],
    ],
    'auth' => [
        'mode' => 'oauth', // oauth => 'Apigility' -/- local => 'en su propia bd'
        'app_id' => 99,
        'acl_id' => 0,
        'multirole' => 0,
    ],
    'module_layouts' => [
        'Application' => 'layout/layout.phtml',
        'Usuario' => 'layout/layout_usuario.phtml',
        'Dashboard' => 'layout/layout_admin.phtml'
    ],
    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy',
        ]
    ]
];
