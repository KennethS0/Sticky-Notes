<?php

//require "control_sesion.php"; //importa el control de sesiones el require detecta errores Fatales en la ejecución del archivo importado no así el include!
include "connection.php";
session_start();
/*********Eliminar estando producción************/
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);
/*************************************************/
// if(!isset($_REQUEST["name"]) && !isset($_REQUEST["description"]))
// {
//     echo ("[false,{'Error':'Debe enviar los parámetros name y description'}]");
//     exit();
// }


$email=$_SESSION["email"];
$option = $_REQUEST["option"];

$conn = get_conection();
//option 1,2,3
//1 new workflow
//2 edit workflow
//3 delete workflow

if ($option == 1)
{
    
    $name=$_REQUEST["name"];
    $description=$_REQUEST["description"];
   
    if(existWorkflow($conn,$email,$name)){
        echo ("[false,{'Error': 'Ya existe un Workflow con ese nombre.'}]"); 
        exit();
    }
    else{
        createWorkflow($conn,$email,$name,$description);
    }
   

    
}
if ($option == 2)
{
    //Update name action = 1
    //Update description action = 2
    //Add state action = 3
    //Delete state action = 4
    //Load states = 5
    //Update workflow = 6

      
    $action = $_REQUEST["action"];

    if($action == 1){

        $name=$_REQUEST["name"];  
        $newName=$_REQUEST["newName"];
        updateWorkflowName($conn, $email, $name, $newName);

    }elseif($action == 2){
        $name=$_REQUEST["name"];  

        $newDescription=$_REQUEST["newDescription"];
        updateWorkflowDescription($conn, $email, $name, $newDescription);

    }elseif($action == 3){
        $name=$_REQUEST["name"];  
        $state=$_REQUEST["state"];
        if(existState($conn, $email, $name,$state)){
            echo ("[false,{'Error':'El estado ya existe en el workflow'}]");
            exit();
        }else{
            addState($conn, $email, $name,$state);
        }

    }elseif($action == 4){
        $name=$_REQUEST["name"];  

        $state=$_REQUEST["state"];
        if(!existState($conn, $email, $name,$state)){
            echo ("[false,{'Error':'El estado que desea eliminar no existe en el workflow'}]");
            exit();
        }else{
            deleteState($conn, $email, $name,$state);
        }
    }elseif($action == 5){
        $name=$_REQUEST["name"];  
        $index = $_REQUEST["wfIndex"];

        $doc = getWorkflows();
        $statesList = getStates($conn, $email, $name);
        
        $jsonResponse = '{"name": "'.$doc->workflows[$index]->name.'",';
        $jsonResponse .= '"description": "'.$doc->workflows[$index]->description.'",';

        $jsonResponse .= '"states": [';

        for($i=0; $i<$statesList->count();$i++)
        {
            // Adds every state to the response
            $jsonResponse .= '{"name":"'.$statesList[$i]->name.'", "stickies":[';
            
            // Adds every sticky note as an array
            if (isset($statesList[$i]->stickies)) {
                for($j = 0; $j < $statesList[$i]->stickies->count(); $j++) {
                    $jsonResponse .= '{"color":"'.$statesList[$i]->stickies[$j]->color.'",';
                    $jsonResponse .= '"text":"'.$statesList[$i]->stickies[$j]->text.'",';
                    $jsonResponse .= '"height":"'.$statesList[$i]->stickies[$j]->height.'",';
                    $jsonResponse .= '"width":"'.$statesList[$i]->stickies[$j]->width.'"}';
                    if ($j != $statesList[$i]->stickies->count()-1) { $jsonResponse .= ","; }
                }
            }
            
            $jsonResponse .= ']}';

            if($i!=$statesList->count()-1)
            {
                $jsonResponse .= ",";
            }

        }
        $jsonResponse .= "]}";
        // print_r($workflows);

        echo ($jsonResponse);
        exit();
    }elseif($action == 6){

        $states=$_REQUEST["states"];
        $wfIndex=$_REQUEST["wfIndex"];
        updateWFStates($conn,$email,$states,$wfIndex);
        exit();
    }

}
if ($option== 3)
{
    deleteWorkflow($conn,$name,$description);
    
}




?>