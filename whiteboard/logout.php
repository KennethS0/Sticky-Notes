<?php
session_start();
//logout actual session
unset($_SESSION["email"]);


echo ("<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <head>
        <meta http-equiv='refresh' content='0; URL=main.html' />
    <title>Whiteboard: Saliendo del sistema!</title>
</head>
<body></body>
</html>");
?>