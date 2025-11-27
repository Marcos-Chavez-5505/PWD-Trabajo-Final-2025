<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
date_default_timezone_set('America/Argentina/Buenos_Aires');

$control = new ControlUsuario();
$controlRol = new ControlRol();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['idUsuario'])) {
        header("Location: /PWD-TP-FINAL/Vista/admin/usuarios.php");
        exit();
    }

    $idUsuario = intval($_GET['idUsuario']);
    $usuario = $control->buscarUsuario($idUsuario);

    if (!$usuario) {
        header("Location: /PWD-TP-FINAL/Vista/admin/usuarios.php?error=Usuario no encontrado");
        exit();
    }

    $listaRoles = $controlRol->listarRoles();

	$controlUsuarioRol = new controlUsuarioRol();
	$idRolActual = $controlUsuarioRol->listarUsuarios($idUsuario); // arreglo con roles asociados al usuario (solo asumimos que 1)
	$idRolActual = $idRolActual[0]->getIdrol(); // esto está mal porque llama al ORM
    // $usuarioRol = new UsuarioRol();
    
    // // CORRECCIÓN: obtener solo el ID del rol actual
    // $rolObj = $usuarioRol->rolDeUsuario($usuario->getIdusuario());
    // $idRolActual = $usuarioRol->rolDeUsuario($usuario->getIdusuario());

	$datosVista = $control->obtenerDatosParaVista($usuario);
}

elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idUsuario = intval($_POST['idUsuario']);
    $idRol = intval($_POST['idrol']); 

    $datos = [
        'idusuario' => $idUsuario,
        'usnombre' => trim($_POST['usnombre']),
        'uspass' => trim($_POST['uspass']),
        'usmail' => trim($_POST['usmail']),
        'usdeshabilitado' => isset($_POST['usdeshabilitado']) ? null : date('Y-m-d H:i:s'),
        'idrol' => $idRol
    ];

    if ($control->modificarUsuario($datos)) {
        header("Location: /PWD-TP-FINAL/Vista/admin/usuarios.php?exito=Usuario actualizado correctamente");
    } else {
        header("Location: /PWD-TP-FINAL/Vista/admin/usuarios.php?error=No se pudo actualizar el usuario");
    }
    exit();
}

?>