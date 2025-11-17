<?php 
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";
header('Content-Type: application/json');

$session = new Session();

if (!$session->activa()) {
    echo json_encode(['ok' => false, 'msg' => 'Debes iniciar sesión.']);
    exit;
}

if ($_POST){
    $usuario = $_POST['username'];
    $contraseñaActual = $_POST['currentPassword'];
    $nuevaContraseña = $_POST['newPassword'];

    $controlUsuario = new ControlUsuario;
    if($controlUsuario->cambiarContraseña($usuario, $contraseñaActual, $nuevaContraseña)){
        echo json_encode(['ok' => true, 'msg' => 'Los cambios se aplicaron exitosamente.']);
    }else{
        echo json_encode(['ok' => false, 'msg' => 'Hubo un error en el proceso.']);
    }
}




?>