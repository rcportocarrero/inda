<?php

//'dsn' => 'mysql:host=db.piscolabs.com;dbname=caro_auth',
return [
    'db' => array(
        'adapters' => array(
            'db1' => array(
                'charset' => 'utf8',
                'database' => 'zf2_apigility',
                'driver' => 'PDO_Mysql',
                'hostname' => 'dev.redzrii.com',
//                'hostname' => 'cloud.piscolabs.com',
                'username' => 'root',
                'password' => 'carlossing88',
                'port' => '3306',
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Db\Adapter\AdapterAbstractServiceFactory',
        )
    ),
];
