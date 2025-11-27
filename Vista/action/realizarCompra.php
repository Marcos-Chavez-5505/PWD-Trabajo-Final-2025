<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";
header('Content-Type: application/json; charset=utf-8');

try {
    $input = json_decode(file_get_contents('php://input'), true);
    $idUsuario = $input['idUsuario'] ?? null;

    $ctrl = new ControlCarrito();
    $resultado = $ctrl->procesarCompra($idUsuario);

    $_SESSION['flash_msg'] = $resultado['flash'];
    echo json_encode(['ok' => true]);
} catch (Exception $e) {
    echo json_encode(['ok' => false, 'errorMsg' => $e->getMessage()]);
}
?>
