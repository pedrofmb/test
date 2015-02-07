<?php 

session_start();

require 'config.php';

$db = DB::getInstance();
$pdo = $db->dbh;
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");



$option = isset($_GET["option"])? $_GET["option"] : '';
switch ($option) {
    case 'login':
        login();
        break;
    
    case 'inicio':
        inicio();
        
        
    default:
        inicio();
        break;
}

function login(){
//    print_r($_POST);return;
    if (isset($_POST["user"]) && isset($_POST["pass"])) {
    
        $user = $_POST["user"];
        $pass = $_POST["pass"];
        $usuario = Usuario::checkLogin($user, $pass);
        
        //$trabajador = $usuario->getObj_Trabajador();

        if ($usuario != NULL) {

            $_SESSION['login'] = 'ok';
            $_SESSION["user"]["id"]  = $usuario->usuario_id;
            $_SESSION["user"]["email"]  = $usuario->usuario_email;
            $_SESSION["user"]["nombres"]  = $usuario->usuario_nombres;
            $_SESSION["user"]["apellidos"]  = $usuario->usuario_apellidos;
            
            unset($_SESSION["error_message"]);
            
            header("Location: inicio.php");
        } else {
            $_SESSION["error_message"] = 'Usuario o contrase&ntilde;a incorrectos.';
            header("Location: login.php");
        }
    }else{
        if(isset($_SESSION["login"]) && $_SESSION["login"] == "ok"){
            header("Location: index.php");
        }
    }
}

function inicio() {
    if ($_SESSION["login"] != "ok") {
        header("Location: login.php");
        exit;
    }

    $titulo = "Inicio";
    require './mod/inicio/inicio.php';
}

?>