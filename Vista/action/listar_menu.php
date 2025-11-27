<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
header('Content-Type: application/json; charset=utf-8');

try {
    $data = data_submitted();
    $ctrl = new AbmMenu();
    $resultado = $ctrl->obtenerDatosMenu($data);

    echo json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
?>
