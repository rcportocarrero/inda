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
 * Description of SeleccionController
 *
 * @author hnkr
 */
class IndexController extends \BaseX\Controller\BaseController {

    protected $needAuthentication = TRUE;
    protected $enable_layout = false;
    protected $_seleccionTable = null;

    public function getSeleccionTable() {
        if (!$this->_seleccionTable) {
            $sm = $this->getServiceLocator();
            $this->_seleccionTable = $sm->get('Dashboard\Model\Seleccion');
        }
        return $this->_seleccionTable;
    }

    //Index presentacion de trayectoria
    public function indexpresentacionAction() {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(TRUE);
        return $viewModel;
    }

    //Index seleccion de plazas
    public function indexseleccionAction() {
        $username = $this->getSessionStorage()->get('user');
        $config = $this->getConfig();
        $datos = $this->getSeleccionTable()->datos_postulante($username);
        $estado_postulante = $this->getSeleccionTable()->existe_registro_postulante($datos['id_postulante']);
        $ruta_descarga = '';
        $ruta_constancia = $this->getSeleccionTable()->ruta_constancia($datos['id_postulante']);

        if ($ruta_constancia['cantidad'] > 0) {
            $ruta_descarga = 'existe';
        }

        $selecciones_primera_etapa = [];
        $cantidad_selecciones = 0;

        $etapa = $config['config_extra']['param_sistema']['etapa'];
        if (intval($etapa) === 2) {
            $cantidad = $this->getSeleccionTable()->countPlazasResultantes($datos['id_postulante']);
            if ($cantidad['cantidad_detalles'] > 0) {
                $selecciones_primera_etapa = $this->getSeleccionTable()->carga_datos_segunda_etapa($datos['id_postulante']);
            }
        }

        $params = array(
            'id_postulante' => $datos['id_postulante'],
            'id_grupo_inscripcion' => $datos['id_grupo_inscripcion'],
            'desc_grupo_inscripcion' => $datos['desc_grupo_inscripcion'],
            'ruta_constancia' => $ruta_descarga,
            'existen_registros' => $estado_postulante['cantidad'],
            'etapa' => $etapa,
            'estado_postulante' => $datos['msj_estado'],
            'id_estado_postulante' => $datos['estado'],
            'seleccion_primera_etapa' => $selecciones_primera_etapa,
        );
        $viewModel = new ViewModel($params);
        $viewModel->setTerminal(TRUE);
        return $viewModel;
    }

    public function vistapreviaAction() {
        $username = $this->getSessionStorage()->get('user');
        $config = $this->getConfig();
        $datos = $this->getSeleccionTable()->datos_postulante($username);
        $ruta_constancia = $this->getSeleccionTable()->ruta_constancia($datos['id_postulante']);
        $url_file = '';
        $param_ecuesta = $config['config_extra']['param_sistema']['mostrar_encuesta'];
        $param_ruta_ecuesta = $config['config_extra']['param_sistema']['ruta_encuesta'];
        #$ruta_cocatenada = $param_ruta_ecuesta.$username;
	$ruta_cocatenada = $param_ruta_ecuesta.$datos['id_postulante'];
        if ($ruta_constancia['cantidad'] > 0) {
            $url_file = $ruta_constancia['ruta_constancia'];
        }
        $viewModel = new ViewModel(array('id_postulante' => $datos['id_postulante'], 'id_grupo_inscripcion' => $datos['id_grupo_inscripcion'], 'desc_grupo_inscripcion' => $datos['desc_grupo_inscripcion'], 'ruta' => $url_file ,'encuesta' =>$param_ecuesta ,'ruta_encuesta' =>$ruta_cocatenada));
        $viewModel->setTerminal(TRUE);
        return $viewModel;
    }

    public function pdfAction() {
        $request = $this->params();
        $config = $this->getConfig();
        $raw = $request->fromQuery('r', '');
        $datos_decoded = base64_decode($raw);
        $datos_decoded_json = json_decode($datos_decoded);
        $rutaj = $this->getSeleccionTable()->ruta_constancia($datos_decoded_json->id_postulante);
        $ruta_descarga = '-';
        $metodo = '';
        $nombre_file = $rutaj['ruta_constancia'];
        if ($rutaj['cantidad'] > 0) {
            $ruta_descarga = $rutaj['ruta_constancia'];
            $metodo = $datos_decoded_json->metodo;
        }
        try {
            $url_file = $config['config_extra']['path_files']['pdf'] . '/' . $ruta_descarga;
            $data_file = file_exists($url_file);
            if ($data_file === false) {
                throw new \Exception('El archivo no existe');
            } else {
                $data_file = file_get_contents($url_file);
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }

        $viewModel = new ViewModel([
            'data_pdf' => $data_file,
            'metodo' => $metodo,
            'nombre_file' => $nombre_file
        ]);
        $viewModel->setTerminal(TRUE);

        return $viewModel;
    }

    //Index registro

    public function indexregistroAction() {
        $username = $this->getSessionStorage()->get('user');
        $datos = $this->getSeleccionTable()->datos_postulante($username);
        $viewModel = new ViewModel();
        $viewModel->setTerminal(TRUE);
        return $viewModel;
    }

    public function getRegionAction() {
        $slct = $this->getSeleccionTable()->carga_region();
        return new JsonModel($slct);
    }

    public function getFamiliaAction() {
        $request = $this->params();
        $slct = $this->getSeleccionTable()->carga_familia();
        return new JsonModel($slct);
    }

    public function getDreUgelAction() {
        $request = $this->params();
        $id = $request->fromQuery('id', '');
        $slct = $this->getSeleccionTable()->carga_dre_ugel($id);
        return new JsonModel($slct);
    }

    public function getLenguasAction() {
        $slct = $this->getSeleccionTable()->carga_lenguas();

        return new JsonModel($slct);
    }

    public function getPrioridadesAction() {
        $username = $this->getSessionStorage()->get('user');
        $datos = $this->getSeleccionTable()->datos_postulante($username);
        $slct = $this->getSeleccionTable()->carga_prioridad_x_postulante($datos['id_postulante']);
        return new JsonModel($slct);
    }

    public function getDataGrillaAction() {
        $request = $this->params();
        $page = $request->fromQuery('page', '');
        $limit = $request->fromQuery('rows', '');
        $sidx = $request->fromQuery('sidx', '');
        $sord = $request->fromQuery('sord', '');
        $familia = $request->fromQuery('fm', '');

        $username = $this->getSessionStorage()->get('user');
//        $filters = json_decode($request->fromQuery('qry', ''));
        $filters_encode = $request->fromQuery('rndom', '');
        $filters = json_decode(base64_decode($filters_encode, TRUE));

        //Validar si el formato de query es una cadena json válida
        if (!$filters) {
            $rp = array();
            $rp['id'] = -1;
            $rp['mensaje'] = 'La cadena enviada no es un formato json válido.';
            $rp['page'] = 0;
            $rp['total'] = 0;
            $rp['records'] = 0;
            $rp['rows'] = array();
            return new JsonModel($rp);
        }
        $cadena_region = "";
        if ($filters->qry_region !== '') {
            $cadena_region.=' and ( du.id_region=' . $filters->qry_region . ') ';
        }

        //Validando parámetro página
        $resultValidate_sc_familia = $this->validarParametro($familia, array('min' => 1, 'max' => 2), true);
        if (isset($resultValidate_sc_familia)) {
            return $resultValidate_sc_familia;
        }

        //Validando parámetro página
        $resultValidate_sc_page = $this->validarParametro($page, array('min' => 1, 'max' => 4), true);
        if (isset($resultValidate_sc_page)) {
            return $resultValidate_sc_page;
        }
        //Validando parámetro limite de páginas
        $resultValidate_sc_limit = $this->validarParametro($limit, array('min' => 1, 'max' => 4), true);
        if (isset($resultValidate_sc_limit)) {
            return $resultValidate_sc_limit;
        }
        //Validando parámetro order by parameter
        $resultValidate_sc_sidx = $this->validarParametro($sidx, array('min' => 1, 'max' => 20), false);
        if (isset($resultValidate_sc_sidx)) {
            return $resultValidate_sc_sidx;
        }

        //Validando parámetro oder by asc desc
        $resultValidate_sc_sord = $this->validarParametro($sord, array('min' => 3, 'max' => 4), false);
        if (isset($resultValidate_sc_sord)) {
            return $resultValidate_sc_sord;
        }

        $ar_order_by = array(
            'asc',
            'desc'
        );

        if ($sord !== '') {
            if (in_array($sord, $ar_order_by) === false) {
                $rp = array();
                $rp['id'] = -1;
                $rp['mensaje'] = 'Este parámetro fue alterado y no cumple con los límites establecido.';
                $rp['page'] = 0;
                $rp['total'] = 0;
                $rp['records'] = 0;
                $rp['rows'] = array();
                return new JsonModel($rp);
            }
        }

        //Validando parámetro página
        //query filtro ugeles
        $cadena_ugel = "";
        $i1 = 0;
        $limit1 = count($filters->qry_dre_ugel);
        $cierre1 = "";
        $cierre11 = "";
        if ($limit1 === 1) {
            $cierre11 = " ) ";
        }

        foreach ($filters->qry_dre_ugel as $key1 => $valor1) {

            if (($limit1 - 1) === $i1) {
                $cierre1 = " ) ";
            }

            if ($key1 === 0) {
                if (is_numeric($valor1)) {
                    $cadena_ugel.=' and ( it.id_dre_ugel=' . $valor1 . $cierre11 . ' ';
                }
            }
            if ($key1 >= 1) {
                if (is_numeric($valor1)) {
                    $cadena_ugel.=' or it.id_dre_ugel=' . $valor1 . $cierre1 . ' ';
                }
            }
            $i1++;
        }

        //query filtro lengua
        $cadena_lenguas = "";
        $i2 = 0;
        $limit2 = count($filters->qry_lenguas);
        $cierre2 = "";
        $cierre22 = "";
        if ($limit2 === 1) {
            $cierre22 = " ) ";
        }
        foreach ($filters->qry_lenguas as $key2 => $valor2) {
            if (($limit2 - 1 ) === $i2) {
                $cierre2 = " ) ";
            }

            if ($key2 === 0) {
                if (is_numeric($valor2)) {
                    $cadena_lenguas.=' and ( it.id_lengua=' . $valor2 . $cierre22 . ' ';
                }
            }
            if ($key2 >= 1) {
                if (is_numeric($valor2)) {
                    $cadena_lenguas.=' or it.id_lengua=' . $valor2 . $cierre2 . ' ';
                }
            }
            $i2++;
        }
        //query filtro gestion
        $cadena_gestion = "";
        $i3 = 0;
        $limit3 = count($filters->qry_gestion);
        $cierre3 = "";
        $cierre33 = "";
        if ($limit3 === 1) {
            $cierre33 = " ) ";
        }
        foreach ($filters->qry_gestion as $key3 => $valor3) {
            if (($limit3 - 1 ) === $i3) {
                $cierre3 = " ) ";
            }
            if ($key3 === 0) {
                if ($valor3 === 'PÚBLICA' || $valor3 === 'CONVENIO') {
                    $cadena_gestion.=' and ( it.gestion="' . $valor3 . '"' . $cierre33 . ' ';
                }
            }
            if ($key3 >= 1) {
                if ($valor3 === 'PÚBLICA' || $valor3 === 'CONVENIO') {
                    $cadena_gestion.=' or it.gestion="' . $valor3 . '"' . $cierre3 . ' ';
                }
            }
            $i3++;
        }
        //query filtro ruralidad
        $cadena_ruralidad = "";
        $i4 = 0;
        $limit4 = count($filters->qry_ruralidad);
        $cierre4 = "";
        $cierre44 = "";
        if ($limit4 === 1) {
            $cierre44 = " ) ";
        }
        foreach ($filters->qry_ruralidad as $key4 => $valor4) {
            if (($limit4 - 1 ) === $i4) {
                $cierre4 = " ) ";
            }
            if ($key4 === 0) {
                if ($valor4 === 'RURAL' || $valor4 === 'URBANO') {
                    $cadena_ruralidad.=' and ( it.ruralidad LIKE "%' . $valor4 . '%"' . $cierre44 . ' ';
                }
            }
            if ($key4 >= 1) {
                if ($valor4 === 'RURAL' || $valor4 === 'URBANO') {
                    $cadena_ruralidad.=' or it.ruralidad LIKE "%' . $valor4 . '%"' . $cierre4 . ' ';
                }
            }
            $i4++;
        }
        //query filtro tipoiiee
        $cadena_tipoiiee = "";
        $i5 = 0;
        $limit5 = count($filters->qry_tipoiiee);
        $cierre5 = "";
        $cierre55 = "";
        if ($limit5 === 1) {
            $cierre55 = " ) ";
        }
        foreach ($filters->qry_tipoiiee as $key5 => $valor5) {
            if (($limit5 - 1 ) === $i5) {
                $cierre5 = " ) ";
            }
            if ($key5 === 0) {
                if ($valor5 === 'MULTIGRADO' || $valor5 === 'POLIDOCENTE' || $valor5 === 'UNIDOCENTE') {
                    $cadena_tipoiiee.=' and ( it.tipo_ie="' . $valor5 . '"' . $cierre55 . ' ';
                }
            }
            if ($key5 >= 1) {
                if ($valor5 === 'MULTIGRADO' || $valor5 === 'POLIDOCENTE' || $valor5 === 'UNIDOCENTE') {
                    $cadena_tipoiiee.=' or it.tipo_ie="' . $valor5 . '"' . $cierre5 . ' ';
                }
            }
            $i5++;
        }
        //agregando filtro familia
        $cadena_familia = '';
        if (is_numeric($familia) && intval($familia) !== 0) {
            $cadena_familia = ' and (id_familia=' . $familia . ') ';
        }


        $query_all_filters = $cadena_region . $cadena_ugel . $cadena_lenguas . $cadena_gestion . $cadena_ruralidad . $cadena_tipoiiee . $cadena_familia;

        if (!$sidx) {
            $sidx = 1;
        }
        $datos = $this->getSeleccionTable()->datos_postulante($username);
        $params_count = array(
            'grupo_inscripcion' => $datos['id_grupo_inscripcion'],
            'filtros_generales' => $query_all_filters
        );

        $select = $this->getSeleccionTable()->countTotalRegistros($params_count);
        $count = $select[0]['total'];
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages) {
            $page = $total_pages;
        }
        $start = $limit * $page - $limit;
        if (empty($sord)) {
            $sord = '';
        }
        if (empty($limit)) {
            $limit = '';
        }
        if ($count != 0) {
            $params = array(
                'sidx' => $sidx,
                'sord' => $sord,
                'start' => $start,
                'limit' => $limit,
                'grupo_inscripcion' => $datos['id_grupo_inscripcion'],
                'filtros_generales' => $query_all_filters
            );

            $result = $this->getSeleccionTable()->carga_iiee_grid($params);
        } else {
            $result = array();
        }

        $rp = array();
        $rp['page'] = $page;
        $rp['total'] = $total_pages;
        $rp['records'] = $count;
        $rp['rows'] = $result;
        return new JsonModel($rp);

        ////////////////////
    }

    public function guardarsinseleccionAction() {
        $response = null;
        $request = $this->params();
        $username = $this->getSessionStorage()->get('user');
        $datos = $this->getSeleccionTable()->datos_postulante($username);
        try {
            $result = $this->getSeleccionTable()->sp_update_cabecera_detalle($datos['id_postulante']);
            if ($result['_estado'] === 'ACTUALIZO') {
                $this->generarpdf_sin_seleccion($username, $datos['id_postulante'], $datos['desc_grupo_inscripcion'], $datos['nombre_completo']);
                $response = array('codigo' => 1, 'mensaje' => 'Se guardó correctamente la cabecera sin detalles.');
            } else {
                $response = array('codigo' => 2, 'mensaje' => 'No tiene selecciones para guardar.');
            }
        } catch (Exception $ex) {
            $response = array('codigo' => -1, 'mensaje' => 'Ocurrió un error, vuelva a intentarlo.');
        }
        $jsonModel = new JsonModel($response);
        return $jsonModel;
    }

    public function guardarSeleccionPlazasAction() {
        $response = null;
        $crc = '';
        $mascero = '';
        $mascero2 = '';
        $cantidad = 0;
        $config = $this->getConfig();
        $request = $this->params();
        $seleccion = $request->fromPost('ids', '');
        $username = $this->getSessionStorage()->get('user');
        $crc.=$username;
        try {
            $array_seleccion = array();
            $array_detalle_seleccion = array();
            $cantidad = count($seleccion);
            //Generando CRC
            for ($i = 0; $i < $cantidad; $i++) {
                if ($cantidad < 10) {
                    $mascero = '0';
                }
                $array_seleccion[$i] = explode("__", $seleccion[$i]);
                $crc .= $array_seleccion[$i][2] . $mascero . $array_seleccion[$i][0];
            }
            //guardar cabecera
            $datos = $this->getSeleccionTable()->datos_postulante($username);
            $result = $this->getSeleccionTable()->sp_save_seleccion($crc, $datos['id_postulante']);
            //guardar detalle de seleccion

            if ($result['_resultado'] === 'NUEVOREGISTRO' || $result['_resultado'] === 'ACTUALIZAREGISTRO') {
                //el registro es nuevo
                //inserto detalle
                try {
                    if ($result['_resultado'] === 'ACTUALIZAREGISTRO') {
                        $update_detalle_seleccion = $this->getSeleccionTable()->update_detalle_seleccion_plazas($result['_id_seleccion_x_postulante_anterior']);
                    }
                    for ($o = 0; $o < $cantidad; $o++) {
                        //_id_seleccion_x_postulante   => id de cabecera seleccion de postulante
                        if ($cantidad < 10) {
                            $mascero2 = '0';
                        }
                        $array_seleccion[$o] = explode("__", $seleccion[$o]);
                        $insert_detalle_seleccion = $this->getSeleccionTable()->insert_detalle_seleccion_plazas($result['_id_seleccion_x_postulante'], $array_seleccion[$o][3], $mascero2 . $array_seleccion[$o][0]);
                    }

                    $pdf = $this->generarpdf_seleccion($username, $datos['id_postulante']);
                    $param_envio = $config['config_extra']['param_sistema']['envio_de_correos'];
                    if (intval($param_envio) === 1) {
                        $enviar = $this->enviarCorreo();
                    }
                    $response = array('codigo' => 1, 'mensaje' => 'Se guardó correctamente.');
                } catch (Exception $exc) {
                    $response = array('codigo' => 1, 'mensaje' => 'Error.');
                }
            }
            if ($result['_resultado'] === 'NOCAMBIOCRC') {
                //el registro ya existe y no se hace nada
                $response = array('codigo' => 2, 'mensaje' => 'Se guardó correctamente.');
            }
        } catch (Exception $ex) {
            $response = array('mensaje' => 'Ocurrió un error.');
        }
        $jsonModel = new JsonModel($response);
        return $jsonModel;
    }

    public function generarpdf_sin_seleccion($dni, $id_postulante, $desc_grupo_inscripcion, $nombres) {
        $nombre_pdf = $dni . '_' . date("YmdHis");
        $fecha_hora = date("d/m/Y H:i A");
        $cuerpo = $this->generar_html_sin_seleccion_plazas($dni, $desc_grupo_inscripcion, $nombres, $fecha_hora);
        $html = $cuerpo;
        $mpdf = new \mPDF('utf-8', 'A4-L');
//        $mpdf = new \mPDF('c', 'A4-L', '10px', 'Tahoma', 10, 5, 10, 10, 10, 0, 'L');
        $mpdf = new \mPDF('c', 'A4-L', '10px', 'Tahoma', 10, 5, 10, 30, 10, 20, 'L');
        $mpdf->mirrorMargins = 1;
        $mpdf->WriteHTML($html);
        //$uploaddir = getcwd() . '/public/pdf_seleccion_plazas';
	$uploaddir='/var/www/evaluaciondocente.perueduca.pe/www/cpm/cpm-sp/public/dynamic/pdf_seleccion_plazas';

        if (is_dir($uploaddir)) {
            $mpdf->Output(realpath($uploaddir) . '/' . $nombre_pdf . '.pdf', 'F');
        }
        $update = $this->getSeleccionTable()->update_ruta_pdf($dni, $nombre_pdf . '.pdf', $id_postulante);
        return $mpdf;
    }


    public function generarpdf_seleccion($dni, $id_postulante) {
        $nombre_pdf = $dni . '_' . date("YmdHis");
        $fecha_hora = date("d/m/Y H:i A");
        $a_html = array();
        $cuerpo = $this->generar_html_seleccion_plazas($dni, $fecha_hora);
        $html = $cuerpo;
        $mpdf = new \mPDF('utf-8', 'A4-L');
        $mpdf = new \mPDF('c', 'A4-L', '10px', 'Tahoma', 10, 5, 10, 30, 10, 20, 'L');
        $mpdf->mirrorMargins = 1;
        $mpdf->WriteHTML($html);
        //$uploaddir = getcwd() . '/public/pdf_seleccion_plazas';
	 $uploaddir='/var/www/evaluaciondocente.perueduca.pe/www/cpm/cpm-sp/public/dynamic/pdf_seleccion_plazas';
	$nombre_pdf = $dni . '_' . date("YmdHis");
        if (is_dir($uploaddir)) {
            $mpdf->Output(realpath($uploaddir) . '/' . $nombre_pdf . '.pdf', 'F');
        }
        $update = $this->getSeleccionTable()->update_ruta_pdf($dni, $nombre_pdf . '.pdf', $id_postulante);
        return $mpdf;
    }

    public function generar_html_seleccion_plazas($dni, $fecha_hora) {
        $datos_plaza_seleccionada = $this->getSeleccionTable()->listar_plaza_seleccionada_por_dni($dni);
        $html_datos = '';
        $a_html = array();
        $img_logo = getcwd() . '/public/img/logo_ministerio_educacion.gif';
        $a_html[] = '<htmlpagefooter name="myFooter2">
            <div style="width: 100%; text-align:left;">
                        <table style="width:100%">
                                <tbody>
                                        <tr>
                                                <td style="text-align:left"><strong>Recuerde usted que puede modificar los registros seleccionados durante el periodo que indica el cronograma de los Concursos Públicos de Ingreso a la Carrera Pública Magisterial.</strong></td>
                                        </tr>
                                          <tr>
                                                <td style="text-align:left"><strong>Fecha y hora de registro: ' . $fecha_hora . '</strong></td>
                                        </tr>
                                </tbody>
                        </table>
                    </div>
                    </htmlpagefooter>    
        <sethtmlpagefooter name="myFooter2" page="O" value="on" show-this-page="1" />
        <sethtmlpagefooter name="myFooter2" page="E" value="on" />
                    ';
        $a_html[] = '<div style="width: 100%;">
<table style="width:100%;text-align:center;">
	<tbody>
		<tr>
			<td><img src="' . $img_logo . '" /></td>
			<td>
			<div>
			<p style="text-align: center;"><strong>CONCURSOS P&Uacute;BLICOS DE INGRESO A LA CARRERA P&Uacute;BLICA MAGISTERIAL Y DE CONTRATACI&Oacute;N DOCENTE EN INSTITUCIONES EDUCATIVAS P&Uacute;BLICAS DE EDUCACION B&Aacute;SICA</strong></p>

			<p style="text-align: center;"><strong>R.V.M. N&deg; 021-2015-MINEDU</strong></p>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
			<p style="text-align: center;"><strong>FORMATO DIGITAL</strong></p>
			</div>
			</td>
		</tr>
	</tbody>
</table>
</div>';
        $a_html[] = '<div style="width: 100%; text-align:center; padding-top:20px">
                    <table style="width:700px">
                        <tbody>
                                <tr>
                                        <td colspan="3" style="text-align:left"><strong>Datos del postulante:</strong></td>
                                </tr>
                                <tr>
                                        <td style="text-align:left; width:50px">&nbsp;</td>
                                        <td style="text-align:left; width:200px"><strong>N&deg; de Documento de Identidad:</strong></td>
                                        <td style="text-align:left; width:450px">' . $dni . '</td>
                                </tr>
                                <tr>
                                        <td style="text-align:left">&nbsp;</td>
                                        <td style="text-align:left"><strong>Nombres y apellidos:</strong></td>
                                        <td style="text-align:left">' . $datos_plaza_seleccionada[0]['nombres'] . '</td>
                                </tr>
                                <tr>
                                        <td style="text-align:left">&nbsp;</td>
                                        <td style="text-align:left"><strong>Grupo de inscripci&oacute;n:</strong></td>
                                        <td style="text-align:left">' . $datos_plaza_seleccionada[0]['desc_grupo_inscripcion'] . '</td>
                                </tr>
                        </tbody>
                    </table>
                    </div>';
        $a_html[] = '<div style="width: 100%; text-align:center; padding-top:20px">
                        <table style="width:700px">
                                <tbody>
                                        <tr>
                                                <td colspan="3" style="text-align:left"><strong>Instituciones educativas seleccionadas:</strong></td>
                                        </tr>
                                        <tr>
                                                <td style="text-align:left; width:50px">&nbsp;</td>
                                                <td style="text-align:left; width:200px"><strong>Regi&oacute;n:</strong></td>
                                                <td style="text-align:left; width:450px">' . $datos_plaza_seleccionada[0]['desc_region'] . '</td>
                                        </tr>
                                </tbody>
                        </table>
                    </div>';
        $a_html[] = '<div style="width: 100%; text-align:center; padding-top:50px">
                        <table border="0" cellpadding="1" cellspacing="1" style="width: 1050px;">
                                <tbody>
                                <tr>
                                    <th colspan="1" style="border-color: rgb(0, 0, 0); text-align: center; width: 60px; background-color: rgb(0, 0, 0);"><strong><span style="color:#FFFFFF;">Prioridad</span></strong></th>
                                    <th colspan="1" style="border-color: rgb(0, 0, 0); text-align: center; width: 100px; background-color: rgb(0, 0, 0);"><strong><span style="color:#FFFFFF;">Dre/Ugel</span></strong></th>
                                    <th colspan="1" style="border-color: rgb(0, 0, 0); text-align: center; width: 70px; background-color: rgb(0, 0, 0);"><strong><span style="color:#FFFFFF;">Cod. Modular</span></strong></th>
                                    <th colspan="1" style="border-color: rgb(0, 0, 0); text-align: center; width: 140px; background-color: rgb(0, 0, 0);"><strong><span style="color:#FFFFFF;">Nombre de IE</span></strong></th>
                                    <th colspan="1" style="border-color: rgb(0, 0, 0); text-align: center; width: 100px; background-color: rgb(0, 0, 0);"><strong><span style="color:#FFFFFF;">Tipo de IE</span></strong></th>
                                    <th colspan="1" style="border-color: rgb(0, 0, 0); text-align: center; width: 100px; background-color: rgb(0, 0, 0);"><strong><span style="color:#FFFFFF;">Distrito</span></strong></th>
                                    <th colspan="1" style="border-color: rgb(0, 0, 0); text-align: center; width: 60px; background-color: rgb(0, 0, 0);"><strong><span style="color:#FFFFFF;">Ámbito</span></strong></th>
                                    <th colspan="1" style="border-color: rgb(0, 0, 0); text-align: center; width: 60px; background-color: rgb(0, 0, 0);"><strong><span style="color:#FFFFFF;">Biling&uuml;e</span></strong></th>
                                    <th colspan="1" style="border-color: rgb(0, 0, 0); text-align: center; width: 100px; background-color: rgb(0, 0, 0);"><strong><span style="color:#FFFFFF;">Lengua</span></strong></th>
                                    <th colspan="1" style="border-color: rgb(0, 0, 0); text-align: center; width: 7px; background-color: rgb(0, 0, 0);"><strong><span style="color:#FFFFFF;">Frontera</span></strong></th>
                                    <th colspan="1" style="border-color: rgb(0, 0, 0); text-align: center; width: 50px; background-color: rgb(0, 0, 0);"><strong><span style="color:#FFFFFF;">Vraem</span></strong></th>
                                    <th colspan="1" style="border-color: rgb(0, 0, 0); text-align: center; width: 60px; background-color: rgb(0, 0, 0);"><strong><span style="color:#FFFFFF;">Gesti&oacute;n</span></strong></th>
                                    <th colspan="1" style="border-color: rgb(0, 0, 0); text-align: center; width: 160px; background-color: rgb(0, 0, 0);"><strong><span style="color:#FFFFFF;">Familia / Especialidad</span></strong></th>
                                </tr>';
        for ($i = 0; $i < count($datos_plaza_seleccionada); $i++) {
            $a_html[] = '                                <tr>
                                                <td style="boder:1px solid;border-color: rgb(0, 0, 0); text-align: center;">' . $datos_plaza_seleccionada[$i]['prioridad'] . '</td>
                                                <td>' . $datos_plaza_seleccionada[$i]['desc_dre_ugel'] . '</td>
                                                <td>' . $datos_plaza_seleccionada[$i]['codigo_modular'] . '</td>
                                                <td>' . $datos_plaza_seleccionada[$i]['desc_institucion'] . '</td>
                                                <td>' . $datos_plaza_seleccionada[$i]['tipo_ie'] . '</td>
                                                <td>' . $datos_plaza_seleccionada[$i]['distrito'] . '</td>
                                                <td>' . $datos_plaza_seleccionada[$i]['ruralidad'] . '</td>
                                                <td>' . $datos_plaza_seleccionada[$i]['es_bilingue'] . '</td>
                                                <td>' . $datos_plaza_seleccionada[$i]['desc_lengua'] . '</td>
                                                <td>' . $datos_plaza_seleccionada[$i]['es_frontera'] . '</td>
                                                <td>' . $datos_plaza_seleccionada[$i]['es_vraem'] . '</td>
                                                <td>' . $datos_plaza_seleccionada[$i]['gestion'] . '</td>
                                                <td>' . $datos_plaza_seleccionada[$i]['de_familia_especialidad'] . '</td>
                                        </tr>	
                             ';
        }

        $a_html[] = '   </tbody>
                        </table>
                    </div>';


        $html_final = $html_datos . implode('', $a_html);
        return $html_final;
    }

    public function generar_html_sin_seleccion_plazas($dni, $desc_grupo_inscripcion, $nombres, $fecha_hora) {

        $html_datos = '';
        $a_html = array();
        $img_logo = getcwd() . '/public/img/logo_ministerio_educacion.gif';
        $a_html[] = '<htmlpagefooter name="myFooter2">
            <div style="width: 100%; text-align:center; padding-top:50px">
                        <table style="width:100%">
                                <tbody>
                                        <tr>
                                                <td style="text-align:left"><strong>Recuerde usted que puede modificar los registros seleccionados durante el periodo que indica el cronograma de los Concursos Públicos de Ingreso a la Carrera Pública Magisterial.</strong></td>
                                        </tr>
                                          <tr>
                                                <td style="text-align:left"><strong>Fecha y hora de registro: ' . $fecha_hora . '</strong></td>
                                        </tr>
                                </tbody>
                        </table>
                    </div>
                    </htmlpagefooter>      
        <sethtmlpagefooter name="myFooter2" page="O" value="on" show-this-page="1" />
        <sethtmlpagefooter name="myFooter2" page="E" value="on" />
                    ';
        $a_html[] = '<div style="width: 100%;">
<table style="width:100%;text-align:center;">
	<tbody>
		<tr>
			<td><img src="' . $img_logo . '" /></td>
			<td>
			<div>
			<p style="text-align: center;"><strong>CONCURSOS P&Uacute;BLICOS DE INGRESO A LA CARRERA P&Uacute;BLICA MAGISTERIAL Y DE CONTRATACI&Oacute;N DOCENTE EN INSTITUCIONES EDUCATIVAS P&Uacute;BLICAS DE EDUCACION B&Aacute;SICA</strong></p>

			<p style="text-align: center;"><strong>R.V.M. N&deg; 021-2015-MINEDU</strong></p>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
			<p style="text-align: center;"><strong>FORMATO DIGITAL</strong></p>
			</div>
			</td>
		</tr>
	</tbody>
</table>
</div>';
        $a_html[] = '<div style="width: 100%; text-align:center; padding-top:20px">
                    <table style="width:700px">
                        <tbody>
                                <tr>
                                        <td colspan="3" style="text-align:left"><strong>Datos del postulante:</strong></td>
                                </tr>
                                <tr>
                                        <td style="text-align:left; width:50px">&nbsp;</td>
                                        <td style="text-align:left; width:200px"><strong>N&deg; de Documento de Identidad:</strong></td>
                                        <td style="text-align:left; width:450px">' . $dni . '</td>
                                </tr>
                                <tr>
                                        <td style="text-align:left">&nbsp;</td>
                                        <td style="text-align:left"><strong>Nombres y apellidos:</strong></td>
                                        <td style="text-align:left">' . $nombres . '</td>
                                </tr>
                                <tr>
                                        <td style="text-align:left">&nbsp;</td>
                                        <td style="text-align:left"><strong>Grupo de inscripci&oacute;n:</strong></td>
                                        <td style="text-align:left">' . $desc_grupo_inscripcion . '</td>
                                </tr>
                        </tbody>
                    </table>
                    </div>';
        $a_html[] = '<div style="width: 100%; text-align:center; padding-top:20px">
                        <table style="width:700px">
                                <tbody>
                                        <tr>
                                                <td colspan="3" style="text-align:left"><strong>Instituciones educativas seleccionadas:</strong></td>
                                        </tr>
                                        <tr>
                                               <td colspan="3" style="padding-left:50px;text-align:left"><strong>Usted ha seleccionado (0) cero instituciones educativas.</strong></td>
                                        </tr>
                                </tbody>
                        </table>
                    </div>';

        $html_final = $html_datos . implode('', $a_html);
        return $html_final;
    }

    function validarParametro($parameter, $arrayLimit = NULL, $onlyNumbers = false) {

        $scape_page = htmlspecialchars($parameter, ENT_QUOTES);
//        $validator_ilegal_char = new Regex(['pattern' => '/[\^<,\"@\/\{\}\(\)\*\$%\?=>:\|;#]+/i']);
        $validator_ilegal_char = new Regex(['pattern' => '/[\^<,\"@\/\\\{\}\(\)\*\$%\?=>:\|;#]+/i']);

        //Dar limite de caracteres minimo y maximo
        if (isset($arrayLimit)) {
            $validator_limites = new ValidatorChain();
            //$validator_limites->attach(new StringLength(array('min' => 1, 'max' => 3)));
            $validator_limites->attach(new StringLength($arrayLimit));
            if (!$validator_limites->isValid($scape_page)) {
                $rp = array();
                $rp['id'] = -1;
                $rp['mensaje'] = 'Este parámetro fue alterado y no cumple con los límites establecido.';
                $rp['page'] = 0;
                $rp['total'] = 0;
                $rp['records'] = 0;
                $rp['rows'] = array();
                return new JsonModel($rp);
            }
        }

        if (strlen(trim($scape_page)) > 0) {
            //Validar que el campo no tenga caracteres extranios
            if ($validator_ilegal_char->isValid($scape_page)) {
                $rp = array();
                $rp['id'] = -1;
                $rp['mensaje'] = 'Se ha detectado un caracter no válido.';
                $rp['page'] = 0;
                $rp['total'] = 0;
                $rp['records'] = 0;
                $rp['rows'] = array();
                return new JsonModel($rp);
            }
        }
        //validar que el campo sea numerico
        if ($onlyNumbers) {
            $validator_int = new \Zend\I18n\Validator\Int();
            if (!$validator_int->isValid($scape_page)) {
                $rp = array();
                $rp['id'] = -1;
                $rp['mensaje'] = 'Este parámetro no contiene solo números.';
                $rp['page'] = 0;
                $rp['total'] = 0;
                $rp['records'] = 0;
                $rp['rows'] = array();
                return new JsonModel($rp);
            }
        }

        return null;
    }

    public function enviarformatodigitalAction() {
        $response = null;
        $config = $this->getConfig();
        $request = $this->params();

        $param_envio = $config['config_extra']['param_sistema']['envio_de_correos'];
        if (intval($param_envio) === 1) {
            $enviar = $this->enviarCorreo();
            if ($enviar) {
                $response = array('mensaje' => 'Se ha enviado un correo electrónico, revise su bandeja de entrada de correo.', 'codigo' => 1);
            }
        } else {
            $response = array('mensaje' => 'Ocurrió un error al enviar por correo.', 'codigo' => -1);
        }

        $jsonModel = new JsonModel($response);
        return $jsonModel;
    }

    function enviarCorreo() {

        $request = $this->params();
        $username = $this->getSessionStorage()->get('user');
        $config = $this->getConfig();
        $datos = $this->getSeleccionTable()->datos_postulante($username);
        $rutaj = $this->getSeleccionTable()->ruta_constancia($datos['id_postulante']);
        $ruta_descarga = '';
        $response = false;
        if (count($rutaj['cantidad']) > 0) {
            $ruta_descarga = $rutaj['ruta_constancia'];
            $url_file = $config['config_extra']['path_files']['pdf'] . '/' . $ruta_descarga;
            try {
                $to = $datos['correo_electronico'];
                $from = $config['datos_correo']['from'];
                $param_bcc = $config['config_extra']['param_sistema']['bcc'];
                if (intval($param_bcc) === 1) {
                    $bcc = $config['datos_correo']['bcc'];
                } else {
                    $bcc = '';
                }

                $subject = $config['datos_correo']['subject'];
                $html = $config['datos_correo']['html'];


                $datos_reemplazar_nombres = $datos['nombre_completo'];
                $html2 = str_replace('{{nombre_apellidos}}', $datos_reemplazar_nombres, $html);


                $text = '';
                $adjunto = array();
                $adjunto[] = array('content' => $url_file, 'filename' => $ruta_descarga);
                $correo = $this->sendEmail($to, $from, $subject, $html2, $text, $adjunto, $bcc);
                if ($correo) {
                    $response = true;
                }
            } catch (Exception $ex) {
                $response = false;
            }
        }

        return $response;
    }

    function sendEmail($to, $from, $subject, $html, $text, $attachments = null, $bcc='') {
        $config = $this->getConfig();
        $transportConfig = new SmtpOptions($config['envio_correo_reclamos']);
        $message = new Message();

        $message->addTo($to);
        $message->addFrom($from);
	if($bcc !== ''){
        $message->addBcc($bcc);
        }
        $message->setSubject($subject);

        // HTML part
        $htmlPart = new MimePart($html);
        $htmlPart->encoding = Mime::ENCODING_QUOTEDPRINTABLE;
        $htmlPart->type = "text/html; charset=UTF-8";

        // Plain text part
        $textPart = new MimePart($text);
        $textPart->encoding = Mime::ENCODING_QUOTEDPRINTABLE;
        $textPart->type = "text/plain; charset=UTF-8";

        $body = new MimeMessage();
        if ($attachments) {
            // With attachments, we need a multipart/related email. First part
            // is itself a multipart/alternative message        
            $content = new MimeMessage();
            $content->addPart($textPart);
            $content->addPart($htmlPart);

            $contentPart = new MimePart($content->generateMessage());
            $contentPart->type = "multipart/alternative;\n boundary=\"" .
                    $content->getMime()->boundary() . '"';

            $body->addPart($contentPart);
            $messageType = 'multipart/related';

            // Add each attachment
            foreach ($attachments as $thisAttachment) {
                $attachment = new MimePart(fopen($thisAttachment['content'], 'r'));
                $attachment->filename = $thisAttachment['filename'];
                $attachment->type = Mime::TYPE_OCTETSTREAM;
                $attachment->encoding = Mime::ENCODING_BASE64;
                $attachment->disposition = Mime::DISPOSITION_ATTACHMENT;

                $body->addPart($attachment);
            }
        } else {
            // No attachments, just add the two textual parts to the body
            $body->setParts(array($textPart, $htmlPart));
            $messageType = 'multipart/alternative';
        }

        // attach the body to the message and set the content-type
        $message->setBody($body);
        $message->getHeaders()->get('content-type')->setType($messageType);
        $message->setEncoding('UTF-8');
        try {
            $transport = new SmtpTransport();
            $transport->setOptions($transportConfig);
            $result = $transport->send($message);
            return true;
        } catch (Exception $exc) {
            return false;
        }
    }

}
