<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";
header("Content-Type: application/json");

$data = data_submitted();
$session = new Session();

$retorno = ['ok' => false];

if (!$session->activa()) {
    $retorno['msg'] = 'Debes iniciar sesión.';
    echo json_encode($retorno);
    exit;
}

$usuarioLogueado = $session->getUsuario(); 

if (!isset($data['username'], $data['currentPassword'], $data['newPassword'])) {
    $retorno['msg'] = 'Datos incompletos.';
    echo json_encode($retorno);
    exit;
}

if ($data['username'] !== $usuarioLogueado) {
    $retorno['msg'] = 'No tienes permisos para modificar esta cuenta.';
    echo json_encode($retorno);
    exit;
}

$control = new ControlUsuario();
$ok = $control->cambiarContraseña($data['username'], $data['currentPassword'], $data['newPassword']);

$retorno['ok'] = $ok;
$retorno['msg'] = $ok ? 'Contraseña modificada correctamente.' : 'La contraseña actual no coincide.';

echo json_encode($retorno);
