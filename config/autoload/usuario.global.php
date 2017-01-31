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
            'diccionario' => 'A', //'ABCDEFGHIJKLMNPQRSTUVWXYZ',
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
        'dashboard' => [
            'opc_msje_app' => true
        ],
        'general' => [
            'ultimas_claves' => 3,
            'nro_intentos_codval_cel' => 2,
            'diccionario_clave' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890',
            'diccionario_correo' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890.-_@',
            'templates' => [
                'template_recuperacion_id' => 11, //  ID del Template de Recuperar Contraseña por Correo (envia link)
                'template_cambio_clave_id' => 12, //  ID del Template de Confirmacion de Cambio de Contraseña
                'template_cambio_correo_id' => 14, //  ID del Template de Cambio de Correo
            ],
            'concurso' => array(
                'id_concurso' => 8, // Concurso
                'id_etapa_actividad' => 14, //  Etapa de Actividad 
            ),        
        ],     
        'path_files' => [            
            'pdf' => getcwd() . '/public/pdf', // enlace simbólico    
            'pdf_diresp_fichas' => getcwd() . '/public/pdf_nmcd_fichas', // enlace simbólico            
            'pdf_reclamos' => getcwd() . '/public/pdf_nmcd_reclamos', // enlace simbólico
            'pdf_resp_reclamos' => getcwd() . '/public/pdf_nmcd_respuesta_reclamos', // enlace simbólico
            'pdf_verifica' => getcwd() . '/public/pdf_nmcd_cartilla', // enlace simbólico            
            'pdf_informe' => getcwd() . '/public/pdf_nmcd_informes', // enlace simbólico  
            'date_format' => 'YmdHis',
        ],
        'recuperacion' => [
            'opciones_recuperar' => [
                'correo' => true,
                'sms' => true
            ],
            'token_min_clave' => 6,
            'token_max_clave' => 6,
        ],
        'cambio' => [
            'caracteres_min_clave' => 8,
            'caracteres_max_clave' => 15,
            'caracteres_max_correo' => 150,
            'caracteres_min_dni' => 8,
            'caracteres_max_dni' => 12,  
            'caracteres_long_celular' => 9,
            'caracteres_long_valcel' => 6,
            'caracteres_min_telefono' => 6,
            'caracteres_max_telefono' => 7,
            'caracteres_max_direccion' => 250,
            'caracteres_min_nombre' => 5,
            'caracteres_max_nombre' => 250,
            'correo_confirmacion_cclave' => true,
        ]
    ]
];
