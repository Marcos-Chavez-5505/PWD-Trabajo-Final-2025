<?php
require_once $_SERVER['DOCUMENT_ROOT']."/PWD-TP-FINAL/Vista/action/accionRolesPermitidos.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';

$session = new Session();
$session->validar(2); 

$idUsuario = $session->getIdusuario();

$controlCompra = new ControlCompra();
$controlEstado = new ControlCompraEstado();

$comprasBD = $controlCompra->obtenerComprasPorUsuario($idUsuario);

$compras = [];

foreach ($comprasBD as $compraObj) {
    $idCompra = $compraObj->getIdcompra();

    $estadoObj = $controlEstado->obtenerEstadoActual($idCompra);
    $estado = $estadoObj ? $estadoObj->getObjCompraEstadoTipo()->getCETdescripcion() : "Sin estado";

    $itemObj = new compraItem();
    $itemsBD = $itemObj->listar("idcompra = {$idCompra}");

    $items = [];
    foreach ($itemsBD as $it) {
        $producto = $it->getObjProducto();

        $items[] = [
            'producto' => $producto->getPronombre(),
            'cantidad' => $it->getCicantidad(),
            'precio'   => $producto->getProprecio(),
            'subtotal' => $it->getCicantidad() * $producto->getProprecio()
        ];
    }

    $compras[] = [
        'id' => $idCompra,
        'fecha' => $compraObj->getCofecha(),
        'estado' => $estado,
        'items' => $items
    ];
}
