<?php
require 'vendor/autoload.php'

?>
<?php
//Get database conection
function get_conection(){
    
   
    $conn =new MongoDB\Client("mongodb+srv://whiteboard:1234@whiteboard.lqc06.mongodb.net/myFirstDatabase?retryWrites=true&w=majority");
    if (!$conn) 
    {
        echo "[false,{'error':'conection failed!'}]";
        exit;
    }
    return ($conn);
}

//Check if the user already exist on the database
//conn = database connection
//user = email user
function existUser($conn,$user){
    $collection = $conn->whiteboard->users;
    $result = $collection->find(['email' => $user]);
    foreach ($result as $document) {
        if($document["email"] == $user){
            return true;
        }
    }
    return false;
}

// New user register on the database
//conn = database connection
//user = new user email
//password = user password
function register($conn,$user,$password){
    $collection = $conn->whiteboard->users;
    $collection->insertOne( [ 'email' => $user, 'password' => $password , 'workflows' => []] );
}

// User login
//conn = database connection
//user = user email
//password = user password
function login($conn,$user,$password){
    $collection = $conn->whiteboard->users;
    $result = $collection->find();
    foreach ($result as $document) {
        if($document["email"] == $user AND $document["password"] == $password){
            return true;
        }
       
       
    }
    return false;

}

//Check if workflow already exist on the user
//conn = database connection
//user = user email
//name = workflow name
function existWorkflow($conn, $user, $name){
    
    $collection = $conn->whiteboard->users;
    $result = $collection->find(['email' => $user]);
    foreach($result as $document){
        
        $workflows = $document->workflows;
        foreach($workflows as $workflow){
            if($workflow->name == $name){
                return true;
                exit();
            }

        }
  
    }

    return false;
}

 //Create new user workflow
 //conn = database connection
//user = user email
//name = workflow name
//description= workflow description
function createWorkflow($conn, $user, $name, $description){

 //Add workflow to user
 $collection = $conn->whiteboard->users;
 $filter = array('email'=>$user);
 $update = array('$push'=>array('workflows'=>[ 'name' => $name, 'creation_date' => date("d/m/Y H:i:s"),'description'=> $description ,'states' => [['name' => 'Sin iniciar','stickies' =>[]],['name' => 'Iniciado','stickies' =>[]],['name' => 'Finalizado','stickies' =>[]]]]));   
 $collection->updateOne($filter, $update);

}

    

//Get workflows by user
function getWorkFlows() {
    $conn = get_conection();
    $collection = $conn->whiteboard->users;
    $res = $collection->findOne(['email' => $_SESSION["email"]]);
    return $res;
}

//Update workflow name
//conn = database connection
//user = user email
//name = workflow name
//newName= new workflow name
function updateWorkflowName($conn, $user, $name, $newName){
    $collection = $conn->whiteboard->users;
    $result = $collection->find(['email' => $user]);
    foreach($result as $document){
        
        $workflows = $document->workflows;
        foreach($workflows as $workflow){
            if($workflow->name == $name){
                $workflow->name = $newName;
                $filter = array('email'=>$user);
                $update = array('$set'=>array('workflows'=>$workflows));   
                $collection->updateOne($filter,$update);
            }

        }
  
    }

}

//Update workflow description
//conn = database connection
//user = user email
//name = workflow name
//newDescription= new workflow description
function updateWorkflowDescription($conn, $user, $name, $newDescription){
    $collection = $conn->whiteboard->users;
    $result = $collection->find(['email' => $user]);
    foreach($result as $document){
        
        $workflows = $document->workflows;
        foreach($workflows as $workflow){
            if($workflow->name == $name){
                $workflow->description = $newDescription;
                $filter = array('email'=>$user);
                $update = array('$set'=>array('workflows'=>$workflows));   
                $collection->updateOne($filter,$update);
            }

        }
  
    }

}


//get workflow states
//conn = database connection
//user = user email
//name = workflow name
function getStates($conn, $user, $name){
    $collection = $conn->whiteboard->users;
    $result = $collection->find(['email' => $user]);
    $states = NULL;
    foreach($result as $document){
      
        $workflows = $document->workflows;


        foreach($workflows as $workflow){
            
            if($workflow->name == $name){
                $states = $workflow->states;  
            }
        }
            
           
    }
    return $states;
}


//Check if state already exist on the workflow
//conn = database connection
//user = user email
//name = workflow name
//state= state name to check
function existState($conn, $user, $name,$state){
    $collection = $conn->whiteboard->users;
    $result = $collection->find(['email' => $user]);

    foreach($result as $document){
      
        $workflows = $document->workflows;


        foreach($workflows as $workflow){
            
            if($workflow->name == $name){
            
                $states = $workflow->states;  

                foreach($states as $st){
                    if($st == $state){
                       return true;
                       exit();
                    }
                }           
                
            }
        }
            
           
    }
    return false;
}

//Add new workflow state
//conn = database connection
//user = user email
//name = workflow name
//newState= new state to add
//position= newState position

function addState($conn, $user, $name,$newState,$position){
    date_default_timezone_set('America/Costa_Rica');
    $collection = $conn->whiteboard->users;
    $result = $collection->findOne(['email' => $user]);
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
  
    
}
//Delete workflow state
//conn = database connection
//user = user email
//name = workflow name
//state= state name to delete

function deleteState($conn, $user, $name,$state){
    $collection = $conn->whiteboard->users;
    $result = $collection->find(['email' => $user]);

    foreach($result as $document){
      
        $workflows = $document->workflows;


        foreach($workflows as $workflow){
            
            if($workflow->name == $name){
               
            $states = $workflow->states;  
            echo $states->count();
            $count = $states->count();
            for ($i = 0; $i < $count; $i++) {
                if($states[$i] == $state){
                    $states->offsetUnset($i);
                }
            }
            
                $workflow->states= $states;
                $filter = array('email'=>$user);
                $update = array('$set'=>array('workflows'=>$workflows));   
                $collection->updateOne($filter,$update);
            }
        }
            
           
    }

}

//Delete user workflow
//conn = database connection
//user = user email
//name = workflow name to delete

function deleteWorkflow($conn, $user, $name){
    $collection = $conn->whiteboard->users;
    $result = $collection->findOne(['email' => $user]);

    $workflows = $result->workflows;
    $count = $workflows->count();
    for ($i = 0; $i < $count; $i++) {

        if($workflows[$i]->name == $name){
            $workflows->offsetUnset($i);
        }

        $filter = array('email'=>$user);
        $update = array('$set'=>array('workflows'=>$workflows));
        $collection->updateOne($filter,$update);
    }
}

//Get stickies by state | return array type
//conn = database connection
//user = user email
//name = workflow name
//state = state name
function getStickies($conn,$user,$name,$state){
    $collection = $conn->whiteboard->users;
    $result = $collection->find(['email' => $user]);
    $stickiesArray = array();
    foreach($result as $document){
      
        $workflows = $document->workflows;

        foreach($workflows as $workflow){
            
            if($workflow->name == $name){
                $stickies = $workflow->stickies;  
                foreach($stickies as $sticky){
                    if($sticky->state == $state){
                        array_push($stickiesArray, $sticky);
                    }
                }            
              
                
         
            }
        }
            
           
    }
    return $stickiesArray;
}


//Change the position of a specific sticky
//conn = database connection
//user = user email
//strStates = states to update
//wfIndex= workflow position to set states

function updateWFStates($conn,$user,$strStates,$wfIndex){
    
    $states = json_decode($strStates);
    $collection = $conn->whiteboard->users;
    $result = $collection->findOne(['email' => $user]);

    $workflows = $result->workflows;
    $workflows[$wfIndex]->states = $states;
    $filter = array('email'=>$user);
    $update = array('$set'=>array('workflows'=>$workflows));   
    $collection->updateOne($filter,$update);
}



