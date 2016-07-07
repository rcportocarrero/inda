jQuery(document).on('ready', function () {

    $('#frm_login').validate({
        rules: {
            dni: {required: true, number: true, minlength: min_dni, maxlength: max_dni},
            'password': {required: true, maxlength: max_clave},
            'captcha_login': {required: true, minlength: tam_captcha, number: false, }
        },
        messages: {
            'dni': {
                number: "Debe llenar este campo sólo con números.",
                required: "Debe llenar este campo.",
                maxlength: "No puede ingresar mas de " + max_dni + " dígitos.",
                minlength: "Debe ingresar " + min_dni + " dígitos como mínimo."
            }, 'password': {
                required: "Debe llenar este campo.",
                maxlength: "No puede ingresar mas de " + max_clave + " caracteres."
            }, 'captcha_login': {
                required: "Debe llenar este campo.",
                minlength: "Debe ingresar los " + tam_captcha + " caracteres de la imagen.",
                number: "Sólo debe ingresar letras."
            }
        },
        debug: true,
        submitHandler: function (form) {
        }
    });

    jQuery("#signIn_1").click(function () {
        var form = jQuery("#frm_login");
        if (form.valid()) {
            document.getElementById("frm_login").submit();
        }
    });

    jQuery('#captcha_login').filter_input({regex: '[a-zA-Z]'});
    jQuery('#captcha_recuperar').filter_input({regex: '[a-zA-Z]'});
    jQuery('#dni').filter_input({regex: '[0-9]'});
    jQuery('#captcha_registro').filter_input({regex: '[a-zA-Z]'});
    jQuery('#email_register').filter_input({regex: '[a-zA-Z0-9_.@a-zA-Z0-9_.a-zA-Z0-9]'});
    jQuery('#dni_register').filter_input({
        regex: '[0-9]',
        feedback: function (char) {
            return;
        }
    });

    $('#nombres_register').filter_input({regex: '[a-zA-Z\' ñÑáÁéÉíÍóÓúÚäÄëËïÏöÖüÜ\\-0-9]'});
    $('#ap_pat_register').filter_input({regex: '[a-zA-Z\' ñÑáÁéÉíÍóÓúÚäÄëËïÏöÖüÜ\\-0-9]'});
    $('#ap_mat_register').filter_input({regex: '[a-zA-Z\' ñÑáÁéÉíÍóÓúÚäÄëËïÏöÖüÜ\\-0-9]'});
    $('#email_register').filter_input({regex: '[a-zA-Z_0-9_\\-@a-zA-Z_0-9.a-zA-Z_0-9]'});
});

jQuery.fn.datepicker.dates['es'] = {
    days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"],
    daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"],
    daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa", "Do"],
    months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
    monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
    today: "Hoy"
};
jQuery('#fecha_nacimiento').datepicker({
    format: "dd/mm/yyyy",
    language: "es",
    autoclose: true,
});