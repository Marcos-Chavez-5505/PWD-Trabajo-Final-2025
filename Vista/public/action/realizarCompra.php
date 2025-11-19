<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php" ;

$input = json_decode(file_get_contents('php://input'), true);
$usuario = $input['idUsuario'] ?? null;

$controlCarrito = new ControlCarrito;

if ($controlCarrito->comprarCarrito($usuario)){
    $exito = true;
    $mensaje = "todo salió bien";
}else{
    $exito = false;
    $mensaje = "No hay compras sin estado";
}

echo json_encode(['ok' => $exito , 'error' => $mensaje]);

?>