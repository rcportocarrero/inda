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
            
            var div_row_cambio_contrasena = jQuery('#div_row_cambio_contrasena');
            
            div_row_cambio_contrasena.hide();
            /* Cambio de contraseña */
            pefil_btn_cambiar_contrasena.off('click');
            pefil_btn_cambiar_contrasena.on('click', function (evt) {
                evt.preventDefault();

                var row_cambio_contrasena_title = jQuery('#row_cambio_contrasena_title');

                row_cambio_contrasena_title.addClass('row_selected');
                pefil_btn_cambiar_contrasena.hide();
                div_row_cambio_contrasena.show();
                

            });



            pefil_adicional_ref_btn_cambiar_num_tel.off('click');
            pefil_adicional_ref_btn_cambiar_num_tel.on('click', function (evt) {
                evt.preventDefault();
            });


        }
    });
}