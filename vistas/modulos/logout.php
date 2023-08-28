<?php
session_start();
$deslogin=new controladorUsuarios();
$respuesta=$deslogin::ctrDesloguearse();
if ($respuesta) {
    session_destroy();
    header('Location: inicio.php');
}
// redirigir al usuario a la página de inicio de sesión
