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
    <link rel="stylesheet" href="styles.css" />
    <script src="script.js"></script>
  </head>
  <body>
    
    
    <div class="sidenav">
      <p>Username</p>
      <button>Log out</button>
      <hr>

      <form action="whiteboard.php">
        <label for="name"><b>Name <br/>   </b> </label>
        <input id="name" type="text" minlength="5" required>
        <br></br>
        <label for="description"><b>Description <br/>  </b> </label>
        <input id="description" type="text" required>
        <br></br>
        <button class="buttonRight" onclick="newWorkflow()">New Workflow</button>
        <br></br>
      </form>
     
      
      <hr>
      
      <div id="boards_area">

        <?php
            foreach(getWorkFlows()->workflows as $wf) {
              echo '<button onclick="getWorkflowData(\''.$wf->name.'\')">'.$wf->name.'</button>';
            } 
        ?>

      </div>
    </div>

    <div class="main">
        <!-- Navbar -->
        <div class="navbar">
            <ul>
              <li><a href="#">Test</a></li>
              <li><a href="#">Test2</a></li>
              <li><a href="#">Test3</a></li>
              <li><a href="#">Test4</a></li>
            </ul>

        </div>
    
      <div class="board">
    
        <table id="mainTable">
          <thead>
            <tr id="tableHeaders">
              <th header="1">Header 1</th>
              <th header="2">Header 2</th>
              <th header="3">Header 3</th>
            </tr>
          </thead>

          <tbody>
            <tr id="tableContent">

              <td header="1">
                
                <button onclick="createNote(this.parentNode)">+</button>
              </td>
              
              <td header="2">
                <button>+</button>
              </td>

              <td header="3">
                
                <button>+</button>
              </td>

            </tr>
          </tbody>
          <tfoot></tfoot>
        </table>
      </div>
    </div>

    

  </body>

  <script src="whiteboard.js"></script>
</html>
