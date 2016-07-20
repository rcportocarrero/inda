jQuery(document).on('ready', function () {

    menu();


});


function valida_sesion() {
    var rp = true;
    jQuery.ajaxSetup({
        cache: false,
        async: false
    });
    jQuery.ajax({
        url: root + '/usuario/sesion/valida',
        data: {
            neko: jQuery('#hdn_sesion_token').val(),
        },
        method: 'post',
        success: function (xhr, txt) {
            rp = xhr.activo;
            if (rp === false) {
                bootbox.dialog({
                    message: _strings.app.msg_sistema_termino_sesion,
                    title: "Mensaje del sistema",
                    closeButton: false,
                    buttons: {
                        success: {
                            label: "Aceptar",
                            className: "btn-naranja",
                            callback: function () {
                                document.location = root + '/usuario/sec/logout';
                            }
                        }
                    }
                });
            }

            if (rp === true) {
                if (xhr.ini === true) {
                    if (xhr.fin === true) {
                        bootbox.dialog({
                            message: _strings.app.msg_fin_proceso,
                            title: "Mensaje del sistema",
                            closeButton: false,
                            buttons: {
                                success: {
                                    label: "Aceptar",
                                    className: "btn-naranja",
                                    callback: function () {
                                        document.location = root + '/usuario/sec/logout';
                                    }
                                }
                            }
                        });
                        return false;
                    }
                } else {

                    bootbox.dialog({
                        message: _strings.app.msg_no_ini_proceso,
                        title: "Mensaje del sistema",
                        closeButton: false,
                        buttons: {
                            success: {
                                label: "Aceptar",
                                className: "btn-naranja",
                                callback: function () {
                                    document.location = root + '/usuario/sec/logout';
                                }
                            }
                        }
                    });
                    return false;
                }
            }
        }
    });

    return rp;
}