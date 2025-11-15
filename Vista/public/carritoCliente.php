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

$sqlCompra = "
    SELECT c.idcompra 
    FROM compra c
    INNER JOIN compraestado ce ON c.idcompra = ce.idcompra
    INNER JOIN compraestadotipo cet ON ce.idcompraestadotipo = cet.idcompraestadotipo
    WHERE c.idusuario = $idUsuario AND cet.cetdescripcion = 'Iniciada' AND ce.cefechafin IS NULL
    LIMIT 1
";
$base->Ejecutar($sqlCompra);
$compra = $base->Registro();

$productos = [];
if ($compra) {
    $idCompra = $compra['idcompra'];
    $sqlItems = "
        SELECT p.idproducto, p.pronombre, p.prodetalle, p.proprecio, ci.cicantidad
        FROM compraitem ci
        INNER JOIN producto p ON ci.idproducto = p.idproducto
        WHERE ci.idcompra = $idCompra
    ";
    $base->Ejecutar($sqlItems);
    while ($row = $base->Registro()) {
        $productos[] = $row;
    }
}
?>
<head>
    <title>Carrito de compras</title>
    
    <link rel="stylesheet" type="text/css" href="../js/jquery-easyui-1.6.6/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="../js/jquery-easyui-1.6.6/themes/icon.css">

    <script src="../js/jquery-easyui-1.6.6/jquery.min.js"></script>
    <script src="../js/jquery-easyui-1.6.6/jquery.easyui.min.js"></script>
</head>

<main class="container py-5">
    <h2 class="text-center mb-4">🛒 Mi Carrito</h2>

    <?php if (empty($productos)): ?>
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
                        <th colspan="2">Acciónes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($productos as $p):
                        $subtotal = $p['proprecio'] * $p['cicantidad'];
                        $total += $subtotal;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($p['pronombre']); ?></td>
                        <td><?= htmlspecialchars($p['prodetalle']); ?></td>
                        <td>$<?= number_format($p['proprecio'], 2, ',', '.'); ?></td>
                        <td><?= $p['cicantidad']; ?></td>
                        <td>$<?= number_format($subtotal, 2, ',', '.'); ?></td>
                        <td class="text-center">
                            <div class="d-flex flex-column flex-md-row justify-content-center gap-1">
                                <button class="btn btn-success btn-sm comprar-producto" data-id-comprar="<?= $p['idproducto']; ?>">
                                    <i class="bi bi-bag-check"></i>
                                    <span class="d-none d-md-inline"> Comprar</span>
                                </button>
                                <button class="btn btn-danger btn-sm eliminar-producto" data-id-borrar="<?= $p['idproducto']; ?>">
                                    <i class="bi bi-trash"></i>
                                    <span class="d-none d-md-inline"> Eliminar</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <tr class="table-secondary fw-bold">
                        <td colspan="5" class="text-end">Total:</td>
                        <td>$<?= number_format($total, 2, ',', '.'); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</main>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const botones = document.querySelectorAll('.eliminar-producto'); // ← Cambié la clase

        botones.forEach(boton => {
            boton.addEventListener('click', async () => {
                const id = boton.getAttribute('data-id-borrar'); // Con getAttribute

                try {
                    const respuesta = await fetch('../private/action/carrito/eliminarProducto.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'idproducto=' + encodeURIComponent(id)
                    });

                    const texto = await respuesta.text();
                    let data;
                    try {
                        data = JSON.parse(texto);
                    } catch (e) {
                        alert("⚠ Error inesperado:\n\n" + texto);
                        console.log("⚠ Error inesperado:\n\n" + texto);
                        return;
                    }

                    if (data.ok) {
                        boton.closest("tr").remove();
                        setTimeout(() => {
                            location.reload();
                        }, 500);
                    } else {
                        alert("⚠ " + data.msg);
                    }
                } catch (error) {
                    alert("❌ Error de conexión:\n\n" + error);
                }
            });
        });
    });
</script>
<?php 
//! Falta cambiar de estado la compra si el carrito se vacio.
//! esta implementación borra con delete, se le puede añadir borrado logico si asi se quiere
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/Vista/estructura/footer.php"; 
?>
