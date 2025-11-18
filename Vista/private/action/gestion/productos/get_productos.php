<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';

header('Content-Type: application/json');

$session = new Session();

if (!$session->activa()) {
    echo json_encode(['ok' => false, 'msg' => 'Debes iniciar sesión.']);
    exit;
}

$controlAdmin = new controlAdmin();
$productos = $controlAdmin->obtenerProductos();

if ($productos !== false) {
    echo json_encode([
        'total' => count($productos),
        'rows' => $productos
    ]);
} else {
    echo json_encode([
        'total' => 0,
        'rows' => []
    ]);
}
?>