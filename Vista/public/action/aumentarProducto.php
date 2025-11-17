<?php
include_once '../../../configuracion.php';
$base = new bdCarritoCompras();

$idProducto = $_POST['idproducto'] ?? null;
$session = new Session();
$idUsuario = $session->getIdUsuario();

if (!$idProducto || !$idUsuario) {
    echo json_encode(['ok' => false, 'msg' => 'Datos incompletos']);
    exit;
}

// Compra activa
$sqlCompra = "
    SELECT c.idcompra 
    FROM compra c
    LEFT JOIN compraestado ce ON c.idcompra = ce.idcompra
    LEFT JOIN compraestadotipo cet ON ce.idcompraestadotipo = cet.idcompraestadotipo
    WHERE c.idusuario = $idUsuario
    ORDER BY ce.cefechaini DESC
    LIMIT 1
";
$base->Ejecutar($sqlCompra);
$compra = $base->Registro();
$idCompra = $compra['idcompra'] ?? null;
if (!$idCompra) { echo json_encode(['ok' => false, 'msg' => 'Carrito no encontrado']); exit; }

// Aumentar cantidad
$sqlItem = "SELECT cicantidad, proprecio FROM compraitem ci
            INNER JOIN producto p ON ci.idproducto = p.idproducto
            WHERE ci.idcompra = $idCompra AND ci.idproducto = $idProducto";
$base->Ejecutar($sqlItem);
$item = $base->Registro();
if (!$item) { echo json_encode(['ok'=>false,'msg'=>'Producto no encontrado']); exit; }

$nuevaCantidad = $item['cicantidad'] + 1;
$sqlUpdate = "UPDATE compraitem SET cicantidad = $nuevaCantidad WHERE idcompra = $idCompra AND idproducto = $idProducto";
$base->Ejecutar($sqlUpdate);

echo json_encode(['ok'=>true,'cantidad'=>$nuevaCantidad,'precio'=>$item['proprecio']]);
