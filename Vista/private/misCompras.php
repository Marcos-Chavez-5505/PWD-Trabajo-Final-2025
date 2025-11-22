<?php
require_once $_SERVER['DOCUMENT_ROOT']."/PWD-TP-FINAL/Vista/action/accionRolesPermitidos.php";
require_once $_SERVER['DOCUMENT_ROOT']."/PWD-TP-FINAL/Vista/action/accionMisCompras.php"; 

include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/Vista/estructura/header.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
?>

<div class="container py-5 min-vh-100 d-flex flex-column">
    <h2>Mis Compras</h2>
    <hr>

    <?php if (empty($compras)): ?>
        <p>No realizaste ninguna compra todavía.</p>
    <?php else: ?>

        <?php 
        foreach ($compras as $compra): 
            $idCompra = $compra->getIdcompra();
            
            // Estado actual
            $estadoObj = $controlCompraEstado->obtenerEstadoActual($idCompra);
            $estado = $estadoObj ? $estadoObj->getObjCompraEstadoTipo()->getCETdescripcion() : "Sin estado";

            // Items de la compra
            $compraItem = new compraItem();
            $items = $compraItem->listar("idcompra = {$idCompra}");
        ?>
        
        <div class="card mb-4">
            <div class="card-header">
                <strong>Compra #<?= $idCompra ?></strong> 
                - Fecha: <?= $compra->getCofecha() ?>
                <span class="badge bg-primary float-end"><?= $estado ?></span>
            </div>

            <div class="card-body">
                <?php if (!empty($items)): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($items as $item): ?>
                            <?php $producto = $item->getObjProducto(); ?>
                            <tr>
                                <td><?= $producto->getPronombre() ?></td>
                                <td><?= $item->getCicantidad() ?></td>
                                <td>$<?= number_format($producto->getProprecio(), 2) ?></td>
                                <td>$<?= number_format($item->getCicantidad() * $producto->getProprecio(), 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No hay productos en esta compra.</p>
                <?php endif; ?>
            </div>
        </div>

        <?php endforeach; ?>

    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/Vista/estructura/footer.php'; ?>