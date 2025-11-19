<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
require $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/util/vendor/autoload.php';
header('Content-Type: application/json');

use Intervention\Image\ImageManager;

$id = $_POST['idproducto_img'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'errorMsg' => 'ID de producto no recibido']);
    exit;
}

if (!isset($_FILES['proimagen']) || $_FILES['proimagen']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'errorMsg' => 'No se subió ninguna imagen']);
    exit;
}

try {
    $dir = $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/Vista/image';
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }

    $ext = pathinfo($_FILES['proimagen']['name'], PATHINFO_EXTENSION);
    $nombreImagen = $_FILES['proimagen']['name'];

    // forma vieja que pone un nombre con letras random
    // $nombreImagen = 'prod_' . uniqid() . '.' . $ext;

    move_uploaded_file($_FILES['proimagen']['tmp_name'], $dir . '/' . $nombreImagen);

    modificarImagenExistente($dir . '/' . $nombreImagen);

    // Actualizar BD
    $controlAdmin = new controlAdmin();
    $controlAdmin->actualizarProducto($id, [
        'proimagen' => $nombreImagen
    ]);

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'errorMsg' => $e->getMessage()]);
}


function modificarImagenExistente($rutaImagen, $ancho = 300, $alto = 300, $calidad = 80) {
    if (!file_exists($rutaImagen) || !is_readable($rutaImagen)) {
        throw new Exception("La imagen no existe o no se puede leer: $rutaImagen");
    }

    $manager = ImageManager::gd();

    // Cargar, modificar y sobrescribir
    $manager->read($rutaImagen)
            ->cover($ancho, $alto)  // Recorte centrado
            ->save($rutaImagen, $calidad);
}
?>