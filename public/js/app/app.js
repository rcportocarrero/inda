var _config = {
    url_valida_sesion:root + '/usuario/sesion/valida',
    url_logout: root + '/usuario/sec/logout'
};

//document.location = root + '/usuario/sec/logout';

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
        url: _config.url_valida_sesion,
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
                                document.location = _config.url_logout;
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
                                        document.location = _config.url_logout;
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
                                    document.location = _config.url_logout;
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