<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);

include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";

header('Content-Type: application/json; charset=utf-8');

$input = json_decode(file_get_contents('php://input'), true);
$usuario = $input['idUsuario'] ?? null;

$controlCarrito = new ControlCarrito();

if ($usuario && $controlCarrito->comprarCarrito($usuario)) {
    $_SESSION['flash_msg'] = [
        'tipo' => 'success',
        'texto' => '✅ La compra se realizó correctamente.'
    ];
} else {
    $_SESSION['flash_msg'] = [
        'tipo' => 'danger',
        'texto' => '❌ No hay compras pendientes o hubo un error al procesarlas.'
    ];
}

echo json_encode(['ok' => true]);
?>
