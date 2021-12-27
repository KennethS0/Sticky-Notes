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
