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

function createWorkflow($conn,$user,$name,$description){

 //Add workflow to user
 $collection = $conn->whiteboard->users;
 $filter = array('email'=>$user);
 $update = array('$push'=>array('workflows'=>[ 'name' => $name, 'creation_date' => date("d/m/Y H:i:s"),'description'=> $description ,'states' => ['Sin iniciar','Iniciado','Finalizado'],'stickies' => []]));   
 $collection->updateOne($filter,$update);

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

function createSticky(){

}

function updateStickyText(){

}

function updateStickyColor(){

}

function updateStickyPosition(){

}

function updateStickySize(){

}