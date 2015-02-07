<?php
$stylesheets = array(
    'datatables.css'
);
$scripts = array(
    'jquery.dataTables.min.js',
    'datatables.js',
    'jquery.validate.js'
);
?>

<?php include_once 'inc/head.php'; ?>
    <body>
        <style>
            .table th.center-text,
            .table td.center-text{
                text-align: center;
                vertical-align: middle;
            }
            .tab-content {overflow:hidden; }
            .dataTables_filter {
                display: none; 
            }
            table tbody tr.info td{
                background-color: #FFE142 !important;
            }
            .mr5{
                margin-right: 5px;
            }
        </style>

        <div class="container">
            <?php include_once 'inc/menu.php'; ?>
        <div class="panel panel-primary">
            <div class="panel-heading">Mantenimiento de Tabla: categoria</div>
            <div class="panel-body">
                <div class="well well-sm row clearfix">
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" aria-controls="tabla-alumno" placeholder="B&uacute;squeda" class="form-control input-md">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button"><i class="glyphicon glyphicon-search"></i></button>
                            </span>
                        </div><!-- /input-group -->
                    </div>
                    <div class="col-md-8">
                        <div class="pull-right">
                            <button type="button" class="btn btn-sm btn-primary" id="btn-nuevo"><i class="glyphicon glyphicon-file"></i> Nuevo </button>
                            <button type="button" class="btn btn-sm" id="btn-editar"><i class="glyphicon glyphicon-pencil"></i> Editar </button>
                            <button type="button" class="btn btn-sm" id="btn-eliminar"><i class="glyphicon glyphicon-trash"></i> Eliminar </button>
                            <button type="button" class="btn btn-sm btn-primary" id="btn-actualizar"><i class="glyphicon glyphicon-refresh"></i> Actualizar </button>
                        </div>
                    </div>
                </div>
                <table cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered table-condensed" id="tabla-categoria">
                        <thead>
                            <tr>
                                <th>categoria_id</th>
                                <th>Nombre de la Línea</th>
                                <th>Orden (En que aparecen listados)</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
            <?php include_once 'inc/footer.php';?>
        </div>

        <div class="modal fade" id="modal-confirmar-eliminar" tabindex="-1" role="dialog" aria-labelledby="modal-confirmar-eliminar-categoria-label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                         <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                         <h4 class="modal-title" id="modal-confirmar-eliminar-categoria-label">Confirmar eliminaci&oacute;n</h4>
                    </div>
                    <div class="modal-body">
                        <p>Estas a punto de eliminar un registro <strong>(<span class="nombre-registro"></span>)</strong>. Este proceso es irreversible.</p>
                        <p>Confirma la eliminaci&oacute;n?</p>
                        <p id="debug-url"></p>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="hdn-modal-idxrow"/>
                        <input type="hidden" id="hdn-modal-confirmar-eliminar-valor" name="categoria-id" value=""/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <a href="#" class="btn btn-danger" id="btn-confirmar-eliminar">Delete</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-nuevo-categoria" tabindex="-1" role="dialog" aria-labelledby="modal-nuevo-categoria-label" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                         <h4 class="modal-title" id="modal-nuevo-categoria-label">Registrar nuevo categoria</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                        <form class="form-horizontal" role="form" id="form-registrar-categoria" name="form-registrar-categoria" novalidate="novalidate" method="post" action="#">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="txt-categoria-nombre">Nombre:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="txt-categoria-nombre" name="categoria_nombre" placeholder="nombre">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="txt-categoria-orden">Orden:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="txt-categoria-orden" name="categoria_orden" placeholder="nombre">
                                            </div>
                                        </div>
                                              <input type="hidden" id="hdn-categoria-id" name="categoria_id"/>
                                              <input type="hidden" id="hdn-option" name="option" value=""/>
                                      </form>
                          </div>
                      </div>
                  </div><!-- /modal-body -->
                  <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                      <button id="btn-registrar" title="Registrar categoria" type="button" class="btn btn-primary">Registrar</button>
                  </div>
              </div>
          </div>
        </div>

        <?php include_once 'inc/scripts.php';?>
        <link rel="stylesheet" href="css/redmond/jquery-ui-1.10.4.custom.min.css"/>
        <script type="text/javascript" src="js/jquery-ui-1.10.4.custom.min.js"></script>
        <script type="text/javascript">
            $(function() {
                //Esto es para evitar que cuando den enter los mande a otra pagina
                $("form").submit(function(evt){evt.preventDefault();});
                var dt_options = {
                    "sPaginationType": "bs_full",
                    "bProcessing": true,
                    "aoColumns": [
                        {"mDataProp": "categoria_id", "bSearchable": false, "bVisible": false},
                        {"mDataProp": "categoria_nombre", "bSearchable": true},
                        {"mDataProp": "categoria_orden", "bSearchable": true},
                    ],
                    "bServerSide": true,
                    "sAjaxSource": "ajax_mantenimiento.php?option=listar_categorias",
                };

                var $tabla_categoria     = $("#tabla-categoria");
                var $dt_tabla_categoria = $tabla_categoria.dataTable(dt_options);

                function obj2array(myObj){
                    var array = $.map(myObj, function(k, v) {
                        return [k];
                    });
                    return array;
                }

                $("#tabla-categoria tbody").on("click", "tr", function( e ) {
                    if ( $(this).hasClass('info') ) {
                        $(this).removeClass('info');
                    }else {
                        $dt_tabla_categoria.$('tr.info').removeClass('info');
                        $(this).addClass('info');
                    }
                });

                function fnClickAgregarFilaDT(obj) {
                    var addedRow = $dt_tabla_categoria.fnAddData(obj);
                    var nTr = $dt_tabla_categoria.fnSettings().aoData[addedRow[0]].nTr;
                    return $(nTr);
                }

                var $modal_nuevo_categoria = $("#modal-nuevo-categoria");
                var $modal_confirmar_eliminar = $("#modal-confirmar-eliminar");

                $("#btn-actualizar").click(function(evt){
                    evt.preventDefault();
                    $dt_tabla_categoria.fnStandingRedraw();
                });

                $("#btn-nuevo").click(function(evt){
                    evt.preventDefault();
                    $("#hdn-option").val("registrar_categoria");
                    $("#btn-registrar").html("Registrar");
                    $("#modal-nuevo-categoria").modal("show");
                    $("#form-registrar-categoria").get(0).reset();
                });

                $("#btn-eliminar").click(function(evt){
                    var anSelected = fnGetSelected( $dt_tabla_categoria );
                    if ( anSelected.length !== 0 ) {
                        evt.preventDefault();
                        var descripcion = $dt_tabla_categoria.fnGetData(anSelected[0], 1);
                        $('html, body').animate({
                            scrollTop: $(".masthead").offset().top - 60
                         }, 200);
                        $modal_confirmar_eliminar.modal("show");
                        $modal_confirmar_eliminar.find("span.nombre-registro").html(descripcion);
                    }else{
                        alert("Debe seleccionar un registro de la lista!");
                    }
                });

                $("#btn-confirmar-eliminar").click(function(evt){
                    var anSelected = fnGetSelected( $dt_tabla_categoria );
                    var id = $dt_tabla_categoria.fnGetData(anSelected[0], 0);
                    $.post(
                        'ajax_mantenimiento.php',
                        {
                            'option':'eliminar_categoria',
                            'categoria_id':id
                        },
                        function(data){
                            if(data.success){
                                $dt_tabla_categoria.fnDeleteRow( anSelected[0] );
                                $modal_confirmar_eliminar.modal("hide");
                                $('html, body').animate({
                                    scrollTop: $("#tabla-categoria").offset().top - 60
                                }, 200);
                            }else{
                                alert("Error al eliminar registro");
                            }
                        },
                        'json'
                    );
                });

                $("#btn-editar").click(function(){
                    var anSelected = fnGetSelected( $dt_tabla_categoria );
                    if (anSelected.length !== 0) {
                        var id = $dt_tabla_categoria.fnGetData(anSelected[0], 0);
                        $("#hdn-categoria-id").val(id);
                        $.post(
                            'ajax_mantenimiento.php',
                            {
                                'option':'editar_categoria',
                                'categoria_id':id
                            },
                            function(data){
                                if(data.categoria){
                                    var x = data.categoria;
                                    $("#hdn-categoria-id").val(x.categoria_id);
                                    $("#txt-categoria-nombre").val(x.categoria_nombre);
                                    $("#txt-categoria-orden").val(x.categoria_orden);
                                    $("#hdn-option").val("actualizar_categoria");

                                    $("#btn-registrar").html("Actualizar");
                                    $("#modal-nuevo-categoria").modal("show");
                                }else{
                                    alert("Error al cargar datos del registro");
                                }
                            },
                            'json'
                        );
                    }else{
                        alert("Debe seleccionar un registro de la lista!");
                    }
                });

                $("#btn-registrar").click(function(){
                    $form_registrar_categoria.trigger("submit");
                });

                /****************************************************/
                /****************************************************/
                var $txt_filter_categoria = $("#txt-filter-categoria");
                $txt_filter_categoria.keyup(function(e){
                    if (e.which <= 90 && e.which >= 48){
                        if ( this.value.length>2 ) {
                            $dt_tabla_categoria.fnFilter( this.value);
                        }
                    }
                });

                $("#btn-mostrar-todo").click(function(){
                    $txt_filter_categoria.val("");
                    $dt_tabla_categoria.fnFilter( this.value);
                });

                var $form_registrar_categoria = $("#form-registrar-categoria");
                $form_registrar_categoria.validate({
                    rules: {
                        'categoria_nombre':{
                            required: true
                        },
                        'categoria_orden':{
                            required: true
                        },
                    },
                    focusCleanup: false,
                    highlight: function(label) {
                        $(label).closest('.control-group').removeClass ('success').addClass('error');
                    },
                    success: function(label) {
                        label
	    		        .text('OK!').addClass('valid')
	    		        .closest('.control-group').addClass('success');
                    },
                    errorPlacement: function(error, element) {
                        error.appendTo( element.parents ('.controls') );
                    },
                    submitHandler: function(form){
                        var $form = $(form);
                        $.post(
                            'ajax_mantenimiento.php',
                            $form.serialize(),
                            function(data){
                                if($("#hdn-option").val() == "actualizar_categoria"){
                                    if(data.success){
                                        $modal_nuevo_categoria.modal("hide");
                                        $dt_tabla_categoria.fnStandingRedraw();
                                    }else{
                                        alert("Error al actualizar registro");
                                    }
                                }else{
                                    if(data.categoria_id){
                                        $modal_nuevo_categoria.modal("hide");
                                        $dt_tabla_categoria.fnStandingRedraw();
                                    }else{
                                        alert("Error al registrar categoria.");
                                    }
                                }
                                form.reset();
                            },
                            'json'
                        );
                    }
                });

                function fnGetSelected( oTableLocal ){
                    return oTableLocal.$("tr.info");
                }
            });
        </script>
    </body>
</html>