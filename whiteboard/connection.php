<?php
require 'vendor/autoload.php'

?>
<?php
function get_conection(){
    
   
    $conn =new MongoDB\Client("mongodb+srv://whiteboard:1234@whiteboard.lqc06.mongodb.net/myFirstDatabase?retryWrites=true&w=majority");
    if (!$conn) 
    {
        echo "[false,{'error':'conection failed!'}]";
        exit;
    }
    return ($conn);
}

function register($conn,$email,$password){
    $collection = $conn->whiteboard->users;
    $result = $collection->insertOne( [ 'email' => $email, 'password' => $password , 'workflows' => []] );
    echo "Inserted with Object ID '{$result->getInsertedId()}'";
}

function login($conn,$email,$password){
    $collection = $conn->whiteboard->users;
  
    $result = $collection->find();
    foreach ($result as $document) {
        if($document["email"] == $email AND $document["password"] == $password){
            return true;
        }
       
       
    }
    return false;

}

function createWorkflow($conn, $user, $name, $description){

 //Add workflow to user
 $collection = $conn->whiteboard->users;
 $filter = array('email'=>$user);
 $update = array('$push'=>array('workflows'=>[ 'name' => $name, 'creation_date' => date("d/m/Y H:i:s"),'description'=> $description ,'states' => ['Sin iniciar','Iniciado','Finalizado'],'stickies' => []]));   
 $collection->updateOne($filter, $update);

}

function getWorkFlows() {
    $conn = get_conection();
    $collection = $conn->whiteboard->users;
    $res = $collection->find(['email' => 'lisethGonz6'], ['workflows' => 'true']);
    return $res;
}



//Name, description
function updateWorkflow(){

}

function addState(){

}

function deleteState(){


}

function deleteWorkflow(){

}
function getLastPosition($conn,$email,$name){
    $collection = $conn->whiteboard->users;
    $email = $email;
    $result = $collection->find(['email' => $email]);

    foreach($result as $document){
      
        $workflows = $document->workflows;


        foreach($workflows as $workflow){
            
            if($workflow->name == $name){
            
                $array = $workflow->stickies;              
                $position = ($array[count($array)-1]->position + 1);
                return $position;
            }
        }
            
           
    }
    return 1;

}

function createSticky($conn,$email,$name,$text,$state,$color,$size){
    $collection = $conn->whiteboard->users;
    $result = $collection->find(['email' => $email]);

    foreach($result as $document){
      
        $workflows = $document->workflows;


        foreach($workflows as $workflow){
            
            if($workflow->name == $name){
                $position = getLastPosition($conn,$email,$name);
                $array = $workflow->stickies;              
                $array->append(['text' => $text, 'state' => $state,'color'=>$color,'size'=>$size,'position'=>$position]);
                $workflow->stickies= $array;
                $filter = array('email'=>$email);
                $update = array('$set'=>array('workflows'=>$workflows));   
                $collection->updateOne($filter,$update);
         
            }
        }
            
           
    }}

function updateStickyText(){

}

function updateStickyColor(){

}

function updateStickyPosition(){

}

function updateStickySize(){

}


if (isset($_REQUEST["option"]) == 4)
{
    $conn = get_conection();
    $email = $_REQUEST["email"];
    $datos = getWorkFlows($conn, $email);
}