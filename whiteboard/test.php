<?php
require 'vendor/autoload.php'

?>
<?php


$conn =new MongoDB\Client("mongodb+srv://whiteboard:1234@whiteboard.lqc06.mongodb.net/myFirstDatabase?retryWrites=true&w=majority");
$user = 'lisethGonz6';
$name = 'Prueba';
$newState = ['name' => 'Posicion 5.0','stickies' =>[]];
$collection = $conn->whiteboard->users;
$result = $collection->findOne(['email' => $user]);
$position = 5;
$workflows = $result->workflows;

    foreach($workflows as $workflow){
        
        if($workflow->name == $name){
            
            $states = $workflow->states; 
            if($position<$states->count()){
                
                $actualState =$newState;

                for($i=$position;$i<$states->count();$i++){

                    $lastState = $states[$i];
                    $states[$i] = $actualState;
                    $actualState = $lastState;

                }

                $states->append($actualState);

            }else{

                $states->append($newState);
            
            }
        }
    }
    $filter = array('email'=>$user);
    $update = array('$set'=>array('workflows'=>$workflows));   
    $collection->updateOne($filter,$update);