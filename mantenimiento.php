<?php

session_start();

require 'config.php';

$db = DB::getInstance();
$pdo = $db->dbh;
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");


$option = $_GET["option"];

switch ($option) {
    case 'categoria':
        categoria();
        break;
    case 'subcategoria':
        subcategoria();
        break;
    
    default:
        echo 'Error en la URL';
        break;
}

function categoria(){
    $titulo = "Mantenimiento de Tabla Categorias";
    $_SESSION["modulo"] = "mantenimiento";
    
    require './mod/mantenimiento/categorias.php';
}
function subcategoria(){
    $titulo = "Mantenimiento de Tabla Subcategorias";
    $_SESSION["modulo"] = "mantenimiento";
    
    $categorias = Categoria::getList();
    
    require './mod/mantenimiento/subcategorias.php';
}