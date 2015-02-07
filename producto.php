<?php

session_start();

require 'config.php';

$db = DB::getInstance();
$pdo = $db->dbh;
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");


$option = $_GET["option"];

switch ($option) {
    case 'galeria':
        galeria();
        break;
}

function galeria(){
    $titulo = "Galeria de Productos";
    $_SESSION["modulo"] = "productos";
    
    $categorias = Categoria::listar();
    
    require './mod/productos/galeria.php';
}