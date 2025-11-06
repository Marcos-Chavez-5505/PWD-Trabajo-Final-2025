<?php
session_start();

include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD/configuracion.php';

if (!isset($_POST['nombreUsuario'], $_POST['password'])) {
    header("Location: /PWD/vista/TP/5/2/login.php?error=Faltan datos");
    exit();
}

$nombreUsuario = trim($_POST['nombreUsuario']);
$password = trim($_POST['password']);

$controlUsuario = new ControlUsuario();
$usuario = $controlUsuario->autenticar($nombreUsuario, $password);

if ($usuario) {
    $_SESSION['usuario'] = $usuario->getNombreUsuario();
    header("Location: /PWD/vista/TP/5/2/paginaSegura.php");
    exit();
} else {
    header("Location: /PWD/vista/TP/5/2/login.php?error=Usuario o contraseña incorrectos");
    exit();
}
?>
