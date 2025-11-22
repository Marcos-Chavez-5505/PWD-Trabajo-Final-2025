<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';

$session = new Session();

$usuarioIngresado = $_POST['nombreUsuario'] ?? "";
$passIngresada = $_POST['password'] ?? "";

$user = new Usuario();
$lista = $user->listar("usnombre = '$usuarioIngresado'");

if (count($lista) == 0) {
    header("Location: login.php?error=Usuario incorrecto");
    exit;
}

$user = $lista[0];

if ($user->getUsdeshabilitado() !== null) {
    header("Location: login.php?error=Su cuenta está deshabilitada");
    exit;
}

if ($user->getUspass() !== $passIngresada) {
    header("Location: login.php?error=Contraseña incorrecta");
    exit;
}

$session->iniciarSesion($user);

header("Location: /PWD-TP-FINAL/Vista/home.php");
exit;
