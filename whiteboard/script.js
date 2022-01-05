function notificar(msg) {
  notificador = document.getElementById("notificaciones");
  notificador.innerHTML = msg;
  setTimeout(
    function (htmlobj) {
      htmlobj.innerHTML = "";
    },
    5000,
    notificador
  );
}

function register() {
  email = document.getElementById("user").value;
  password = document.getElementById("password").value;

  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      respuesta = eval("(" + xhttp.responseText + ")");
      console.log(respuesta);
      if (respuesta[0] == false) {
        alert(JSON.stringify(respuesta[1]));
      } else {
        alert(JSON.stringify(respuesta[1]));
      }
    }
  };
  xhttp.open("POST", "register.php", false);
  var formData = new FormData();
  if (email != "" && password != "") {
    formData.append("email", email);
    formData.append("password", password);
  }
  xhttp.send(formData);
}

function login() {
  email = document.getElementById("user").value;
  password = document.getElementById("password").value;

  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      respuesta = eval("(" + xhttp.responseText + ")");

      if (respuesta[0] == false) {
        alert(JSON.stringify(respuesta[1]));
      } else {
        window.location.href = "whiteboard.php";
      }
    }
  };
  xhttp.open("POST", "login.php", true);
  var formData = new FormData();
  formData.append("email", email);
  formData.append("password", password);
  xhttp.send(formData);
}

function newWorkflow() {
  workflowName = document.getElementById("name").value;
  description = document.getElementById("description").value;
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      respuesta = eval("(" + xhttp.responseText + ")");

      if (respuesta[0] == false) {
        alert(JSON.stringify(respuesta[1]));
      }
    }
  };
  xhttp.open("POST", "workflow.php", false);
  var formData = new FormData();
  formData.append("option", 1);
  formData.append("email", "lisethGonz6");
  formData.append("name", workflowName);
  formData.append("description", description);
  xhttp.send(formData);
}

function newSticky() {
  text = document.getElementById("text").value;
  workflowName = document.getElementById("workflowName").value;
  state = document.getElementById("state").value;
  color = document.getElementById("color").value;
  size = document.getElementById("size").value;

  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      respuesta = eval("(" + xhttp.responseText + ")");

      if (respuesta[0] == false) {
        alert(JSON.stringify(respuesta[1]));
      }
    }
  };
  xhttp.open("POST", "sticky.php", false);
  var formData = new FormData();
  formData.append("option", 1);
  formData.append("email", "lisethGonz6");
  formData.append("name", workflowName);
  formData.append("text", text);
  formData.append("state", state);
  formData.append("color", color);
  formData.append("size", size);
  xhttp.send(formData);
}

//Load the workflow states
function loadStates(email, workflowName) {

  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      
      // Gets the data from php
      const jsonData = JSON.parse(xhttp.responseText);  
      let states = jsonData['states'];

      // Updates the name and the description
      document.getElementById("workflowName").innerText = jsonData["name"];
      document.getElementById("workflowDescription").innerText = jsonData["description"];

      states.forEach(state => {
        // Creates each column 
        let stickyArea = addNewColumn(state.name);
        let stickies = state.stickies;
        
        // Creates each note in the column
        stickies.forEach(sticky => {
          createNewNote(stickyArea, sticky);
        });

      });
      
    }
  };

  let index = document.getElementById("workflowsCombo").selectedIndex;

  xhttp.open("POST", "workflow.php", false);
  var formData = new FormData();
  formData.append("option", 2);
  formData.append("action", 5);
  formData.append("wfIndex", index);
  formData.append("email", email);
  formData.append("name", workflowName);
  xhttp.send(formData);
}


//Load the workflow states
function updateWFStates() {

  let wfStates = [...document.getElementsByClassName("header")];

  let wfStateStickies = [...document.getElementsByClassName("sticky-area")];

  let stickiesJSON = [];

  wfStateStickies.forEach(stArea => {
    let stickies = [...stArea.children];
    let stickyArray = [];
    stickies.forEach(st => {
        let sticky = {
          text : st.getElementsByTagName("p")[0].innerText,
          color : st.style.backgroundColor,
          height : st.style.height,
          width : st.style.width
        }
        stickyArray.push(sticky);
    });
    stickiesJSON.push(stickyArray);
  });
 
  

  let statesArray = [];
  for(let i=0;i<stickiesJSON.length;i++)
  {
    let state = {
      name : wfStates[i].innerText,
      stickies : stickiesJSON[i]
     
    }
    statesArray.push(state);

  }

  
  let wfIndex= document.getElementById("workflowsCombo").selectedIndex;


  console.log(wfIndex);
  console.log(statesArray);

  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      

      respuesta = xhttp.responseText;
      console.log(respuesta);
      
    }
  };
  xhttp.open("POST", "workflow.php", false);
  var formData = new FormData();
  formData.append("option", 6);
  formData.append("email", "lisethGonz6");
  formData.append("states", statesArray);
  formData.append("wfIndex", wfIndex);
  xhttp.send(formData);
}

