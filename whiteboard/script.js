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
  email = document.getElementById("email").value;
  password = document.getElementById("password").value;

  var xhttp = new XMLHttpRequest();
  xhttp.open("POST", "register.php", false);
  var formData = new FormData();
  formData.append("email", email);
  formData.append("password", password);
  xhttp.send(formData);
}

function login() {
  email = document.getElementById("email").value;
  password = document.getElementById("password").value;
  console.log("Entra");
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      respuesta = eval("(" + xhttp.responseText + ")");

      if (respuesta[0] == false) {
        alert("Error: Las credenciales del usuario no son válidas.");
      } else {
        window.location.href = "whiteboard.html";
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
