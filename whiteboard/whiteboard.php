<?php

  require 'connection.php'

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Whiteboard</title>
    <link rel="stylesheet" href="workflow.css" />
    
  </head>
  <body>
      
    <!-- Navbar -->
    <nav class="navbar">
      <ul class="menu">
        <li>Inclusive Whiteboard</li>
        <li>  | Workflows:
          <select onchange="loadWorkflow()" name="workflows" id="workflowsCombo">
            <?php
            session_start();
            if($_SESSION["email"]){
              foreach(getWorkFlows()->workflows as $wf) {
                echo '<option value="'.$wf->name.'">'.$wf->name.'</option>'; 
              }
            }
              
            ?>
          </select>

        </li>
        <li><button  onclick="deleteWF()">Delete</button></li>

        <li>  | New workflow: <input placeholder="Name" type="text" name="new_wf_name" id="wf_name"></li>
        <li><input placeholder="Description" type="text" name="new_wf_description" id="wf_description"></li>
        <li><button onclick="newWorkflow()">Create workflow</button></li>
        

        <li>  | New state: <input placeholder="Name" type="text" name="new_st_name" id="st_name"></li>
        <li> Position: <select name="new_st_position" id="st_position"></select></li>
        <li><button id="addColumnButton">Create state</button></li>
        <li><button onclick="window.location.href='logout.php'">Logout</button></li>
       
      </ul>
    </nav>

    <!-- Main content of the page -->
    <div class="content">

      <div class="current-workflow">
        <h1 id="workflowName" contentEditable>Current Workflow</h1>
        <p id="workflowDescription" contentEditable>Workflow description</p>
      </div>

      <div class="workflow">
        <!-- Workflow -->
        <table style=" table-layout: fixed">
          
          <thead>
            <tr id="head-row">
              
            </tr>
          </thead>
          
          <tbody>
            <tr id="body-row">
              
            </tr>
          </tbody>

          <tfoot>
            <tr id="foot-row">
              
            </tr>
          </tfoot>

        </table>
      </div>

    </div>
    
    <button onclick="readBoard()">Read Board</button>
    <button onclick="pauseSpeech()">Pause</button>
    <button onclick="continueSpeech()">Resume</button>

  </body>

  <script src="speech.js"></script>
  <script src="whiteboard.js"></script>
  <script src="script.js"></script>
  <script>
    loadWorkflow();
    var logged_user =  
    <?php if($_SESSION["email"])
    {
      echo "'".$_SESSION["email"]."'";
    }
    ?>;
  </script>
</html>
