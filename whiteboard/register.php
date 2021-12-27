<?php

//require "control_sesion.php"; //importa el control de sesiones el require detecta errores Fatales en la ejecución del archivo importado no así el include!
include "connection.php";

/*********Eliminar estando producción************/
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);
/*************************************************/


if (!isset($_REQUEST["email"]) && !isset($_REQUEST["password"]))
{
    echo ("[false,{'Error':'Debe enviar los parámetros email y password'}]");
    exit();
}

$email=$_REQUEST["email"];
$password=$_REQUEST["password"];

$conn = get_conection();
register($conn,$email,$password);

?>