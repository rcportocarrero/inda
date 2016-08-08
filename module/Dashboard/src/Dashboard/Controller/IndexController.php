<?php

namespace Dashboard\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends \BaseX\Controller\BaseController {

    protected $_need_auth = true;

    public function indexAction() {

//        var_dump($this->getSessionStorage()->isAuthenticate());
//        var_dump($this->getSessionStorage()->get('user'));
//        var_dump($this->getSessionStorage()->get('users_acl'));
        $config = $this->getConfig();
        $app_config = $config['app'];
        $usuario_dashboard = $config['usuario']['dashboard'];
        $usuario_cambio = $config['usuario']['cambio'];        
        $users_acl = json_decode($this->getSessionStorage()->get('users_acl'));
        $username = $this->getSessionStorage()->get('user');
        $token_sesion = sha1($username . time());
        $this->Session()->token_sesion = $token_sesion;

        $this->layout()->apps_var = $app_config;
        $this->layout()->users_acl = $users_acl;
        $this->layout()->dashboard_config = $usuario_dashboard;
        $this->layout()->cambio_config = $usuario_cambio;        
        $this->layout()->token_sesion = $this->Session()->token_sesion;

//        var_dump($users_acl);
//        var_dump($token_sesion);

        $params_view = [
            'apps_var' => $app_config,
            'users_acl' => $users_acl,
            'dashboard_config' => $usuario_dashboard,
            'cambio_config' => $usuario_cambio,
        ];

        $view = new ViewModel($params_view);
        return $view;

//        $writer = new \Zend\Log\Writer\Stream('log/app.log');
//        $logger = new \Zend\Log\Logger();
//        $logger->addWriter($writer);
//
//        $logger->info('Informational message XD1 ');
//        $logger->log(\Zend\Log\Logger::INFO, 'Informational message XD2');
//
//        $logger->log(\Zend\Log\Logger::INFO, 'Informational message');
//        $logger->info('Informational message 3');
//
//        $logger->log(\Zend\Log\Logger::EMERG, 'Emergency message');
//        $logger->emerg('Emergency message 4');
//    
    }

    public function presentacionAction() {
        $view = new ViewModel();
        $view->setTerminal(true);
        return $view;
    }

}
