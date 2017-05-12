<?php

namespace Seleccion\Controller;

use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use ZfcItp\Controller\BaseController;
use Zend\Crypt\Password\Bcrypt;
use Zend\File\Transfer\Adapter\Http;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Mime;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;
use Zend\Validator\ValidatorChain;
use Zend\Validator\StringLength;
use Zend\Validator\Regex;

/*
 * Description of IndexController
 *
 * @author hnkr
 */

class IndexController extends \BaseX\Controller\BaseController {

    protected $needAuthentication = TRUE;
    protected $enable_layout = false;
    protected $_seleccionTable = null;

    public function getSeleccionTable()
    {
        if (!$this->_seleccionTable)
        {
            $sm = $this->getServiceLocator();
            $this->_seleccionTable = $sm->get('Seleccion\Model\SeleccionTable');
        }
        return $this->_seleccionTable;
    }

    public function instrumentosAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(TRUE);
        return $viewModel;
    }

    public function inicioAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(TRUE);
        return $viewModel;
    }

    public function reportesAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(TRUE);
        return $viewModel;
    }

    public function getDataGrillaAction()
    {
        $grid = [
            [
                'id_instrumento' => 1,
                'nombre' => 'Ficha de cotejo sobre la ejecución de los grupos de inter aprendizaje',
                'meta' => 40,
                'informantes' => 'Docentes',
                'estado' => '0/20',
            ],
            [
                'id_instrumento' => 2,
                'nombre' => 'Ficha de prueba 1',
                'meta' => 50,
                'informantes' => 'Docentes',
                'estado' => '0/20',
            ],
            [
                'id_instrumento' => 3,
                'nombre' => 'Ficha de prueba 2',
                'meta' => 20,
                'informantes' => 'Docentes',
                'estado' => '0/20',
            ],
            [
                'id_instrumento' => 4,
                'nombre' => 'Ficha de prueba 3',
                'meta' => 10,
                'informantes' => 'Docentes',
                'estado' => '0/20',
            ],
            [
                'id_instrumento' => 5,
                'nombre' => 'Ficha de prueba 4',
                'meta' => 30,
                'informantes' => 'Docentes',
                'estado' => '0/20',
            ]
        ];

        return new JsonModel($grid);
    }

    public function getDataGrillaDetailAction()
    {

        $request = $this->params();
        $id = $request->fromQuery('id', '');
        
        $grid = [
            [
                'id_instrumento' => 1,
                'nombre' => '9878987 Jesus Niño Lindo',
                'informante' => 'Sanchez Alvarez Nelson',
                'estado' => '0/20',
            ],
             [
                'id_instrumento' => 2,
                'nombre' => '0777912 Inca Garcilaso',
                'informante' => 'Zapata Guzman Doris',
                'estado' => '0/20',
            ],
             [
                'id_instrumento' => 3,
                'nombre' => '0078977	Kcalvin KClein',
                'informante' => 'Sanchez Alvarez Greta',
                'estado' => '0/20',
            ],

        ];

        return new JsonModel($grid);
    }

    public function getInstrumentosAction()
    {
        //$slct = $this->getSeleccionTable()->carga_region();
        $data_instrumento = [
            [
                'id_instrumento' => 1,
                'nombre' => 'Ficha de cotejo sobre la ejecución de los grupos de inter aprendizaje',
                'id_tipo_estrategia' => 1,
                'id_tipo_intervencion' => 1,
                'id_tipo_instrumento' => 1,
                'id_tipo_ambito' => 1,
                'id_tipo_muestra' => 1,
                'tipoEstrategia' => [
                    'id_tipo_estrategia' => 1,
                    'nombre' => 'Tipo de estrategia 01'
                ],
                'tipoIntervencion' => [
                    'id_tipo_intervencion' => 1,
                    'nombre' => 'Tipo de intervencion 01'
                ],
                'tipoInstrumento' => [
                    'id_tipo_instrumento' => 1,
                    'nombre' => 'Tipo de instrumento 01'
                ],
                'tipoAmbito' => [
                    'id_tipo_ambito' => 1,
                    'nombre' => 'Tipo de ambito 01'
                ],
                'tipoMuestra' => [
                    'id_tipo_muestra' => 1,
                    'nombre' => 'Tipo de muestra 01'
                ],
                'listaGrupoPregunta' => null,
                'listaInstrumentoEmpleado' => [
                    [
                        'id_instrumento_empleado' => 1,
                        'sincronizado' => false,
                        'instrumentoCompletado' => false,
                        'id_instrumento' => 1,
                        'id_aplicador' => 1,
                        'id_informante' => 2,
                        'instrumento' => null,
                        'aplicador' => null,
                        'informante' => null,
                        'listaRespuestaPregunta' => null
                    ],
                    [
                        'id_instrumento_empleado' => 2,
                        'sincronizado' => false,
                        'instrumentoCompletado' => false,
                        'id_instrumento' => 1,
                        'id_aplicador' => 1,
                        'id_informante' => 3,
                        'instrumento' => null,
                        'aplicador' => null,
                        'informante' => null,
                        'listaRespuestaPregunta' => null
                    ],
                    [
                        'id_instrumento_empleado' => 3,
                        'sincronizado' => false,
                        'instrumentoCompletado' => false,
                        'id_instrumento' => 1,
                        'id_aplicador' => 1,
                        'id_informante' => 4,
                        'instrumento' => null,
                        'aplicador' => null,
                        'informante' => null,
                        'listaRespuestaPregunta' => null
                    ],
                    [
                        'id_instrumento_empleado' => 4,
                        'sincronizado' => false,
                        'instrumentoCompletado' => false,
                        'id_instrumento' => 1,
                        'id_aplicador' => 1,
                        'id_informante' => 5,
                        'instrumento' => null,
                        'aplicador' => null,
                        'informante' => null,
                        'listaRespuestaPregunta' => null
                    ],
                    [
                        'id_instrumento_empleado' => 5,
                        'sincronizado' => false,
                        'instrumentoCompletado' => false,
                        'id_instrumento' => 1,
                        'id_aplicador' => 1,
                        'id_informante' => 6,
                        'instrumento' => null,
                        'aplicador' => null,
                        'informante' => null,
                        'listaRespuestaPregunta' => null
                    ],
                    [
                        'id_instrumento_empleado' => 6,
                        'sincronizado' => false,
                        'instrumentoCompletado' => false,
                        'id_instrumento' => 1,
                        'id_aplicador' => 1,
                        'id_informante' => 7,
                        'instrumento' => null,
                        'aplicador' => null,
                        'informante' => null,
                        'listaRespuestaPregunta' => null
                    ],
                    [
                        'id_instrumento_empleado' => 7,
                        'sincronizado' => false,
                        'instrumentoCompletado' => false,
                        'id_instrumento' => 1,
                        'id_aplicador' => 1,
                        'id_informante' => 8,
                        'instrumento' => null,
                        'aplicador' => null,
                        'informante' => null,
                        'listaRespuestaPregunta' => null
                    ],
                    [
                        'id_instrumento_empleado' => 8,
                        'sincronizado' => false,
                        'instrumentoCompletado' => false,
                        'id_instrumento' => 1,
                        'id_aplicador' => 1,
                        'id_informante' => 9,
                        'instrumento' => null,
                        'aplicador' => null,
                        'informante' => null,
                        'listaRespuestaPregunta' => null
                    ],
                    [
                        'id_instrumento_empleado' => 9,
                        'sincronizado' => false,
                        'instrumentoCompletado' => false,
                        'id_instrumento' => 1,
                        'id_aplicador' => 1,
                        'id_informante' => 10,
                        'instrumento' => null,
                        'aplicador' => null,
                        'informante' => null,
                        'listaRespuestaPregunta' => null
                    ],
                    [
                        'id_instrumento_empleado' => 10,
                        'sincronizado' => false,
                        'instrumentoCompletado' => false,
                        'id_instrumento' => 1,
                        'id_aplicador' => 1,
                        'id_informante' => 11,
                        'instrumento' => null,
                        'aplicador' => null,
                        'informante' => null,
                        'listaRespuestaPregunta' => null
                    ],
                    [
                        'id_instrumento_empleado' => 11,
                        'sincronizado' => false,
                        'instrumentoCompletado' => false,
                        'id_instrumento' => 1,
                        'id_aplicador' => 1,
                        'id_informante' => 12,
                        'instrumento' => null,
                        'aplicador' => null,
                        'informante' => null,
                        'listaRespuestaPregunta' => null
                    ],
                    [
                        'id_instrumento_empleado' => 12,
                        'sincronizado' => false,
                        'instrumentoCompletado' => false,
                        'id_instrumento' => 1,
                        'id_aplicador' => 1,
                        'id_informante' => 13,
                        'instrumento' => null,
                        'aplicador' => null,
                        'informante' => null,
                        'listaRespuestaPregunta' => null
                    ],
                    [
                        'id_instrumento_empleado' => 13,
                        'sincronizado' => false,
                        'instrumentoCompletado' => false,
                        'id_instrumento' => 1,
                        'id_aplicador' => 1,
                        'id_informante' => 14,
                        'instrumento' => null,
                        'aplicador' => null,
                        'informante' => null,
                        'listaRespuestaPregunta' => null
                    ],
                    [
                        'id_instrumento_empleado' => 14,
                        'sincronizado' => false,
                        'instrumentoCompletado' => false,
                        'id_instrumento' => 1,
                        'id_aplicador' => 1,
                        'id_informante' => 15,
                        'instrumento' => null,
                        'aplicador' => null,
                        'informante' => null,
                        'listaRespuestaPregunta' => null
                    ],
                    [
                        'id_instrumento_empleado' => 15,
                        'sincronizado' => false,
                        'instrumentoCompletado' => false,
                        'id_instrumento' => 1,
                        'id_aplicador' => 1,
                        'id_informante' => 16,
                        'instrumento' => null,
                        'aplicador' => null,
                        'informante' => null,
                        'listaRespuestaPregunta' => null
                    ]
                ]
            ]
        ];
        return new JsonModel($data_instrumento);
    }

}
