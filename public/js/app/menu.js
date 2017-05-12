function menu() {
    menu_instrumento();

    var left_menu = jQuery('.nav-stacked li');
    left_menu.off('click').on('click', function (evt) {
        var _this = jQuery(this);
        var _function_handle = _this.data('function-handle');
        eval(_function_handle + '()');
    });

}

function menu_inicio() {
    jQuery('.nav-stacked li').removeClass('active');
    jQuery('#mnuInicio').addClass('active');
    load_inicio();
}

function menu_reportes() {
    jQuery('.nav-stacked li').removeClass('active');
    jQuery('#menuReportes').addClass('active');
    load_reportes();
}

function menu_instrumento() {

    jQuery('.nav-stacked li').removeClass('active');
    jQuery('#menuInstrumento').addClass('active');

    load_instrumentos();
}

function load_inicio() {
    BaseX.load_html(root + '/seleccion/index/inicio', {
        data: {},
        success: function (xhr) {
            jQuery('#workArea').html(xhr);
        }
    });
}
function load_reportes() {
    BaseX.load_html(root + '/seleccion/index/reportes', {
        data: {},
        success: function (xhr) {
            jQuery('#workArea').html(xhr);
        }
    });
}

function load_instrumentos() {
    BaseX.load_html(root + '/seleccion/index/instrumentos', {
        data: {},
        success: function (xhr) {
            jQuery('#workArea').html(xhr);
//            jQuery("#grid").GridUnload();
            grid_instrumentos();
        }
    });
}


function grid_instrumentos()
{
    jQuery("#grid_instrumentos").jqGrid({
        url: root + '/seleccion/index/get-data-grilla',
        datatype: "json",
        height: 250,
        width: 800,
        colNames: ['Id', 'Nombre de instrumento', 'Meta', 'Informantes', 'Estado', 'Acciones'],
        colModel: [
            {name: 'id_instrumento', index: 'id_instrumento', width: 10, align: "left", sortable: true, hidden: true},
            {name: 'nombre', index: 'nombre', width: 300, align: "left", sortable: true},
            {name: 'meta', index: 'meta', width: 90, sortable: false},
            {name: 'informantes', index: 'informantes', width: 200, sortable: true},
            {name: 'estado', index: 'estado', width: 90, align: "center", formatter: estadoINS, sortable: false},
            {name: 'id_instrumento', index: 'id_instrumento', width: 100, formatter: agregarIE, align: "center", sortable: false},
        ],
        caption: "",
        rownumbers: false,
        rowNum: 50,
        rowList: [10, 20, 30, 50, 100, 200],
        pager: '#pager_grid_instrumentos',
        sortname: 'id_instrumento',
        viewrecords: true,
        hidegrid: false,
        resizable: true,
        ondblClickRow: function (rowid)
        {
            grid_instrumentos_detail(rowid);
        }
    });
}
;
function grid_instrumentos_detail(id)
{
    
//    [
//                'id_instrumento' => 1,
//                'nombre' => '9878987 Jesus Niño Lindo',
//                'informante' => 'Sanchez Alvarez Nelson',
//                'estado' => '0/20',
//            ],
//                    
                    
    jQuery("#grid_instrumentos_detail").jqGrid({
        url: root + '/seleccion/index/get-data-grilla-detail',
        datatype: "json",
        data: {
          id:id  
        },
        height: 250,
        width: 800,
        colNames: ['Id', 'Instituación educativa', 'Informantes (Nombres y apellidos)','Estado', 'Acciones'],
        colModel: [
            {name: 'id_instrumento', index: 'id_instrumento', width: 10, align: "left", sortable: true, hidden: true},
            {name: 'nombre', index: 'nombre', width: 250, align: "left", sortable: true},
            {name: 'informante', index: 'informante', width: 250, sortable: true},
            {name: 'estado', index: 'estado', width: 90, align: "center", formatter: estadoINS, sortable: false},
            {name: 'id_instrumento', index: 'id_instrumento', width: 100, formatter: agregarIE, align: "center", sortable: false},
        ],
        caption: "",
        rownumbers: false,
        rowNum: 50,
        rowList: [10, 20, 30, 50, 100, 200],
        pager: '#pager_grid_instrumentos_detail',
        sortname: 'id_instrumento',
        viewrecords: true,
        hidegrid: false,
        resizable: true,
        ondblClickRow: function (rowid)
        {
            alert('Llenar encuesta.'+rowid);
        }
    });
}
;

function agregarIE(cellvalue, options, rowObject) {
    return "<span class='glyphicon glyphicon-th-list'></span>";
}
function estadoINS(cellvalue, options, rowObject) {
    return "<span class='label label-success'>" + cellvalue + "</span>";
}
