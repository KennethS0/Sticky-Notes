<?php

//require "control_sesion.php"; //importa el control de sesiones el require detecta errores Fatales en la ejecución del archivo importado no así el include!
include "connection.php";

/*********Eliminar estando producción************/
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);
/*************************************************/
if(!isset($_REQUEST["name"]) && !isset($_REQUEST["description"]))
{
    echo ("[false,{'Error':'Debe enviar los parámetros name y description'}]");
    exit();
}
$email=$_REQUEST["email"];
$name=$_REQUEST["name"];
$description=$_REQUEST["description"];
$conn = get_conection();
//option 1,2,3
//1 new workflow
//2 edit workflow
//3 delete workflow
if (isset($_REQUEST["option"]) == 1)
{

    if(existWorkflow($conn,$email,$name)){
        echo ("[false,{'Error': 'Ya existe un Workflow con ese nombre.'}]"); 
        exit();
    }
    else{
        createWorkflow($conn,$email,$name,$description);
    }
   

    
}
if (isset($_REQUEST["option"]) == 2)
{
    updateWorkflow($conn,$name,$description);
    
}

if (isset($_REQUEST["option"]) == 3)
{
    deleteWorkflow($conn,$name,$description);
    
}




?>