<?php
require 'vendor/autoload.php'

?>
<?php
  $conn =new MongoDB\Client("mongodb+srv://whiteboard:1234@whiteboard.lqc06.mongodb.net/myFirstDatabase?retryWrites=true&w=majority");

    $collection = $conn->whiteboard->users;
    $email = 'lisethGonz6';
    $result = $collection->find(['email' => $email]);

    foreach($result as $document){
      
        $workflows = $document->workflows;


        foreach($workflows as $workflow){
            
            if($workflow->name == 'hola'){
            
                $array = $workflow->stickies;              
                var_dump($array[count($array)-1]->position + 1);

                echo "<br>";
            
              
            }
        }
            
           
    }