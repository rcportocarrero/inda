<?php

return [
    'db' => array(
        'adapters' => array(
            'db_maestro' => array(
                'charset' => 'utf8',
                'database' => 'db_died_maestro',
                'driver' => 'PDO_Mysql',
                'hostname' => '11.35.100.48',
                'username' => 'developer',
                'password' => 'developerdied2016**//peru',
                'port' => '33061',
                'profiler' => true, 
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Db\Adapter\AdapterAbstractServiceFactory',
        )
    ),
];
