<?php

session_start();

require './config.php';

$db = DB::getInstance();
$pdo = $db->dbh;
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");


$option = isset($_POST["option"]) ? $_POST["option"] : $_GET["option"];

switch($option){
    case "registrar_categoria":
        registrar_categoria();
        break;
    case "editar_categoria":
        editar_categoria();
        break;
    case "eliminar_categoria":
        eliminar_categoria();
        break;
    case "actualizar_categoria":
        actualizar_categoria();
        break;
    case "listar_categorias":
        listar_categorias();
        break;
    
    
    
    case "registrar_subcategoria":
        registrar_subcategoria();
        break;
    case "editar_subcategoria":
        editar_subcategoria();
        break;
    case "eliminar_subcategoria":
        eliminar_subcategoria();
        break;
    case "actualizar_subcategoria":
        actualizar_subcategoria();
        break;
    case "listar_subcategorias":
        listar_subcategorias();
        break;    
    case "listar_subcategoria_padre":
        listar_subcategoria_padre();
        break;
    
}

/*************************************************************************************************************/
/*      C   A   T   E   G   O   R   I   A   S   
/*************************************************************************************************************/
function registrar_categoria(){
    $categoria = new Categoria;
    $categoria->storeFormValues($_POST);
    $idcategoria = $categoria->insert();
    echo json_encode(array("categoria_id"=>$idcategoria));
}
function eliminar_categoria(){
    $categoria = new Categoria;
    $categoria->storeFormValues($_POST);
    echo json_encode(array("success"=>$categoria->delete()));
}
function editar_categoria(){
    $idcategoria = $_POST["categoria_id"];
    $categoria = Categoria::getById($idcategoria);
    echo json_encode(array("categoria"=>$categoria));
}
function actualizar_categoria(){
    $categoria = Categoria::getById($_POST["categoria_id"]);
    $categoria->storeFormValues($_POST);
    echo json_encode(array("success"=>$categoria->update()));
}
function listar_categorias(){
    $start = $_GET['iDisplayStart']*1;
    $limit = $_GET['iDisplayLength']*1;
    $aColumns = array("categoria_id", "categoria_nombre", "categoria_orden");
    $whereParams = array();
    $orderParams = array();
    if ( isset( $_GET['iSortCol_0'] ) ){
        for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ ){
            if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" ){
                $orderParams[] = array(
                    "field"=>$aColumns[ intval( $_GET['iSortCol_'.$i] ) ],
                    "order"=> $_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc'
                );
            }
        }
    }

    if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" ){
        for ( $i=0 ; $i<count($aColumns) ; $i++ ){
            if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" ){
                $whereParams[] = array(
                    "field"=>$aColumns[$i],
                    "operator"=>"LIKE",
                    "value"=>$_GET['sSearch'],
                    "conjunction"=>"OR"
                );
            }
        }
    }

    $categorias = Categoria::getByFields($whereParams, $orderParams, $start, $limit);

    echo json_encode(array(
        "sEcho" => intval($_GET['sEcho']),
        "iTotalRecords" => $categorias["totalCount"]*1,
        "iTotalDisplayRecords" => $categorias["totalCount"]*1,
        "aaData" => $categorias["categorias"]
    ));
}



/*************************************************************************************************************/
/*              S   U   B   C   A   T   E   G   O   R   I   A   S   
/*************************************************************************************************************/
function registrar_subcategoria(){
    $subcategoria = new Subcategoria;
    $subcategoria->storeFormValues($_POST);
    $idsubcategoria = $subcategoria->insert();
    
    $categoria = Categoria::getById($subcategoria->categoria_id);
    
    $basedir = '../img/servitecs/' . $categoria->categoria_ruta . '/';
    $ruta_subcategoria = $subcategoria->subcategoria_ruta;
    $ruta = $basedir . $ruta_subcategoria;
    
    if (!file_exists($ruta)) {
        mkdir($ruta, 0777, true);
    }
    
    echo json_encode(array("subcategoria_id"=>$idsubcategoria));
}
function eliminar_subcategoria(){
    $subcategoria = new Subcategoria;
    $subcategoria->storeFormValues($_POST);
    echo json_encode(array("success"=>$subcategoria->delete()));
}
function editar_subcategoria(){
    $idsubcategoria = $_POST["subcategoria_id"];
    $subcategoria = Subcategoria::getById($idsubcategoria);
    $subcategoria->obj_sucategoriapadre = Subcategoria::getById($subcategoria->subcategoria_padre);
    echo json_encode(array("subcategoria"=>$subcategoria));
}
function actualizar_subcategoria(){
    $subcategoria = Subcategoria::getById($_POST["subcategoria_id"]);
    $subcategoria->storeFormValues($_POST);
    echo json_encode(array("success"=>$subcategoria->update()));
}
function listar_subcategorias(){
    $start = $_GET['iDisplayStart']*1;
    $limit = $_GET['iDisplayLength']*1;
    $aColumns = array("subcategoria_id", "subcategoria_nombre", "categoria_id", "subcategoria_ruta", "subcategoria_padre");
    $whereParams = array();
    $orderParams = array();
    if ( isset( $_GET['iSortCol_0'] ) ){
        for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ ){
            if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" ){
                $orderParams[] = array(
                    "field"=>$aColumns[ intval( $_GET['iSortCol_'.$i] ) ],
                    "order"=> $_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc'
                );
            }
        }
    }

    if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" ){
        for ( $i=0 ; $i<count($aColumns) ; $i++ ){
            if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" ){
                $whereParams[] = array(
                    "field"=>$aColumns[$i],
                    "operator"=>"LIKE",
                    "value"=>$_GET['sSearch'],
                    "conjunction"=>"OR"
                );
            }
        }
    }

    $subcategorias = Subcategoria::getByFields($whereParams, $orderParams, $start, $limit);
    
    foreach ($subcategorias["subcategorias"] as $sc){
        $sc->obj_categoria = Categoria::getById($sc->categoria_id);
        $sc->obj_subcategoriapadre = Subcategoria::getById($sc->subcategoria_padre);
    }
    
    echo json_encode(array(
        "sEcho" => intval($_GET['sEcho']),
        "iTotalRecords" => $subcategorias["totalCount"]*1,
        "iTotalDisplayRecords" => $subcategorias["totalCount"]*1,
        "aaData" => $subcategorias["subcategorias"]
    ));
}

function listar_subcategoria_padre(){
    $idcategoria = $_POST["categoria_id"];
    $subcategorias = Subcategoria::getSubCategoriaPadre($idcategoria);
    
    echo json_encode($subcategorias);
}