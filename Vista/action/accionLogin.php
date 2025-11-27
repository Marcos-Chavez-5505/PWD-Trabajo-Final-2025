<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';

$session = new Session();
$control = new ControlUsuario();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /PWD-TP-FINAL/Vista/public/cuenta.php?err=401");
    exit;
}

$nombreUsuario = $_POST['nombreUsuario'] ?? '';
$password = $_POST['password'] ?? '';

$usuario = $control->autenticar($nombreUsuario, $password);

if (!$usuario) {
    header("Location: /PWD-TP-FINAL/Vista/public/cuenta.php?errLogin=1");
    exit;
}

$session->iniciarSesion($usuario);

$controlRolUsuario = new controlUsuarioRol();
$listaRoles = $controlRolUsuario->listarUsuarios($usuario->getIdusuario());

$rol = "cliente";
if (count($listaRoles) > 0) {
    $rol = $listaRoles[0]->getObjRol()->getDescripcionRol();
}

if (strtolower($rol) === "administrador") {
    header("Location: /PWD-TP-FINAL/Vista/admin/usuarios.php");
} else {
    header("Location: /PWD-TP-FINAL/Vista/public/index.php");
}
exit;
