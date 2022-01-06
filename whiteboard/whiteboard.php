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
    <nav>
      <ul class="menu">
        <li>Username</li>
        <li>Workflows:
          <select onchange="loadWorkflow()" name="workflows" id="workflowsCombo">
            <?php
              foreach(getWorkFlows()->workflows as $wf) {
                echo '<option value="'.$wf->name.'">'.$wf->name.'</option>'; 
              }
            ?>
          </select>

        </li>
        <li><button>Delete</button></li>

        <li>Create: <input placeholder="Name" type="text" name="new_wf_name" id="name"></li>
        <li><input placeholder="Description" type="text" name="new_wf_description" id="description"></li>
        <li><button onclick="newWorkflow()">Create</button></li>
        <li><button onclick="updateWFStates()">Prueba</button></li>
        <li><button onclick="window.location.href='logout.php'">Logout</button></li>
      </ul>
    </nav>

    <!-- Main content of the page -->
    <div class="content">

      <div class="current-workflow">
        <h1 id="workflowName">Current Workflow</h1>
        <p id="workflowDescription">Workflow description</p>
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

        <button class="addColumnButton" onclick="addNewColumn('New Column')">+</button>
      </div>

    </div>
  </body>

  <script src="speech.js"></script>
  <script src="whiteboard.js"></script>
  <script src="script.js"></script>
  <script>
    loadWorkflow();
  </script>
</html>
