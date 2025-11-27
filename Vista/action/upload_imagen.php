<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
include_once ROOT . 'control/ControlAdmin.php';
require ROOT . 'util/vendor/autoload.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $id = $_POST['idproducto_img'] ?? null;
    if (!$id) throw new Exception("ID de producto no recibido.");
    if (!isset($_FILES['proimagen']) || $_FILES['proimagen']['error'] !== UPLOAD_ERR_OK)
        throw new Exception("No se subió ninguna imagen válida.");

    $admin = new ControlAdmin();
    $admin->actualizarImagenProducto($id, $_FILES);

    echo json_encode(['success' => true, 'mensaje' => 'Imagen actualizada correctamente.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'errorMsg' => $e->getMessage()]);
}
