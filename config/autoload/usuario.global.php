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
    'usuario' => [
        'captcha' => [
            'enabled' => true,
            'diccionario' => 'A', //'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'tamano_codigo' => 1 //5
        ],
        'login' => [
            'tamanio' => [
                'caracteres_min_clave' => 8,
                'caracteres_max_clave' => 15,
                'caracteres_min_dni' => 8,
                'caracteres_max_dni' => 12,
                'caracteres_max_correo' => 150
            ]
        ],
        'perfil' => [
            'num_celular' => 9,
            'telefono_fijo' => 10,
            'email_alterno' => 150,
            'contacto_nombres' => 150,
        ],
        'recuperacion' => [
            'ultimas_claves' => 1,
            'diccionario_clave' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuwxyz1234567890@)(',
            'opciones_recuperar' => [
                'sms' => true
            ],
            'templates' => [
                'template_email_id' => 11, //  ID del Template de Emaile App
                'template_recuperacion_id' => 11, //  ID del Template de Email
                'template_cambio_clave_id' => 12, //  ID del Template de Email
                'template_cambio_correo_id' => 14, //  ID del Template de Email
            ],
            'template_cambio_clave_id' => 1,
            'correo_confirmacion_cclave' => true,
            'correo_confirmacion_ccorreo' => true,
            'token_min_clave' => 6,
            'token_max_clave' => 6,
        ],
        'cambio' => [
            'ultimas_claves' => 1,
            'diccionario_clave' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuwxyz1234567890@)(',
            'diccionario_correo' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuwxyz1234567890.-_@',
            'diccionario_numeros' => '0123456789',
            'templates' => [
                'template_cambio_clave_id' => 12, //  ID del Template de Email
                'template_cambio_correo_id' => 14, //  ID del Template de Email
            ],
            'caracteres_min_clave' => 8,
            'caracteres_max_clave' => 15,
            'caracteres_max_correo' => 150,
            'caracteres_min_dni' => 8,
            'caracteres_max_dni' => 12,  
            'caracteres_long_celular' => 9,
            'caracteres_min_telefono' => 5,
            'caracteres_max_telefono' => 7,
            'correo_confirmacion_cclave' => true,
            'correo_confirmacion_ccorreo' => true,
        ]
    ]
];
