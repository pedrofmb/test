<?php

session_start();

require './config.php';

$db = DB::getInstance();
$pdo = $db->dbh;
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");


$option = isset($_POST["option"]) ? $_POST["option"] : $_GET["option"];

switch ($option) {
    case "getProductos":
        getPRoductos();
        break;
    case "registrarProducto":
        registrarProducto();
        break;
    case "editarProducto":
        editarProducto();
        break;
    case "actualizarProducto":
        registrarProducto();
        break;
    case "eliminarProducto":
        eliminarProducto();
        break;
    case "actualizarNombreProducto":
        actualizarNombreProducto();
        break;
}

function getProductos() {

    $idsubcategoria = $_POST["subcategoria_id"];
    $subcategoria = Subcategoria::getById($idsubcategoria);
    $wp = array(
        array('field' => 'subcategoria_id', 'operator' => '=', 'value' => $idsubcategoria)
    );

    $productos = Producto::getByFields($wp);

    foreach ($productos["productos"] as $p) {
        $p->obj_imagen = Imagen::getById($p->imagen_id);
    }

    echo json_encode(array('success' => true, "productos" => $productos["productos"], "subcategoria" => $subcategoria));
}

function registrarProducto(){
    include_once './extras/class.upload.php';
    
    $idsubcategoria = $_POST["subcategoria_id"];
    $nombreproducto = $_POST["producto_nombre"];
    
    $obj_subcategoria = Subcategoria::getById($idsubcategoria);
    $obj_categoria = Categoria::getById($obj_subcategoria->categoria_id);
    
    $ruta_subcategoriapadre = "";
    if($obj_subcategoria->subcategoria_padre*1 != 0){
        $obj_subcategoriapadre = Subcategoria::getById($obj_subcategoria->subcategoria_padre);
        $ruta_subcategoriapadre = $obj_subcategoriapadre->subcategoria_ruta;
    }    
    
    $ruta_subcategoria = $obj_subcategoria->subcategoria_ruta;
    $ruta_categoria = $obj_categoria->categoria_ruta;
    
    if($ruta_subcategoriapadre == ""){
        $ruta_upload = '../img/servitecs/' . $ruta_categoria . '/'. $ruta_subcategoria;        
    }else{
        $ruta_upload = '../img/servitecs/' . $ruta_categoria . '/'. $ruta_subcategoriapadre . '/' . $ruta_subcategoria;        
    }
        
    $ruta_bigimage = $ruta_upload . '/800x600/';
    $ruta_thumb = $ruta_upload . '/th/';
    
    echo $ruta_bigimage, "____";
    echo $ruta_thumb, "____";
    
    $error_documento = "";
    if($_FILES["imagen"]){
        $docu = new Upload($_FILES['imagen']);
        if ($docu->uploaded) {
            $docu->Process($ruta_bigimage);
            if (!$docu->processed) {
                $error_documento = $docu->error;                
            }else{
                $ruta_documento = $docu->file_dst_name;
                procesar_thumb($docu, $ruta_thumb);
            }
        } else {
            $error_documento = "Error al cargar archivo al servidor";            
        }
    }
    
    $imagen = new Imagen;
    $imagen->imagen_descripcion = $nombreproducto;
    $imagen->imagen_ruta = $ruta_documento;
    $idimagen = $imagen->insert();
    

    $prod = new Producto;
    $prod->producto_nombre = $nombreproducto;
    $prod->producto_fecharegistro = date('Y-m-d H:i:s');
    $prod->subcategoria_id = $idsubcategoria;
    $prod->imagen_id = $idimagen;
    $idproducto = $prod->insert();
    
    echo json_encode(array("success" => ($idproducto > 0), "error_documento"=>$error_documento));
}

function procesar_thumb(&$myhandle, $targetPath){
    $myhandle->image_resize          = true;
    $myhandle->image_ratio_crop      = true;
    //$myhandle->image_ratio_y = true;
    if($myhandle->image_src_x > $myhandle->image_src_y){
        $myhandle->image_x               = 240;
        $myhandle->image_y               = 180;
    }else{
        $myhandle->image_x               = 180;            
        $myhandle->image_y               = 240;
    }

    $myhandle->file_new_name_body = $myhandle->file_dst_name_body;
    $myhandle->Process($targetPath);        
}

function eliminarProducto(){
    $idproducto = $_POST["producto_id"];
    $producto = new Producto;
    $producto->producto_id = $idproducto;

    /*
    $idimagen = $producto->imagen_id;
    $imagen = Imagen::getById($idimagen);;
    $imagen_ruta = $imagen->imagen_ruta;
    $imagen->delete();

    $filename = dirname(__FILE__) . "../img/servitecs/" .$imagen_ruta;
    if(file_exists($filename)){
        unlink($filename);
    }*/

    $result = $producto->delete();
    
    echo json_encode(array("success" => $result));
}

function editarProducto(){
    $idproducto = $_POST["producto_id"];
    $producto = Producto::getById($idproducto);
    
    echo json_encode(array("producto" => $producto));   
}

function actualizarNombreProducto(){
    $idproducto = $_POST["producto_id"];
    $nombreproducto = $_POST["producto_nombre"];

    $producto = Producto::getById($idproducto);
    $producto->producto_nombre = $nombreproducto;
    
    echo json_encode(array("success" => $producto->update()));      
}