<?php 
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
header('Content-Type: application/json');

try {
    $control = new ControlAdmin();
    $respuesta = $control->procesarAccionProducto($_REQUEST);
    echo json_encode($respuesta);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'errorMsg' => $e->getMessage()]);
}
?>
