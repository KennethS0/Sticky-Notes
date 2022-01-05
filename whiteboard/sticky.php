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
$color=$_REQUEST["color"];
$position = $_REQUEST["position"];
$conn = get_conection();

$option = $_REQUEST["option"];

if ($option == 1)
{   
    if(existSticky($conn,$email,$name,$text)){
        echo ("[false,{'Error':'El sticky ya existe en el workflow'}]");
        exit();
    }
    else{
        createSticky($conn,$email,$name,$text,$state,$color,$size);
    }

}
if($option == 2)
{

   updateStickyPos($conn,$email,$name,$stickies);

}

?>