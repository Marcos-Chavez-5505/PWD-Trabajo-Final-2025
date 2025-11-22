<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/modelo/conector/bdCarritoCompras.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/control/ControlCarrito.php';

header('Content-Type: application/json');

$session = new Session();

if (!$session->activa()) {
    echo json_encode(['ok' => false, 'msg' => 'Debes iniciar sesión.']);
    exit;
}

$idUsuario = $session->getIdUsuario();
$idProducto = isset($_POST['idproducto']) ? intval($_POST['idproducto']) : 0;

if ($idProducto > 0) {
    $carrito = new ControlCarrito;
    if ($carrito->agregarAlCarrito($idUsuario, $idProducto, 1)){
        echo json_encode(['ok' => true, 'msg' => 'Producto añadido al carrito.']);
    }else{
        echo json_encode(['ok' => false, 'msg' => 'No hay suficiente Stock']);    
    }
} else {
    echo json_encode(['ok' => false, 'msg' => 'Producto no válido.']);
}
?>
