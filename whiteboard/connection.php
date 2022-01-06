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
function register($conn,$user,$password){
    $collection = $conn->whiteboard->users;
    $collection->insertOne( [ 'email' => $user, 'password' => $password , 'workflows' => []] );
}

// User login
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

//Create new user workflow
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
function deleteWorkflow($conn, $user, $name){
    $collection = $conn->whiteboard->users;
    $result = $collection->find(['email' => $user]);

    foreach($result as $document){
      
        $workflows = $document->workflows;

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
            
           
    
}
//Get the position of the last sticky in the state
function getLastPosition($conn,$user,$name,$state){
    $collection = $conn->whiteboard->users;
    $result = $collection->find(['email' => $user]);
    $position = 1;
    foreach($result as $document){
      
        $workflows = $document->workflows;


        foreach($workflows as $workflow){
            
            if($workflow->name == $name){
            
                $stickies = $workflow->stickies;  

                foreach($stickies as $sticky){
                    if($sticky->state == $state and $sticky->position >= $position){
                        $position = $sticky->position + 1;
                    }
                }           
                
            }
        }
            
           
    }
    return $position;

}

//Create new sticky on the workflow 
function createSticky($conn,$user,$name,$text,$state,$color,$size){
    $collection = $conn->whiteboard->users;
    $result = $collection->find(['email' => $user]);

    foreach($result as $document){
      
        $workflows = $document->workflows;


        foreach($workflows as $workflow){
            
            if($workflow->name == $name){
                $position = getLastPosition($conn,$user,$name,$state);
                $array = $workflow->stickies;              
                $array->append(['text' => $text, 'state' => $state,'color'=>$color,'size'=>$size,'position'=>$position]);
                $workflow->stickies= $array;
                $filter = array('email'=>$user);
                $update = array('$set'=>array('workflows'=>$workflows));   
                $collection->updateOne($filter,$update);
         
            }
        }
            
           
    }
}

//Change the state of a specific sticky
function updateStickyState($conn,$user,$name,$text,$state){
    $collection = $conn->whiteboard->users;
    $result = $collection->find(['email' => $user]);
    foreach($result as $document){
      
        $workflows = $document->workflows;

        foreach($workflows as $workflow){
            
            if($workflow->name == $name){
                $stickies = $workflow->stickies;  
                foreach($stickies as $sticky){
                    if($sticky->text == $text){
                        $sticky->state = $state;
                    }
                }            
              
                $workflow->stickies= $stickies;
                $filter = array('email'=>$user);
                $update = array('$set'=>array('workflows'=>$workflows));   
                $collection->updateOne($filter,$update);
         
            }
        }
            
           
    }
}

//Change the text of a specific sticky
function updateStickyText($conn,$user,$name,$text,$newText){
    $collection = $conn->whiteboard->users;
    $result = $collection->find(['email' => $user]);
    foreach($result as $document){
      
        $workflows = $document->workflows;

        foreach($workflows as $workflow){
            
            if($workflow->name == $name){
                $stickies = $workflow->stickies;  
                foreach($stickies as $sticky){
                    if($sticky->text == $text){
                        $sticky->text = $newText;
                    }
                }            
              
                $workflow->stickies= $stickies;
                $filter = array('email'=>$user);
                $update = array('$set'=>array('workflows'=>$workflows));   
                $collection->updateOne($filter,$update);
         
            }
        }
            
           
    }
}

//Change the color of a specific sticky
function updateStickyColor($conn,$user,$name,$text,$color){
    $collection = $conn->whiteboard->users;
    $result = $collection->find(['email' => $user]);
    foreach($result as $document){
      
        $workflows = $document->workflows;

        foreach($workflows as $workflow){
            
            if($workflow->name == $name){
                $stickies = $workflow->stickies;  
                foreach($stickies as $sticky){
                    if($sticky->text == $text){
                        $sticky->color = $color;
                    }
                }            
              
                $workflow->stickies= $stickies;
                $filter = array('email'=>$user);
                $update = array('$set'=>array('workflows'=>$workflows));   
                $collection->updateOne($filter,$update);
         
            }
        }
            
           
    }
}

//Change the position of a specific sticky
function updateStickyPosition($conn,$user,$name,$text,$position){
    $collection = $conn->whiteboard->users;
    $result = $collection->find(['email' => $user]);
    foreach($result as $document){
      
        $workflows = $document->workflows;

        foreach($workflows as $workflow){
            
            if($workflow->name == $name){
                $stickies = $workflow->stickies;  
                foreach($stickies as $sticky){
                    if($sticky->text == $text){
                        if(positionAvailable($stickies,$sticky->state,$position)){
                            $sticky->position = $position;
                        }
                        else{
                           updateStickiesPosition($stickies,$sticky->state,$position);
                        }
                        $sticky->position = $position;
                    }
                }            
              
                $workflow->stickies= $stickies;
                $filter = array('email'=>$user);
                $update = array('$set'=>array('workflows'=>$workflows));   
                $collection->updateOne($filter,$update);
         
            }
        }
            
           
    }
}

//Check if the new position is available in the state
function positionAvailable($stickies,$state,$position){
    foreach($stickies as $sticky){
        if($sticky->state == $state and $sticky->position == $position){
           return false;
        }
    }            
   return true;
}

//When the new position is not available move all stickies in the state to make available the position
function updateStickiesPosition($stickies,$state,$position){
      
    foreach($stickies as $sticky){
        if($sticky->state == $state and $sticky->position >= $position){
            $sticky->position = $sticky->position + 1;
        }
    }            
     
    
}



//Change the size of a specific sticky
function updateStickySize($conn,$user,$name,$text,$size){
    $collection = $conn->whiteboard->users;
    $result = $collection->find(['email' => $user]);
    foreach($result as $document){
      
        $workflows = $document->workflows;

        foreach($workflows as $workflow){
            
            if($workflow->name == $name){
                $stickies = $workflow->stickies;  
                foreach($stickies as $sticky){
                    if($sticky->text == $text){
                        $sticky->size = $size;
                    }
                }            
              
                $workflow->stickies= $stickies;
                $filter = array('email'=>$user);
                $update = array('$set'=>array('workflows'=>$workflows));   
                $collection->updateOne($filter,$update);
         
            }
        }
            
           
    }
}

//Delete specific sticky
function deleteSticky($conn,$user,$name,$text){
    $collection = $conn->whiteboard->users;
    $result = $collection->find(['email' => $user]);
    foreach($result as $document){
      
        $workflows = $document->workflows;

        foreach($workflows as $workflow){
            
            if($workflow->name == $name){

                $stickies = $workflow->stickies;  
                $count = $stickies->count();
                for ($i = 0; $i < $count; $i++) {

                    if($stickies[$i]->text == $text){
                        $stickies->offsetUnset($i);
                    }
             
              
                $workflow->stickies= $stickies;

                $filter = array('email'=>$user);
                $update = array('$set'=>array('workflows'=>$workflows));   
                $collection->updateOne($filter,$update);
         
                }
            }
            
           
        }
    }
}

//Check if sticky already exist on the workflow
function existSticky($conn,$user,$name,$text){
    $collection = $conn->whiteboard->users;
    $result = $collection->find(['email' => $user]);
    foreach($result as $document){
      
        $workflows = $document->workflows;

        foreach($workflows as $workflow){
            
            if($workflow->name == $name){

                $stickies = $workflow->stickies;  
                $count = $stickies->count();
                for ($i = 0; $i < $count; $i++) {

                    if($stickies[$i]->text == $text){
                       return true;
                       
                    }
                }
            }
            
        }
    }
    return false;
}

//Get stickies by state | return array type
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



