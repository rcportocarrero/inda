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
//                                    if(_id > 0){
                                    perfil_row_pass_detail_guardar.prop('disabled', false);
                                      if(_id > 0){
                                          
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



            pefil_adicional_ref_btn_cambiar_num_tel.off('click');
            pefil_adicional_ref_btn_cambiar_num_tel.on('click', function (evt) {
                evt.preventDefault();
            });


        }
    });
}