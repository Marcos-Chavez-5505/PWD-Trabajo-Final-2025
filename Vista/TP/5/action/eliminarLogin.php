<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD/configuracion.php';

session_start();

if (!isset($_POST['idUsuario'])) {
    header("Location: /PWD/vista/TP/5/1/listarUsuarios.php?error=ID de usuario no especificado");
    exit();
}

$idUsuario = intval($_POST['idUsuario']);
$control = new ControlUsuario();

if ($control->eliminarUsuario($idUsuario)) {
    header("Location: /PWD/vista/TP/5/1/listarUsuarios.php?exito=Usuario eliminado correctamente");
} else {
    header("Location: /PWD/vista/TP/5/1/listarUsuarios.php?error=No se pudo eliminar el usuario");
}
exit();
?>
