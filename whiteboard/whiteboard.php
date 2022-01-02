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
    <script src="script.js"></script>
  </head>
  <body>
      
    <!-- Navbar -->
    <nav>
      <ul class="menu">
        <li>Username</li>
        <li>Workflows:
          <select name="workflows" id="workflowsCombo">
            <?php
              foreach(getWorkFlows()->workflows as $wf) {
                echo '<option value="'.$wf->name.'">'.$wf->name.'</option>'; 
              }
            ?>
          </select>

        </li>
        <li><button>Load</button></li>
        <li><button>Delete</button></li>

        <li>Create: <input placeholder="Name" type="text" name="new_wf_name" id="new_wf_na"></li>
        <li><input placeholder="Description" type="text" name="new_wf_description" id="new_wf_desc"></li>
        <li><button>Create</button></li>
      </ul>
    </nav>

    <!-- Main content of the page -->
    <div class="content">

      <div class="current-workflow">
        <h1>Current Workflow</h1>
        <p>Workflow description</p>
      </div>

      <div class="workflow">
        <!-- Workflow -->
        <table style=" table-layout: fixed">
          
          <thead>
            <tr id="head-row">
              <th> 
                  <div contentEditable="true" class="header"> Inicio </div> 
                  <button onclick="deleteColumn(this.parentElement)">X</button>
                </th>
              <th> 
                  <div contentEditable="true" class="header"> En progreso </div>
                  <button onclick="deleteColumn(this.parentElement)">X</button></th>
              <th>
              <div contentEditable="true" class="header"> Finalizado </div>
                <button onclick="deleteColumn(this.parentElement)">X</button>
              </th>
            </tr>
          </thead>
          
          <tbody>
            <tr id="body-row">
              <td>
                <div class="sticky-area">

                </div>
                <button onclick="createNewNote()">Add Note</button>
              </td>
              <td>
                <div class="sticky-area">

                </div>
                <button onclick="createNewNote()">Add Note</button>
              </td>
              <td>
              <div class="sticky-area">

              </div>
              <button onclick="createNewNote()">Add Note</button>
              </td>
            </tr>
          </tbody>
        </table>

        <button class="addColumnButton" onclick="addNewColumn()">+</button>
      </div>

    </div>
  </body>

  <script src="whiteboard.js"></script>
</html>
