<?php
require_once $_SERVER['DOCUMENT_ROOT']."/PWD-TP-FINAL/Vista/action/accionMisCompras.php"; 
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Compras</title>

    <link rel="stylesheet" href="/PWD-TP-FINAL/Vista/css/tpFinal.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="/PWD-TP-FINAL/Vista/css/tpFinal.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



</head>
<body>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/Vista/estructura/header.php'; ?>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>