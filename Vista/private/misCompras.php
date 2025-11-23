<?php
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

        <?php foreach ($compras as $compra): ?>
        
        <div class="card mb-4">
            <div class="card-header">
                <strong>Compra #<?= $compra['id'] ?></strong> 
                - Fecha: <?= $compra['fecha'] ?>
                <span class="badge bg-primary float-end"><?= $compra['estado'] ?></span>
            </div>

            <div class="card-body">

                <?php if (!empty($compra['items'])): ?>
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
                        <?php foreach ($compra['items'] as $item): ?>
                            <?php $producto = $item['producto']; ?>
                            <tr>
                                <td><?= $producto['nombre'] ?></td>
                                <td><?= $item['cantidad'] ?></td>
                                <td>$<?= number_format($producto['precio'], 2) ?></td>
                                <td>$<?= number_format($item['subtotal'], 2) ?></td>
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

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/Vista/estructura/footer.php'; ?>
