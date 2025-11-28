<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
include_once ROOT . 'control/ControlAdmin.php';
require ROOT . 'util/vendor/autoload.php';

try {
    $admin = new ControlAdmin();
    $respuesta = $admin->actualizarImagenProducto($_POST['idproducto_img'] ?? null, $_FILES);
    echo json_encode($respuesta);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'errorMsg' => $e->getMessage()]);
}
