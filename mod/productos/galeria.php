 <?php
    $stylesheets = array(
        'datatables.css',
        'lightbox.css'
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
            .thumbs div{
                margin-bottom: 20px;            
            }
            .thumbs div figure{
                position: relative;
                height: 140px;
                overflow: hidden;
                border: 4px solid #fff;
                box-shadow: 0 2px 3px rgba(0, 0, 0, .35);
            }
            .thumbs div figure figcaption{
                position: absolute;
                bottom: -50px;
                display: block;
                background: rgba(0, 0, 0, .5);
                width: 100%;
                padding: 4px 10px;

                -webkit-transition: all .3s ease;
                -moz-transition: all .3s ease;
                -ms-transition: all .3s ease;
                -o-transition: all .3s ease;
                transition: all .3s ease;
            }
            .thumbs div figure:hover figcaption{
                bottom: 0;
            }

        </style>

        <div class="container">
            <?php include_once 'inc/menu.php'; ?>
        <div class="panel panel-primary">
            <div class="panel-heading">Mantenimiento de Tabla: producto</div>
            <div class="panel-body">
                
                <div class="well well-sm row clearfix">                    
                    <div class="col-md-12">
                        <div class="pull-right">
                            <button type="button" class="btn btn-sm btn-primary" id="btn-nuevo"><i class="glyphicon glyphicon-file"></i> Nuevo </button>                            
                            <button type="button" class="btn btn-sm btn-primary" id="btn-actualizar"><i class="glyphicon glyphicon-refresh"></i> Actualizar </button>
                        </div>
                    </div>
                </div>

                
                <div class="col-md-3">
                    <aside>
                        <h3>LÍNEAS</h3>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="panel-group" id="accordion">
                                    <?php $colapse = true;?>
                                    <?php foreach ($categorias["categorias"] as $cat) : ?>                                    
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $cat->categoria_id; ?>">
                                                        <span class="glyphicon glyphicon-folder-close"></span> <?php echo $cat->categoria_nombre; ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapse_<?php echo $cat->categoria_id; ?>" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <table class="table" id="table_<?php echo $cat->categoria_id;?>">                                                        
                                                        <?php $subcategorias = Subcategoria::getSubCategoriaPadre($cat->categoria_id);?>
                                                        <?php foreach ($subcategorias["subcategorias"] as $subcat) : ?>
                                                            <tr>
                                                                <td id="td_<?php echo $subcat->subcategoria_id; ?>">
                                                                    <!--span class="glyphicon glyphicon-ok text-primary"></span-->
                                                                    <a 
                                                                    data-categoria="<?php echo strtolower($cat->categoria_nombre); ?>" 
                                                                    <?php if ($subcat->tiene_hijos): ?>
                                                                    class="link_subcat" 
                                                                    data-toggle="collapse" 
                                                                    data-parent="#table_<?php echo $cat->categoria_id;?>"
                                                                    href="#collapse_inner_<?php echo $subcat->subcategoria_id; ?>"                                                                
                                                                    <?php else: ?>
                                                                    class="link_subcat link_productos" 
                                                                    href="#<?php echo $subcat->subcategoria_id; ?>" 
                                                                    <?php endif; ?>>
                                                                        <?php echo $subcat->subcategoria_nombre; ?>
                                                                    </a>
                                                                    <div id="collapse_inner_<?php echo $subcat->subcategoria_id; ?>" class="panel-collapse collapse" style="height: auto;">
                                                                        <div class="panel-body">
                                                                            <table class="table">
                                                                                <tbody>
                                                                                    <?php $subcats = Subcategoria::getSubCategoriaHijas($cat->categoria_id, $subcat->subcategoria_id);?>
                                                                                    <?php foreach ($subcats["subcategorias"] as $sc) :?>                                                                                    
                                                                                    <tr>
                                                                                        <td>
                                                                                            <a data-categoria="<?php echo $subcat->subcategoria_nombre;?>" class="link_subcat link_productos hasparent" href="#<?php echo $sc->subcategoria_id?>"> <?php echo $sc->subcategoria_nombre; ?></a>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <?php endforeach; ?>                                                                                                            
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>                                                        
                                                        <?php endforeach; ?> 
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <br/>
                    </aside>
                </div>
                <div class="col-md-9">
                    <div class="row thumbs"></div>
                    
                </div>
            </div>
        </div>
            <?php include_once 'inc/footer.php';?>
        </div>

     
        <div class="modal fade" id="modal-nuevo-producto" tabindex="-1" role="dialog" aria-labelledby="modal-nuevo-producto-label" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                         <h4 class="modal-title" id="modal-nuevo-producto-label">Registrar nuevo producto</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                        <form class="form-horizontal" role="form" id="form-registrar-producto" name="form-registrar-producto" novalidate="novalidate" method="post" action="#">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="txt-producto-nombre">Nombre:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="txt-producto-nombre" name="producto_nombre" placeholder="nombre">
                                            </div>
                                        </div>                                        
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="txt-subcategoria-id">Subcategoria:</label>
                                            <div class="col-sm-8">
                                                <input type="hidden" name="subcategoria_id" id="hdn-subcategoria-id">
                                                <label id="lbl-subcategoria"></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="txt-imagen-id">Imagen:</label>
                                            <div class="col-sm-8">
                                                <input type="file" class="form-control" id="file-imagen-id" name="imagen">
                                            </div>
                                        </div>
                                      <input type="hidden" id="hdn-producto-id" name="producto_id"/>
                                      <input type="hidden" id="hdn-option" name="option" value=""/>
                                      </form>
                          </div>
                      </div>
                  </div><!-- /modal-body -->
                  <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                      <button id="btn-registrar" title="Registrar producto" type="button" class="btn btn-primary">Registrar</button>
                  </div>
              </div>
          </div>
        </div>
        
       

        <div class="modal fade" id="modal-editar-nombre-producto" tabindex="-1" role="dialog" aria-labelledby="modal-editar-nombre-producto-label" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                         <h4 class="modal-title" id="modal-editar-nombre-producto-label">Editar producto</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="form-horizontal" role="form" id="form-actualizar-producto" name="form-registrar-producto" novalidate="novalidate" method="post" action="#">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="txt-editar-producto-nombre">Nombre:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="txt-editar-producto-nombre" name="producto_nombre" placeholder="nombre">
                                        </div>
                                    </div>                                        
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="txt-subcategoria-id">Subcategoria:</label>
                                        <div class="col-sm-8">                                                
                                            <label id="lbl-editar-subcategoria"></label>
                                        </div>
                                    </div>
                                        
                                    <input type="hidden" id="hdn-editar-producto-id" name="producto_id"/>
                                    <input type="hidden" id="hdn-editar-option" name="option" value="actualizarNombreProducto"/>
                                </form>
                            </div>
                      </div>
                  </div><!-- /modal-body -->
                  <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                      <button id="btn-actualizar-producto" title="Actualizar nombre del producto" type="button" class="btn btn-primary">Actualizar</button>
                  </div>
              </div>
          </div>
        </div>
        
        <div class="modal fade" id="modal-editar-producto" tabindex="-1" role="dialog" aria-labelledby="modal-editar-producto-label" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                         <h4 class="modal-title" id="modal-editar-producto-label">Editar nombre de producto</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="form-horizontal" role="form" id="form-registrar-producto" name="form-registrar-producto" novalidate="novalidate" method="post" action="#">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="txt-producto-nombre">Nombre:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="txt-producto-nombre" name="producto_nombre" placeholder="nombre">
                                        </div>
                                    </div>                                                                                
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="txt-imagen-id">Imagen:</label>
                                        <div class="col-sm-8">
                                            <input type="file" class="form-control" id="file-imagen-id" name="imagen">
                                        </div>
                                    </div>
                                  <input type="hidden" id="hdn-producto-id" name="producto_id"/>
                                  <input type="hidden" id="hdn-option" name="option" value="actualizarProducto"/>
                              </form>
                          </div>
                      </div>
                  </div><!-- /modal-body -->
                  <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                      <button id="btn-registrar" title="Actualizar" type="button" class="btn btn-primary">Actualizar</button>
                  </div>
              </div>
          </div>
        </div>

        <?php include_once 'inc/scripts.php';?>
        <link rel="stylesheet" href="css/redmond/jquery-ui-1.10.4.custom.min.css"/>
        <script type="text/javascript" src="js/jquery-ui-1.10.4.custom.min.js"></script>
        <script src="../js/lightbox-2.6.min.js"></script>
        <script type="text/javascript">
            $(function() {
                var accentsTidy = function(s){
                    var r = s.toLowerCase();
                    non_asciis = {'a': '[àáâãäå]', 'ae': 'æ', 'c': 'ç', 'e': '[èéêë]', 'i': '[ìíîï]', 'n': 'ñ', 'o': '[òóôõö]', 'oe': 'œ', 'u': '[ùúûűü]', 'y': '[ýÿ]', '_': '[ ]'};
                    for (i in non_asciis) { r = r.replace(new RegExp(non_asciis[i], 'g'), i); }
                    return r;
                };
                
                var subcategoria_actual = {nombre: '', id: 0};
                
                var $current_link = false;
                $(".link_productos").click(function(evt) {
                    var $a = $(this);
                    $current_link = $a;
                    var idsubcat = $a.attr("href").split("#").pop();
                    var categoria_ruta = accentsTidy($a.data("categoria").toLowerCase());
                    
                    subcategoria_actual.id = idsubcat;
                    subcategoria_actual.nombre = $.trim($a.text());

                    $("a.link_productos").removeClass("active");
                    $a.addClass("active");
                    var titulo = $a.text();

                    if($a.hasClass('hasparent')){
                        var $aa = $a.closest('.panel-collapse').siblings('a');
                        categoria_ruta = accentsTidy($aa.data("categoria").toLowerCase()) + '/' + categoria_ruta;
                    }

                    var $thumbs = $(".thumbs");

                    $('html, body').animate({
                        scrollTop: $(".container").offset().top - 20
                     }, 200);

                    $thumbs.html('<div class="preloading"></div>');



                    $.post(
                        'ajax_productos.php',
                        {
                            'option': 'getProductos',
                            'subcategoria_id': idsubcat
                        },
                        function(data) {
                            if (data.success) {
                                var tpl = '<h3 class="titulo-galeria">'+ titulo +'</h3>';

                                var subcategoria = data.subcategoria;

                                $thumbs.empty();
                                $.each(data.productos, function(i, e) {
                                    var producto_id = e.producto_id;
                                    var producto_nombre = e.producto_nombre;
                                    var producto_imagen = e.obj_imagen.imagen_ruta;
                                    var ruta_imagen = '../img/servitecs/' + categoria_ruta + '/' + subcategoria.subcategoria_ruta + '/800x600/' + producto_imagen;
                                    var ruta_imagen_th = '../img/servitecs/' + categoria_ruta + '/' + subcategoria.subcategoria_ruta + '/th/' + producto_imagen;

                                    tpl += '<div class="col-md-3 col-sm-4 col-xs-4">';
                                    tpl += '  <figure>';
                                    tpl += '    <a href="' + ruta_imagen + '" data-lightbox="captions" title="' + producto_nombre + '">';
                                    tpl += '     <img class="img-responsive" src="' + ruta_imagen_th + '" alt=' + producto_nombre + '/>';
                                    tpl += '    </a>';
                                    tpl += '    <figcaption>';
                                    tpl += '      <a class="btn btn-sm btn-primary producto-edit" title="Cambiar nombre" data-idproducto="'+ producto_id +'">';
                                    tpl += '          <i class="glyphicon glyphicon-pencil"></i>';
                                    tpl += '      </a>';
                                    tpl += '      <a class="btn btn-sm btn-danger producto-del" title="Eliminar" data-idproducto="'+ producto_id +'">';
                                    tpl += '          <i class="glyphicon glyphicon-trash"></i>';
                                    tpl += '      </a>';
                                    tpl += '    </figcaption>';
                                    tpl += '  </figure>';
                                    tpl += '</div>';
                                });
                                $thumbs.html(tpl);
                            }
                        },
                        'json'
                    );
                });
                

                var $modal_editar_nombre_producto = $("#modal-editar-nombre-producto");
                $(".thumbs").on("click", "a.producto-edit", function(){
                    var idproducto = $(this).data("idproducto");
                    $.post(
                        'ajax_productos.php',
                        {
                            'option': 'editarProducto',
                            'producto_id': idproducto
                        },
                        function(data){
                            if(data.producto){                                
                                $("#lbl-editar-subcategoria").html(subcategoria_actual.nombre);
                                $("#txt-editar-producto-nombre").val(data.producto.producto_nombre);
                                $("#hdn-editar-producto-id").val(idproducto)
                                $modal_editar_nombre_producto.modal("show");
                            }
                        },
                        'json'
                    );
                });

                $("#btn-actualizar-producto").click(function(evt){
                    $.post(
                        'ajax_productos.php',
                        $("#form-actualizar-producto").serialize(),
                        function(data){
                            if(data.success){
                                if($current_link){
                                    $current_link.trigger("click");
                                }
                                $modal_editar_nombre_producto.modal("hide");
                            }
                        },
                        'json'
                    );
                });
                    
                
                $(".thumbs").on("click", "a.producto-del", function(){
                    var $a = $(this);
                    var idproducto = $a.data("idproducto");
                    if(confirm("Confirma que desea eliminar este producto?")){
                        $.post(
                            'ajax_productos.php',
                            {
                                'option':'eliminarProducto',
                                'producto_id' : idproducto
                            },
                            function(data){
                                if(data.success){
                                    //Eliminar este producto.
                                    $a.closest("div").remove();
                                }
                            },
                            'json'
                        );
                    }
                });

                var $modal_nuevo_producto = $("#modal-nuevo-producto");
                $("#btn-nuevo").click(function(){
                    if(subcategoria_actual.id == 0){
                        alert("Debe seleccionar una subcategoría!");
                        return;
                    }
                    if(confirm("Se creara un producto dentro de la subcategoria: "+ subcategoria_actual.nombre +", desea continuar?")){
                        $("#form-registrar-producto").get(0).reset();
                        $("#hdn-option").val('registrarProducto');
                        $("#hdn-subcategoria-id").val(subcategoria_actual.id);
                        $("#lbl-subcategoria").html(subcategoria_actual.nombre);
                        $modal_nuevo_producto.modal("show");
                    }
                });


                
                $("#btn-registrar").click(function(){
                    var formData = new FormData();
                    
                    var fileInput = document.getElementById('file-imagen-id');
                    var file = fileInput.files[0];
                    formData.append(fileInput.name, file);
                    
                    var frm = document.getElementById("form-registrar-producto");
                    for (i=0;i<frm.elements.length;i++){
                        formData.append(frm.elements[i].name, frm.elements[i].value);                    
                    }
                    $btn = $(this);
                    $btn.html("Guardando ...").attr("disabled", "disabled");
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'ajax_productos.php', true);
                    xhr.addEventListener('readystatechange', function(e) {
                        if( this.readyState === 4 ) {
                            $modal_nuevo_producto.modal("hide");                        
                            $btn.html("Guardar").removeAttr("disabled");                        
                        
                            if($current_link){
                                $current_link.trigger("click");
                            }
                        }
                    });                

                    xhr.send(formData);
                });
            });
        </script>
    </body>
</html>