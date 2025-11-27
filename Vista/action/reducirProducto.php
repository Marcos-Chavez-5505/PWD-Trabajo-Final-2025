<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
header('Content-Type: application/json');

try {
    $idProducto = $_POST['idproducto'] ?? null;

    $session = new Session();
    $idUsuario = $session->getIdUsuario();

    $ctrl = new ControlCompra();
    $respuesta = $ctrl->procesarDisminuirProducto($idUsuario, $idProducto);

    echo json_encode($respuesta);
} catch (Exception $e) {
    echo json_encode(['ok' => false, 'msg' => $e->getMessage()]);
}
?>
