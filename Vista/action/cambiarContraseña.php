<?php 
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";
header('Content-Type: application/json');

$session = new Session();

if (!$session->activa()) {
    echo json_encode(['ok' => false, 'msg' => 'Debes iniciar sesión.']);
    exit;
}

if ($_POST){
    $usuarioSolicitado = $_POST['username'];
    $usuarioLogueado = $session->getUsuario(); 
    $idUsuarioLogueado = $session->getIdUsuario(); 
    
    if ($usuarioSolicitado !== $usuarioLogueado) {
        echo json_encode(['ok' => false, 'msg' => 'No tienes permisos para modificar esta cuenta.']);
        exit;
    }

    $contraseñaActual = $_POST['currentPassword'];
    $nuevaContraseña = $_POST['newPassword'];

    $controlUsuario = new ControlUsuario;
    if($controlUsuario->cambiarContraseña($usuarioSolicitado, $contraseñaActual, $nuevaContraseña)){
        echo json_encode(['ok' => true, 'msg' => 'Los cambios se aplicaron exitosamente.']);
    }else{
        echo json_encode(['ok' => false, 'msg' => 'Hubo un error en el proceso.']);
    }
}
?>