function menu_datos_usuario() {
    load_menu_datos_usuario();
}

function load_menu_datos_usuario(ops) {
    if (ops === undefined) {
        ops = 0;
    }

    if (valida_sesion() === false) {
        return;
    }

    BaseX.load_html(root + '/usuario/perfil/index', {
        data: ops,
        success: function (xhr) {

            jQuery('#workArea').html(xhr);

            /*
             * ****************************
             * Perfil de datos de usuario
             * ****************************
             * Datos personales
             *
             */

            var pefil_btn_cambiar_contrasena = jQuery('#pefil_btn_cambiar_contrasena');
            var pefil_adicional_ref_btn_cambiar_num_tel = jQuery('#pefil_adicional_ref_btn_cambiar_num_tel');

            // Cambio de contraseña
            var div_row_cambio_contrasena = jQuery('#div_row_cambio_contrasena');
            var row_cambio_contrasena_title = jQuery('#row_cambio_contrasena_title');
            var perfil_row_pass_detail_cancelar = jQuery('#perfil_row_pass_detail_cancelar');

            div_row_cambio_contrasena.hide();
            /* Cambio de contraseña */
            pefil_btn_cambiar_contrasena.off('click');
            pefil_btn_cambiar_contrasena.on('click', function (evt) {
                evt.preventDefault();
                row_cambio_contrasena_title.addClass('row_selected');
                pefil_btn_cambiar_contrasena.hide();
                div_row_cambio_contrasena.show();
            });

            // Cambiar contraseña - Boton guardar

            var perfil_row_pass_detail_guardar = jQuery('#perfil_row_pass_detail_guardar');

            perfil_row_pass_detail_guardar.off('click');
            perfil_row_pass_detail_guardar.on('click', function (evt) {
            perfil_row_pass_detail_guardar.prop('disabled', true);

//                alert('Guardar contraseña');

                var pass_old = jQuery('#perfil_pass_clave_old');
                var pass_pri = jQuery('#perfil_pass_nueva_clave');
                var pass_ver = jQuery('#perfil_pass_nueva_clave_confir');

//                perfilCambiarClave
                var obj = {
                    password_old: pass_old.val(),
                    password_pri: pass_pri.val(),
                    password_ver: pass_ver.val(),
                };

                BaseX.post({
                    url: root + '/usuario/perfil/perfil-cambiar-clave',
                    data: obj,
                    success: function (xhr, txtSting) {

                        var _id = parseInt(xhr.id);

                        BaseX.dialogAceptar({
                            message: xhr.msg,
                            success: {
                                callback: function () {
                                    perfil_row_pass_detail_guardar.prop('disabled', false);
                                      if(_id > 0){
                                          document.location = _config.url_logout;
                                      }
                                }
                            }
                        });
                        perfil_row_pass_detail_guardar.prop('disabled', false);
                    }
                });

            });

            // Cambiar contraseña - Boton cancelar
            perfil_row_pass_detail_cancelar.off('click');
            perfil_row_pass_detail_cancelar.on('click', function (evt) {
                pefil_btn_cambiar_contrasena.show();
                div_row_cambio_contrasena.hide();
                row_cambio_contrasena_title.removeClass('row_selected');
            });
            // Fin contraseña Row
            
            //**************************************//
            //--Cambiar Correo Electrónico--INICIO--//
            //**************************************//
            
            var row_cambio_correo = jQuery('#row_cambio_correo');
            var pefil_btn_cambiar_correo = jQuery('#pefil_btn_cambiar_correo');
            var div_row_cambio_correo = jQuery('#div_row_cambio_correo');            
            var perfil_row_correo_cancelar = jQuery('#perfil_row_correo_cancelar');
            var perfil_row_correo_guardar = jQuery('#perfil_row_correo_guardar');
            
            div_row_cambio_correo.hide();
            pefil_btn_cambiar_correo.off('click');
            pefil_btn_cambiar_correo.on('click', function (evt) {
                evt.preventDefault();
                row_cambio_correo.addClass('row_selected');
                pefil_btn_cambiar_correo.hide();
                div_row_cambio_correo.show();
            });
            
            perfil_row_correo_guardar.off('click');
            perfil_row_correo_guardar.on('click', function (evt) {

                perfil_row_correo_guardar.prop('disabled', true);
                
                var correo_old = jQuery('#perfil_correo_old');
                var correo_pri = jQuery('#perfil_correo_nuevo');
                var correo_ver = jQuery('#perfil_correo_nuevo_confir');

                var obj = {
                    correo_old: correo_old.val(),
                    correo_pri: correo_pri.val(),
                    correo_ver: correo_ver.val()
                };
                
                BaseX.post({
                    url: root + '/usuario/perfil/perfil-cambiar-correo',
                    data: obj,
                    success: function (xhr, txtSting) {                        
                        var _id = parseInt(xhr.id);
                        BaseX.dialogAceptar({
                            message: xhr.msg,
                            success: {
                                callback: function () {
                                    perfil_row_correo_guardar.prop('disabled', false);                                    
                                        if(_id > 0){
                                            //document.location = _config.url_logout;
                                        }
                                }
                            }
                        });
                        perfil_row_correo_guardar.prop('disabled', false);
                    }
                });
            });
            
            perfil_row_correo_cancelar.off('click');
            perfil_row_correo_cancelar.on('click', function (evt) {
                pefil_btn_cambiar_correo.show();
                div_row_cambio_correo.hide();
                row_cambio_correo.removeClass('row_selected');
            });
            //****************************************//
            //== Cambiar Correo Electrónico --- FIN ==//
            //****************************************//
            
            //****************************************//
            //== Cambiar Celular Usuario --- INICIO ==//
            //****************************************//
            
            var row_cambio_celular = jQuery('#row_cambio_celular');
            var pefil_btn_cambiar_celular = jQuery('#pefil_btn_cambiar_celular');
            var div_row_cambio_celular = jQuery('#div_row_cambio_celular');            
            var perfil_row_celular_cancelar = jQuery('#perfil_row_celular_cancelar');
            var perfil_row_celular_guardar = jQuery('#perfil_row_celular_guardar');
            
            div_row_cambio_celular.hide();
            pefil_btn_cambiar_celular.off('click');
            pefil_btn_cambiar_celular.on('click', function (evt) {
                evt.preventDefault();
                row_cambio_celular.addClass('row_selected');
                pefil_btn_cambiar_celular.hide();
                div_row_cambio_celular.show();
            });
            
            perfil_row_celular_guardar.off('click');
            perfil_row_celular_guardar.on('click', function (evt) {

                perfil_row_celular_guardar.prop('disabled', true);
                
                var celular_old = jQuery('#perfil_celular_old');
                var celular_pri = jQuery('#perfil_celular_nuevo');
                var celular_ver = jQuery('#perfil_celular_nuevo_confir');

                var obj = {
                    celular_old: celular_old.val(),
                    celular_pri: celular_pri.val(),
                    celular_ver: celular_ver.val()
                };
                
                BaseX.post({
                    url: root + '/usuario/perfil/perfil-cambiar-celular',
                    data: obj,
                    success: function (xhr, txtSting) {                        
                        var _id = parseInt(xhr.id);
                        BaseX.dialogAceptar({
                            message: xhr.msg,
                            success: {
                                callback: function () {
                                    perfil_row_celular_guardar.prop('disabled', false);                                    
                                        if(_id > 0){
                                            //document.location = _config.url_logout;
                                        }
                                }
                            }
                        });
                        perfil_row_celular_guardar.prop('disabled', false);
                    }
                });
            });
            
            perfil_row_celular_cancelar.off('click');
            perfil_row_celular_cancelar.on('click', function (evt) {
                pefil_btn_cambiar_celular.show();
                div_row_cambio_celular.hide();
                row_cambio_celular.removeClass('row_selected');
            });

            //****************************************//  
            //== Cambiar Celular Usuario ------ FIN ==//
            //****************************************//
            
            //****************************************//
            //== Cambiar Telefono Usuario -- INICIO ==//
            //****************************************//
            
            var row_cambio_telefono = jQuery('#row_cambio_telefono');
            var pefil_btn_cambiar_telefono = jQuery('#pefil_btn_cambiar_telefono');
            var div_row_cambio_telefono = jQuery('#div_row_cambio_telefono');            
            var perfil_row_telefono_cancelar = jQuery('#perfil_row_telefono_cancelar');
            var perfil_row_telefono_guardar = jQuery('#perfil_row_telefono_guardar');
            
            div_row_cambio_telefono.hide();
            pefil_btn_cambiar_telefono.off('click');
            pefil_btn_cambiar_telefono.on('click', function (evt) {
                evt.preventDefault();
                row_cambio_telefono.addClass('row_selected');
                pefil_btn_cambiar_telefono.hide();
                div_row_cambio_telefono.show();
            });
            
            perfil_row_telefono_guardar.off('click');
            perfil_row_telefono_guardar.on('click', function (evt) {

                perfil_row_telefono_guardar.prop('disabled', true);
                
                var telefono_old = jQuery('#perfil_telefono_old');
                var telefono_pri = jQuery('#perfil_telefono_nuevo');

                var obj = {
                    telefono_old: telefono_old.val(),
                    telefono_pri: telefono_pri.val()
                };
                
                BaseX.post({
                    url: root + '/usuario/perfil/perfil-cambiar-telefono',
                    data: obj,
                    success: function (xhr, txtSting) {                        
                        var _id = parseInt(xhr.id);
                        BaseX.dialogAceptar({
                            message: xhr.msg,
                            success: {
                                callback: function () {
                                    perfil_row_telefono_guardar.prop('disabled', false);                                    
                                        if(_id > 0){
                                            //document.location = _config.url_logout;
                                        }
                                }
                            }
                        });
                        perfil_row_telefono_guardar.prop('disabled', false);
                    }
                });
            });
            
            perfil_row_telefono_cancelar.off('click');
            perfil_row_telefono_cancelar.on('click', function (evt) {
                pefil_btn_cambiar_telefono.show();
                div_row_cambio_telefono.hide();
                row_cambio_telefono.removeClass('row_selected');
            });

            //****************************************//  
            //== Cambiar Telefono Usuario ----- FIN ==//
            //****************************************//

            //**************************************************//
            //== Cambiar Celular Alternativo Usuario - INICIO ==//
            //**************************************************//
            
            var row_cambio_celular_alt = jQuery('#row_cambio_celular_alternativo');
            var pefil_btn_cambiar_celular_alt = jQuery('#pefil_btn_cambiar_celular_alternativo');
            var div_row_cambio_celular_alt = jQuery('#div_row_cambio_celular_alternativo');            
            var perfil_row_celular_alt_cancelar = jQuery('#perfil_row_celular_alternativo_cancelar');
            var perfil_row_celular_alt_guardar = jQuery('#perfil_row_celular_alternativo_guardar');
            
            div_row_cambio_celular_alt.hide();
            pefil_btn_cambiar_celular_alt.off('click');
            pefil_btn_cambiar_celular_alt.on('click', function (evt) {
                evt.preventDefault();
                row_cambio_celular_alt.addClass('row_selected');
                pefil_btn_cambiar_celular_alt.hide();
                div_row_cambio_celular_alt.show();
            });
            
            perfil_row_celular_alt_guardar.off('click');
            perfil_row_celular_alt_guardar.on('click', function (evt) {

                perfil_row_celular_alt_guardar.prop('disabled', true);
                
                var celular_alt_old = jQuery('#perfil_celular_alternativo_old');
                var celular_alt_pri = jQuery('#perfil_celular_alternativo_nuevo');

                var obj = {
                    celular_alt_old: celular_alt_old.val(),
                    celular_alt_pri: celular_alt_pri.val()
                };
                
                BaseX.post({
                    url: root + '/usuario/perfil/perfil-cambiar-celular-alt',
                    data: obj,
                    success: function (xhr, txtSting) {                        
                        var _id = parseInt(xhr.id);
                        BaseX.dialogAceptar({
                            message: xhr.msg,
                            success: {
                                callback: function () {
                                    perfil_row_celular_alt_guardar.prop('disabled', false);                                    
                                        if(_id > 0){
                                            //document.location = _config.url_logout;
                                        }
                                }
                            }
                        });
                        perfil_row_celular_alt_guardar.prop('disabled', false);
                    }
                });
            });
            
            perfil_row_celular_alt_cancelar.off('click');
            perfil_row_celular_alt_cancelar.on('click', function (evt) {
                pefil_btn_cambiar_celular_alt.show();
                div_row_cambio_celular_alt.hide();
                row_cambio_celular_alt.removeClass('row_selected');
            });

            //***********************************************//  
            //== Cambiar Celular Alternativo Usuario - FIN ==//
            //***********************************************//
            
            
            /*
             * ****************************
             * Perfil de Informacion adicional
             * ****************************
             * Correo alternativo
             *
             */

            var pefil_adicional_btn_cambiar_email = jQuery('#pefil_adicional_btn_cambiar_email');

            // Cambio de correo
            var div_row_cambio_correo_alternativo = jQuery('#div_row_cambio_correo_alternativo');
            var row_cambio_correo_alternativo_title = jQuery('#row_cambio_correo_alternativo_title');
            var perfil_row_pass_correo_alter_cancelar = jQuery('#perfil_row_pass_correo_alter_cancelar');

            div_row_cambio_correo_alternativo.hide();
            /* Cambio de correo */
            pefil_adicional_btn_cambiar_email.off('click');
            pefil_adicional_btn_cambiar_email.on('click', function (evt) {
                evt.preventDefault();
                row_cambio_correo_alternativo_title.addClass('row_selected');
                pefil_adicional_btn_cambiar_email.hide();
                div_row_cambio_correo_alternativo.show();
            });

            // Cambiar correo alternativo - Boton cancelar
            perfil_row_pass_correo_alter_cancelar.off('click');
            perfil_row_pass_correo_alter_cancelar.on('click', function (evt) {
                row_cambio_correo_alternativo_title.removeClass('row_selected');
                pefil_adicional_btn_cambiar_email.show();
                div_row_cambio_correo_alternativo.hide();
            });

            // Cambiar correo alternativo - Boton guardar

            var perfil_row_pass_correo_alter_guardar = jQuery('#perfil_row_pass_correo_alter_guardar');

            perfil_row_pass_correo_alter_guardar.off('click');
            perfil_row_pass_correo_alter_guardar.on('click', function (evt) {

                bootbox.confirm({
                    title: '<b>Confirmación</b>',
                    message: 'Desea actualizar su correo electrónico alternativo',
                    buttons: {
                        'cancel': {
                            label: 'No',
                            className: 'btn-default pull-left'
                        },
                        'confirm': {
                            label: 'Sí',
                            className: 'btn-danger pull-right'
                        }
                    },
                    callback: function (result) {
                        if (result) {

                            perfil_row_pass_correo_alter_guardar.prop('disabled', true);

                            var correo_alternativo = jQuery('#perfil_cambio_correo_alternativo');
                            var obj = {
                                correo_alternativo: correo_alternativo.val(),
                            };

                            BaseX.post({
                                url: root + '/usuario/perfil/perfil-cambiar-correo-alternativo',
                                data: obj,
                                success: function (xhr, txtSting) {
                                    var _id = parseInt(xhr.id);
                                    BaseX.dialogAceptar({
                                        message: xhr.msg,
                                        success: {
                                            callback: function () {
                                                perfil_row_pass_correo_alter_guardar.prop('disabled', false);
                                                  if(_id > 0){
                                                      load_menu_datos_usuario();
                                                  }
                                            }
                                        }
                                    });
                                    perfil_row_pass_correo_alter_guardar.prop('disabled', false);
                                }
                            });

                        }
                    }
                });

            });

            /*
             * ****************************
             * Perfil de Contacto de Referencia
             * ****************************
             * Datos de referencia
             *
             */

//            jQuery('#perfil_cambio_nombre_referencia').filter_input({regex: '[a-zA-Z]'});
//            jQuery('#perfil_cambio_celular_referencia').filter_input({regex: '[0-9]'});

            var pefil_adicional_ref_btn_cambiar_nombre = jQuery('#pefil_adicional_ref_btn_cambiar_nombre');

            // Cambio de nombre
            var div_row_cambio_nombre_referencia = jQuery('#div_row_cambio_nombre_referencia');
            var row_cambio_nombre_referencia_title = jQuery('#row_cambio_nombre_referencia_title');
            var perfil_row_pass_nombre_ref_cancelar = jQuery('#perfil_row_pass_nombre_ref_cancelar');

            div_row_cambio_nombre_referencia.hide();
            /* Cambio de nombre */
            pefil_adicional_ref_btn_cambiar_nombre.off('click');
            pefil_adicional_ref_btn_cambiar_nombre.on('click', function (evt) {
                evt.preventDefault();
                row_cambio_nombre_referencia_title.addClass('row_selected');
                pefil_adicional_ref_btn_cambiar_nombre.hide();
                div_row_cambio_nombre_referencia.show();
            });

            // Cambiar nombre referencia - Boton cancelar
            perfil_row_pass_nombre_ref_cancelar.off('click');
            perfil_row_pass_nombre_ref_cancelar.on('click', function (evt) {
                row_cambio_nombre_referencia_title.removeClass('row_selected');
                pefil_adicional_ref_btn_cambiar_nombre.show();
                div_row_cambio_nombre_referencia.hide();
            });

            // Cambiar nombre - Boton guardar

            var perfil_row_pass_nombre_ref_guardar = jQuery('#perfil_row_pass_nombre_ref_guardar');

            perfil_row_pass_nombre_ref_guardar.off('click');
            perfil_row_pass_nombre_ref_guardar.on('click', function (evt) {

                bootbox.confirm({
                    title: '<b>Confirmación</b>',
                    message: 'Desea actualizar el nombre de contacto de referencia',
                    buttons: {
                        'cancel': {
                            label: 'No',
                            className: 'btn-default pull-left'
                        },
                        'confirm': {
                            label: 'Sí',
                            className: 'btn-danger pull-right'
                        }
                    },
                    callback: function (result) {
                        if (result) {

                            perfil_row_pass_nombre_ref_guardar.prop('disabled', true);

                            var nombre_referencia = jQuery('#perfil_cambio_nombre_referencia');
                            var obj = {
                                nombre_referencia: nombre_referencia.val(),
                            };

                            BaseX.post({
                                url: root + '/usuario/perfil/perfil-cambiar-nombre-referencia',
                                data: obj,
                                success: function (xhr, txtSting) {
                                    var _id = parseInt(xhr.id);
                                    BaseX.dialogAceptar({
                                        message: xhr.msg,
                                        success: {
                                            callback: function () {
                                                perfil_row_pass_nombre_ref_guardar.prop('disabled', false);
                                                  if(_id > 0){
                                                      load_menu_datos_usuario();
                                                  }
                                            }
                                        }
                                    });
                                    perfil_row_pass_nombre_ref_guardar.prop('disabled', false);
                                }
                            });

                        }
                    }
                });

            });


            /*********************************************************/

            var pefil_adicional_ref_btn_cambiar_num_cel = jQuery('#pefil_adicional_ref_btn_cambiar_num_cel');

            // Cambio de celular
            var div_row_cambio_celular_referencia = jQuery('#div_row_cambio_celular_referencia');
            var row_cambio_celular_referencia_title = jQuery('#row_cambio_celular_referencia_title');
            var perfil_row_pass_celular_ref_cancelar = jQuery('#perfil_row_pass_celular_ref_cancelar');

            div_row_cambio_celular_referencia.hide();
            /* Cambio de celular */
            pefil_adicional_ref_btn_cambiar_num_cel.off('click');
            pefil_adicional_ref_btn_cambiar_num_cel.on('click', function (evt) {
                evt.preventDefault();
                row_cambio_celular_referencia_title.addClass('row_selected');
                pefil_adicional_ref_btn_cambiar_num_cel.hide();
                div_row_cambio_celular_referencia.show();
            });

            // Cambiar celular referencia - Boton cancelar
            perfil_row_pass_celular_ref_cancelar.off('click');
            perfil_row_pass_celular_ref_cancelar.on('click', function (evt) {
                row_cambio_celular_referencia_title.removeClass('row_selected');
                pefil_adicional_ref_btn_cambiar_num_cel.show();
                div_row_cambio_celular_referencia.hide();
            });

            // Cambiar nombre - Boton guardar

            var perfil_row_pass_celular_ref_guardar = jQuery('#perfil_row_pass_celular_ref_guardar');

            perfil_row_pass_celular_ref_guardar.off('click');
            perfil_row_pass_celular_ref_guardar.on('click', function (evt) {

                bootbox.confirm({
                    title: '<b>Confirmación</b>',
                    message: 'Desea actualizar el número de celular de referencia',
                    buttons: {
                        'cancel': {
                            label: 'No',
                            className: 'btn-default pull-left'
                        },
                        'confirm': {
                            label: 'Sí',
                            className: 'btn-danger pull-right'
                        }
                    },
                    callback: function (result) {
                        if (result) {

                            perfil_row_pass_celular_ref_guardar.prop('disabled', true);

                            var celular_referencia = jQuery('#perfil_cambio_celular_referencia');
                            var obj = {
                                celular_referencia: celular_referencia.val(),
                            };

                            BaseX.post({
                                url: root + '/usuario/perfil/perfil-cambiar-celular-referencia',
                                data: obj,
                                success: function (xhr, txtSting) {
                                    var _id = parseInt(xhr.id);
                                    BaseX.dialogAceptar({
                                        message: xhr.msg,
                                        success: {
                                            callback: function () {
                                                perfil_row_pass_celular_ref_guardar.prop('disabled', false);
                                                  if(_id > 0){
                                                      load_menu_datos_usuario();
                                                  }
                                            }
                                        }
                                    });
                                    perfil_row_pass_celular_ref_guardar.prop('disabled', false);
                                }
                            });

                        }
                    }
                });

            });

            /*********************************************************/

            var pefil_adicional_ref_btn_cambiar_num_tel = jQuery('#pefil_adicional_ref_btn_cambiar_num_tel');

            // Cambio de telefono fijo referencia
            var div_row_cambio_telefono_referencia = jQuery('#div_row_cambio_telefono_referencia');
            var row_cambio_telefono_referencia_title = jQuery('#row_cambio_telefono_referencia_title');
            var perfil_row_pass_telefono_ref_cancelar = jQuery('#perfil_row_pass_telefono_ref_cancelar');

            div_row_cambio_telefono_referencia.hide();
            /* Cambio de telefono fijo referencia */
            pefil_adicional_ref_btn_cambiar_num_tel.off('click');
            pefil_adicional_ref_btn_cambiar_num_tel.on('click', function (evt) {
                evt.preventDefault();
                row_cambio_telefono_referencia_title.addClass('row_selected');
                pefil_adicional_ref_btn_cambiar_num_tel.hide();
                div_row_cambio_telefono_referencia.show();
            });

            // Cambiar telefono fijo referencia - Boton cancelar
            perfil_row_pass_telefono_ref_cancelar.off('click');
            perfil_row_pass_telefono_ref_cancelar.on('click', function (evt) {
                row_cambio_telefono_referencia_title.removeClass('row_selected');
                pefil_adicional_ref_btn_cambiar_num_tel.show();
                div_row_cambio_telefono_referencia.hide();
            });

            // Cambiar telefono fijo referencia - Boton guardar

            var perfil_row_pass_telefono_ref_guardar = jQuery('#perfil_row_pass_telefono_ref_guardar');

            perfil_row_pass_telefono_ref_guardar.off('click');
            perfil_row_pass_telefono_ref_guardar.on('click', function (evt) {

                bootbox.confirm({
                    title: '<b>Confirmación</b>',
                    message: 'Desea actualizar el número de teléfono fijo de referencia',
                    buttons: {
                        'cancel': {
                            label: 'No',
                            className: 'btn-default pull-left'
                        },
                        'confirm': {
                            label: 'Sí',
                            className: 'btn-danger pull-right'
                        }
                    },
                    callback: function (result) {
                        if (result) {

                            perfil_row_pass_telefono_ref_guardar.prop('disabled', true);

                            var telefono_referencia = jQuery('#perfil_cambio_telefono_referencia');
                            var obj = {
                                telefono_referencia: telefono_referencia.val(),
                            };

                            BaseX.post({
                                url: root + '/usuario/perfil/perfil-cambiar-telefono-referencia',
                                data: obj,
                                success: function (xhr, txtSting) {
                                    var _id = parseInt(xhr.id);
                                    BaseX.dialogAceptar({
                                        message: xhr.msg,
                                        success: {
                                            callback: function () {
                                                perfil_row_pass_telefono_ref_guardar.prop('disabled', false);
                                                  if(_id > 0){
                                                      load_menu_datos_usuario();
                                                  }
                                            }
                                        }
                                    });
                                    perfil_row_pass_telefono_ref_guardar.prop('disabled', false);
                                }
                            });

                        }
                    }
                });

            });

        }
    });
}
