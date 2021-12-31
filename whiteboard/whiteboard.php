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
              echo '<button onclick="test(\''.$wf->name.'\')">'.$wf->name.'</button>';
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
    
        <table id="mainTable" border="2px" cellspacing="5px" cellpadding="15px">
          <thead style="background-color: rgb(152, 150, 245); color: white">
            <tr>
              <th>On hold</th>
              <th>In progress</th>
              <th>Needs review</th>
              <th>Approved</th>
            </tr>
          </thead>


          <tbody>
            <tr>
              <td width="340" height="700" contenteditable="true"></td>
              <td width="340" height="700" contenteditable="true"></td>
              <td width="340" height="700" contenteditable="true"></td>
              <td width="340" height="700" contenteditable="false">
                
                <label for="text"><b>Text <br/>   </b> </label>
                <input id="text" type="text" />
                <br></br>
                <label for="workflowName"><b>Workflow name <br/>  </b> </label>
                <input id="workflowName" type="text" />
                <br></br>
                <label for="state"><b>State <br/>  </b> </label>
                <input id="state" type="text" />
                <br></br>
              <label for="color"><b>Color <br/>  </b> </label>
                <input id="color" type="text" />
                <br></br>
              <label for="size"><b>Size <br/>  </b> </label>
                <input id="size" type="text" />
                <br></br>
                <label for="posX"><b>PosX <br/>  </b> </label>
                <input id="posX" type="text" />
                <br></br>
                <label for="posY"><b>PosY <br/>  </b> </label>
                <input id="posY" type="text" />
                <br></br>
                <button class="buttonRight" onclick="newSticky()">New Sticky</button>
              </td>
            </tr>
          </tbody>
          <tfoot></tfoot>
        </table>
      <link rel="stylesheet" href="styles.css" />    
    </div>

    

  </body>

  <script src="whiteboard.js"></script>
</html>
