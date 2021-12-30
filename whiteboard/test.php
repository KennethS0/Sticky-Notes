<?php
require 'vendor/autoload.php'

?>
<?php

    $conn =new MongoDB\Client("mongodb+srv://whiteboard:1234@whiteboard.lqc06.mongodb.net/myFirstDatabase?retryWrites=true&w=majority");
    $email = 'lisethGonz6';    
    $name = 'Cambio';
    $newDescription = 'La descripción ha cambiado';
    $collection = $conn->whiteboard->users;
    $result = $collection->find(['email' => $email]);
   
   
    foreach($result as $document){
        
        $workflows = $document->workflows;
        foreach($workflows as $workflow){
            if($workflow->name == $name){
                $workflow->description = $newDescription;
                $filter = array('email'=>$email);
                $update = array('$set'=>array('workflows'=>$workflows));   
                $collection->updateOne($filter,$update);
                echo 'Cambio';
                exit();
            }

        }
  
    }
