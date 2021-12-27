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
    $result = $collection->insertOne( [ 'email' => $email, 'password' => $password ] );
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
