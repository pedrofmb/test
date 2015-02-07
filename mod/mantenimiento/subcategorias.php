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
                <div class="panel-heading">Mantenimiento de Tabla: subcategoria</div>
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
                    <table cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered table-condensed" id="tabla-subcategoria">
                        <thead>
                            <tr>
                                <th>subcategoria_id</th>
                                <th>Subcategoria</th>
                                <th>Categoria</th>
                                <th>Ruta</th>
                                <th>Subcategoria Padre</th>
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

        <div class="modal fade" id="modal-confirmar-eliminar" tabindex="-1" role="dialog" aria-labelledby="modal-confirmar-eliminar-subcategoria-label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="modal-confirmar-eliminar-subcategoria-label">Confirmar eliminaci&oacute;n</h4>
                    </div>
                    <div class="modal-body">
                        <p>Estas a punto de eliminar un registro <strong>(<span class="nombre-registro"></span>)</strong>. Este proceso es irreversible.</p>
                        <p>Confirma la eliminaci&oacute;n?</p>
                        <p id="debug-url"></p>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="hdn-modal-idxrow"/>
                        <input type="hidden" id="hdn-modal-confirmar-eliminar-valor" name="subcategoria-id" value=""/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <a href="#" class="btn btn-danger" id="btn-confirmar-eliminar">Delete</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-nuevo-subcategoria" tabindex="-1" role="dialog" aria-labelledby="modal-nuevo-subcategoria-label" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                         <h4 class="modal-title" id="modal-nuevo-subcategoria-label">Registrar nuevo subcategoria</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                        <form class="form-horizontal" role="form" id="form-registrar-subcategoria" name="form-registrar-subcategoria" novalidate="novalidate" method="post" action="#">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="txt-subcategoria-nombre">Nombre:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="txt-subcategoria-nombre" name="subcategoria_nombre" placeholder="nombre">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="txt-subcategoria-ruta">Ruta:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="txt-subcategoria-ruta" name="subcategoria_ruta" placeholder="Debe ser sin espacios ni caracteres especiales">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="txt-categoria-id">Categoria:</label>
                                            <div class="col-sm-8">
                                                <select id="cmb-categoria-id" name="categoria_id" class="form-control">
                                                    <option value="0">Seleccionar ...</option>
                                                    <?php foreach($categorias["categorias"] as $categoria):?>
                                                    <option value="<?php echo $categoria->categoria_id;?>"><?php echo $categoria->categoria_nombre?></option>
                                                    <?php endforeach;?>
                                                </select>                                                
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="txt-subcategoria-padre">Subcategoria Padre:</label>
                                            <div class="col-sm-8">
                                                <select id="cmb-subcategoria-padre" name="subcategoria_padre" class="form-control">
                                                    <option value="0">Ninguna</option>                                                    
                                                </select>
                                            </div>
                                        </div>
                                              <input type="hidden" id="hdn-subcategoria-id" name="subcategoria_id"/>
                                              <input type="hidden" id="hdn-option" name="option" value=""/>
                                      </form>
                          </div>
                      </div>
                  </div><!-- /modal-body -->
                  <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                      <button id="btn-registrar" title="Registrar subcategoria" type="button" class="btn btn-primary">Registrar</button>
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
                        {"mDataProp": "subcategoria_id", "bSearchable": false, "bVisible": false},
                        {"mDataProp": "subcategoria_nombre", "bSearchable": true},
                        {"mDataProp": "categoria_id", "bSearchable": false},
                        {"mDataProp": "subcategoria_ruta", "bSearchable": false},
                        {"mDataProp": "subcategoria_padre", "bSearchable": false}
                    ],
                    "bServerSide": true,
                    "sAjaxSource": "ajax_mantenimiento.php?option=listar_subcategorias",
                    "aoColumnDefs": [
                        {
                            "fnRender": function ( oObj ) {                                
                                return oObj.aData["obj_categoria"]["categoria_nombre"];
                            },                            
                            "aTargets": [2]
                        },
                        {
                            "fnRender": function ( oObj ) {
                                if(oObj.aData["obj_subcategoriapadre"]){
                                    return oObj.aData["obj_subcategoriapadre"]["subcategoria_nombre"];                                    
                                }else{
                                    return "-";
                                }
                            },                            
                            "aTargets": [4]
                        }
                    ]
                };

                var $tabla_subcategoria     = $("#tabla-subcategoria");
                var $dt_tabla_subcategoria = $tabla_subcategoria.dataTable(dt_options);

                function obj2array(myObj){
                    var array = $.map(myObj, function(k, v) {
                        return [k];
                    });
                    return array;
                }
                
                $("#cmb-categoria-id").change(function(){
                    var idcategoria = $(this).val()*1;
                    if(idcategoria == 0){
                        return;
                    }
                    $.post(
                        'ajax_mantenimiento.php',
                        {'option': 'listar_subcategoria_padre', 'categoria_id': idcategoria},
                        function(data){
                            if(data.subcategorias){
                                var $cmb = $("#cmb-subcategoria-padre");
                                var tpl = '<option value="0">Ninguna</option>';
                                $.each(data.subcategorias, function(i, e){
                                    tpl += '<option value="'+ e.subcategoria_id +'">' + e.subcategoria_nombre + '</option>';
                                });
                                $cmb.html(tpl);
                            }
                        },
                        'json'
                    );
                });

                $("#tabla-subcategoria tbody").on("click", "tr", function( e ) {
                    if ( $(this).hasClass('info') ) {
                        $(this).removeClass('info');
                    }else {
                        $dt_tabla_subcategoria.$('tr.info').removeClass('info');
                        $(this).addClass('info');
                    }
                });

                function fnClickAgregarFilaDT(obj) {
                    var addedRow = $dt_tabla_subcategoria.fnAddData(obj);
                    var nTr = $dt_tabla_subcategoria.fnSettings().aoData[addedRow[0]].nTr;
                    return $(nTr);
                }

                var $modal_nuevo_subcategoria = $("#modal-nuevo-subcategoria");
                var $modal_confirmar_eliminar = $("#modal-confirmar-eliminar");

                $("#btn-actualizar").click(function(evt){
                    evt.preventDefault();
                    $dt_tabla_subcategoria.fnStandingRedraw();
                });

                $("#btn-nuevo").click(function(evt){
                    evt.preventDefault();
                    $("#hdn-option").val("registrar_subcategoria");
                    $("#btn-registrar").html("Registrar");
                    $("#modal-nuevo-subcategoria").modal("show");
                    $("#form-registrar-subcategoria").get(0).reset();
                });

                $("#btn-eliminar").click(function(evt){
                    var anSelected = fnGetSelected( $dt_tabla_subcategoria );
                    if ( anSelected.length !== 0 ) {
                        evt.preventDefault();
                        var descripcion = $dt_tabla_subcategoria.fnGetData(anSelected[0], 1);
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
                    var anSelected = fnGetSelected( $dt_tabla_subcategoria );
                    var id = $dt_tabla_subcategoria.fnGetData(anSelected[0], 0);
                    $.post(
                        'ajax_mantenimiento.php',
                        {
                            'option':'eliminar_subcategoria',
                            'subcategoria_id':id
                        },
                        function(data){
                            if(data.success){
                                $dt_tabla_subcategoria.fnDeleteRow( anSelected[0] );
                                $modal_confirmar_eliminar.modal("hide");
                                $('html, body').animate({
                                    scrollTop: $("#tabla-subcategoria").offset().top - 60
                                }, 200);
                            }else{
                                alert("Error al eliminar registro");
                            }
                        },
                        'json'
                    );
                });

                $("#btn-editar").click(function(){
                    var anSelected = fnGetSelected( $dt_tabla_subcategoria );
                    if (anSelected.length !== 0) {
                        var id = $dt_tabla_subcategoria.fnGetData(anSelected[0], 0);
                        $("#hdn-subcategoria-id").val(id);
                        $.post(
                            'ajax_mantenimiento.php',
                            {
                                'option':'editar_subcategoria',
                                'subcategoria_id':id
                            },
                            function(data){
                                if(data.subcategoria){
                                    var x = data.subcategoria;
                                    $("#hdn-subcategoria-id").val(x.subcategoria_id);
                                    $("#txt-subcategoria-nombre").val(x.subcategoria_nombre);
                                    $("#cmb-categoria-id").val(x.categoria_id);
                                    $("#txt-subcategoria-ruta").val(x.subcategoria_ruta);
                                    
                                    var tpl = '<option value="0">Ninguna</option>';
                                    if(x.subcategoria_padre*1 != 0){
                                        tpl += '<option selected="selected" value="'+x.subcategoria_padre+'">'+ x.obj_sucategoriapadre.subcategoria_nombre +'</option>';
                                    }
                                    $("#cmb-subcategoria-padre").html(tpl);
                                        
                                    $("#hdn-option").val("actualizar_subcategoria");

                                    $("#btn-registrar").html("Actualizar");
                                    $("#modal-nuevo-subcategoria").modal("show");
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
                    $form_registrar_subcategoria.trigger("submit");
                });

                /****************************************************/
                /****************************************************/
                var $txt_filter_subcategoria = $("#txt-filter-subcategoria");
                $txt_filter_subcategoria.keyup(function(e){
                    if (e.which <= 90 && e.which >= 48){
                        if ( this.value.length>2 ) {
                            $dt_tabla_subcategoria.fnFilter( this.value);
                        }
                    }
                });

                $("#btn-mostrar-todo").click(function(){
                    $txt_filter_subcategoria.val("");
                    $dt_tabla_subcategoria.fnFilter( this.value);
                });

                var $form_registrar_subcategoria = $("#form-registrar-subcategoria");
                $form_registrar_subcategoria.validate({
                    rules: {
                        'subcategoria_nombre':{
                            required: true
                        },
                        'categoria_id':{
                            required: true
                        },
                        'subcategoria_ruta':{
                            required: true
                        },
                        'subcategoria_padre':{
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
                                if($("#hdn-option").val() == "actualizar_subcategoria"){
                                    if(data.success){
                                        $modal_nuevo_subcategoria.modal("hide");
                                        $dt_tabla_subcategoria.fnStandingRedraw();
                                    }else{
                                        alert("Error al actualizar registro");
                                    }
                                }else{
                                    if(data.subcategoria_id){
                                        $modal_nuevo_subcategoria.modal("hide");
                                        $dt_tabla_subcategoria.fnStandingRedraw();
                                    }else{
                                        alert("Error al registrar subcategoria.");
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