function menu_presentacion() {
    
    load_menu_presentacion();
}

function load_menu_presentacion(ops) {
    if (ops === undefined) {
        ops = 0;
    }

    BaseX.load_html(root + '/dashboard/index/presentacion', {
        data: ops,
        success: function (xhr) {

            jQuery('#workArea').html(xhr);


        }
    });
}