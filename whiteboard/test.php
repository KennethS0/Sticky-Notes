<?php
require 'vendor/autoload.php'

?>
<?php


$conn =new MongoDB\Client("mongodb+srv://whiteboard:1234@whiteboard.lqc06.mongodb.net/myFirstDatabase?retryWrites=true&w=majority");
$user = 'lisethGonz6';
$wfIndex = 0;
$states = [['name' => 'Sin iniciar','stickies' =>[]],['name' => 'Iniciado','stickies' =>[]],['name' => 'Finalizado','stickies' =>[]],['name' => 'En proceso','stickies' =>[]]];
$collection = $conn->whiteboard->users;
$result = $collection->findOne(['email' => $user]);

$workflows = $result->workflows;
$workflows[$wfIndex]->states = $states;
$filter = array('email'=>$user);
$update = array('$set'=>array('workflows'=>$workflows));   
$collection->updateOne($filter,$update);