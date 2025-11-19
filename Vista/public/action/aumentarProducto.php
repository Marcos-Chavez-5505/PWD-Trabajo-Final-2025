<?php
include_once '../../../configuracion.php';

$idProducto = $_POST['idproducto'] ?? null;
$session = new Session();
$idUsuario = $session->getIdUsuario();

$control = new ControlCompra();
$result = $control->aumentarCantidadProducto($idUsuario, $idProducto);

switch ($result['code']) {
    case 1:
        echo json_encode(['ok' => false, 'msg' => 'Datos incompletos']);
        break;
    case 2:
        echo json_encode(['ok' => false, 'msg' => 'Carrito no encontrado']);
        break;
    case 3:
        echo json_encode(['ok' => false, 'msg' => 'Producto no encontrado']);
        break;
    case 4:
        echo json_encode(['ok' => false, 'msg' => 'Stock insuficiente']);
        break;
    case 5:
        echo json_encode(['ok' => false, 'msg' => 'Error al modificar la cantidad']);
        break;
    case 6:
        echo json_encode(['ok' => true, 'cantidad' => $result['cantidad'], 'precio' => $result['precio']]);
        break;
    default:
        echo json_encode(['ok' => false, 'msg' => 'Error inesperado']);
}












// include_once '../../../configuracion.php';
// $base = new bdCarritoCompras();

// $idProducto = $_POST['idproducto'] ?? null;
// $session = new Session();
// $idUsuario = $session->getIdUsuario();

// if (!$idProducto || !$idUsuario) {
//     echo json_encode(['ok' => false, 'msg' => 'Datos incompletos']);
//     exit;
// }

// $compra = new Compra();
// $idCompra = $compra->listarIDComprasSinEstadoNiFecha($idUsuario) ?? null;

// if (!$idCompra) { 
//     echo json_encode(['ok' => false, 'msg' => 'Carrito no encontrado']); 
//     exit; 
// }

// $compraItem = new CompraItem();
// $item = $compraItem->obtenerDatosItem($idCompra, $idProducto);


// if (!$item) { 
//     echo json_encode(['ok'=>false,'msg'=>'Producto no encontrado']); 
//     exit; 
// }

// $nuevaCantidad = $item['cicantidad'] + 1;
// if ($nuevaCantidad < $item['procantstock']){
//     $compraItem->setCicantidad($nuevaCantidad);
//     $compraItem->modificar();
// }

// echo json_encode(['ok'=>true,'cantidad'=>$nuevaCantidad,'precio'=>$item['proprecio']]);
?>