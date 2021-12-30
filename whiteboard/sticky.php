<?php

//require "control_sesion.php"; //importa el control de sesiones el require detecta errores Fatales en la ejecución del archivo importado no así el include!
include "connection.php";

/*********Eliminar estando producción************/
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);
/*************************************************/
if(!isset($_REQUEST["text"]))
{
    echo ("[false,{'Error':'Debe enviar el texto'}]");
    exit();
}
$email=$_REQUEST["email"];
$text=$_REQUEST["text"];
$name=$_REQUEST["name"];
$state = $_REQUEST["state"];
$color=$_REQUEST["color"];
$size = $_REQUEST["size"];
$conn = get_conection();

if (isset($_REQUEST["new"]) == true)
{
                
    createSticky($conn,$email,$name,$text,$state,$color,$size);
}


if (isset($_REQUEST["new"]) == false)
{
    createSticky($conn,$email,$name,$text,$state,$color,$size);
    
}

?>