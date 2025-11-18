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

// ✅ Buscar la compra con estado "Iniciada" (carrito activo)
$sqlCompra = "
    SELECT c.idcompra 
    FROM compra c
    INNER JOIN compraestado ce ON c.idcompra = ce.idcompra
    INNER JOIN compraestadotipo cet ON ce.idcompraestadotipo = cet.idcompraestadotipo
    WHERE c.idusuario = $idUsuario
    AND cet.cetdescripcion = 'Iniciada'
    AND ce.cefechafin IS NULL
    ORDER BY c.idcompra DESC
    LIMIT 1
";
$base->Ejecutar($sqlCompra);
$compra = $base->Registro();
$idCompra = $compra['idcompra'] ?? null;

if (!$idCompra) { 
    echo json_encode(['ok' => false, 'msg' => 'Carrito no encontrado']); 
    exit; 
}

// ✅ Buscar el item del producto dentro de esa compra
$sqlItem = "
    SELECT cicantidad, proprecio 
    FROM compraitem ci
    INNER JOIN producto p ON ci.idproducto = p.idproducto
    WHERE ci.idcompra = $idCompra AND ci.idproducto = $idProducto
";
$base->Ejecutar($sqlItem);
$item = $base->Registro();

if (!$item) { 
    echo json_encode(['ok'=>false,'msg'=>'Producto no encontrado']); 
    exit; 
}

$nuevaCantidad = $item['cicantidad'] - 1;

if ($nuevaCantidad > 0) {
    $sqlUpdate = "UPDATE compraitem 
                  SET cicantidad = $nuevaCantidad 
                  WHERE idcompra = $idCompra AND idproducto = $idProducto";
    $base->Ejecutar($sqlUpdate);
} else {
    $sqlDelete = "DELETE FROM compraitem 
                  WHERE idcompra = $idCompra AND idproducto = $idProducto";
    $base->Ejecutar($sqlDelete);
}

echo json_encode(['ok'=>true,'cantidad'=>max(0,$nuevaCantidad),'precio'=>$item['proprecio']]);
?>
