<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/Vista/estructura/header.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/modelo/conector/bdCarritoCompras.php";

$session = new Session();

if (!$session->activa()) {
    echo "<div class='alert alert-warning text-center mt-5'>Debes iniciar sesión para ver tu carrito.</div>";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/Vista/estructura/footer.php";
    exit;
}

$idUsuario = $session->getIdUsuario();
$base = new bdCarritoCompras();

// $sqlCompra = "
//     SELECT c.idcompra 
//     FROM compra c
//     LEFT JOIN compraestado ce ON c.idcompra = ce.idcompra
//     LEFT JOIN compraestadotipo cet ON ce.idcompraestadotipo = cet.idcompraestadotipo
//     WHERE c.idusuario = $idUsuario
//     ORDER BY ce.cefechaini DESC
//     LIMIT 1
// ";
// $base->Ejecutar($sqlCompra);
// $compra = $base->Registro();

// $productos = [];
// if ($compra && isset($compra['idcompra'])) {
//     $idCompra = $compra['idcompra'];
//     $sqlItems = "
//         SELECT p.idproducto, p.pronombre, p.prodetalle, p.proprecio, ci.cicantidad
//         FROM compraitem ci
//         INNER JOIN producto p ON ci.idproducto = p.idproducto
//         WHERE ci.idcompra = $idCompra
//     ";
//     $base->Ejecutar($sqlItems);
//     while ($row = $base->Registro()) {
//         $productos[] = $row;
//     }
// }

$controlCarrito = new ControlCarrito();
$itemsCarrito = $controlCarrito->obtenerItemsDelCarrito($idUsuario);
?>
<head>
<title>Carrito de compras</title>
    <link rel="stylesheet" type="text/css" href="../js/jquery-easyui-1.6.6/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="../js/jquery-easyui-1.6.6/themes/icon.css">
    <script src="../js/jquery-easyui-1.6.6/jquery.min.js"></script>
    <script src="../js/jquery-easyui-1.6.6/jquery.easyui.min.js"></script>
    <script src="../js/carrito.js"></script>
</head>
<main class="container py-5">
    <h2 class="text-center mb-4">🛒 Mi Carrito</h2>

    <?php if (empty($itemsCarrito)): ?>
        <div class="alert alert-info text-center">Tu carrito está vacío.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Producto</th>
                        <th>Detalle</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($itemsCarrito as $item):
                        $producto = $item->getObjProducto();
                        $subtotal = $producto->getProprecio() * $item->getCicantidad();
                        $total += $subtotal;
                    ?>
                    <tr data-id-producto="<?= $producto->getIdproducto(); ?>" data-precio="<?= $producto->getProprecio(); ?>">
                        <td><?= htmlspecialchars($producto->getPronombre()); ?></td>
                        <td><?= htmlspecialchars($producto->getProdetalle()); ?></td>
                        <td>$<?= number_format($producto->getProprecio(), 2, ',', '.'); ?></td>
                        <td class="cantidad"><?= $item->getCicantidad(); ?></td>
                        <td class="subtotal">$<?= number_format($subtotal, 2, ',', '.'); ?></td>
                        <td>
                            <div class="d-flex justify-content-center align-items-center gap-1">
                                <button class="btn btn-sm btn-secondary reducir-cantidad" data-id-producto="<?= $producto->getIdproducto(); ?>">-</button>
                                <button class="btn btn-sm btn-secondary aumentar-cantidad" data-id-producto="<?= $producto->getIdproducto(); ?>">+</button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <tr class="table-secondary fw-bold">
                        <td colspan="4" class="text-end">Total:</td>
                        <td id="total-carrito">$<?= number_format($total, 2, ',', '.'); ?></td>
                        <td>
                            <button id="finalizar-compra" class="btn btn-success btn-sm">
                                <i class="bi bi-bag-check"></i> Finalizar Compra
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</main>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/Vista/estructura/footer.php"; ?>