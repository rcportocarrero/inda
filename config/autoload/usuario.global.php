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
            'diccionario' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'tamano_codigo' => 5
        ],
        'login' => [
            'tamanio' => [
                'caracteres_min_clave' => 8,
                'caracteres_max_clave' => 15,
                'caracteres_min_dni' => 8,
                'caracteres_max_dni' => 12,
            ]
        ],
        'registro' => [
        ],
        'recuperacion' => [
            'ultimas_claves' => 1,
            'template_cambio_clave_id' => 1,
            'diccionario_clave' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuwxyz1234567890@)(',
            'correo_confirmacion_cclave' => true,
            'correo_confirmacion_ccorreo' => true,
        ],
    ]
];
