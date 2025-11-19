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
$controlCarrito = new ControlCarrito();
$itemsCarrito = $controlCarrito->obtenerItemsDelCarrito($idUsuario);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carrito de compras</title>
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

  <script>
    const BASE_URL = "/PWD-TP-FINAL/";
  </script>

  <script src="<?= BASE_URL ?>Vista/js/carrito.js"></script>
</head>

<body>
<main class="container py-5 min-vh-100 d-flex flex-column">
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
                <button class="btn btn-sm btn-outline-secondary reducir-cantidad" data-id-producto="<?= $producto->getIdproducto(); ?>">-</button>
                <button class="btn btn-sm btn-outline-secondary aumentar-cantidad" data-id-producto="<?= $producto->getIdproducto(); ?>">+</button>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>

          <tr class="table-secondary fw-bold">
            <td colspan="4" class="text-end">Total:</td>
            <td id="total-carrito">$<?= number_format($total, 2, ',', '.'); ?></td>
            <td>
              <button id="finalizar-compra" 
                      class="btn btn-success btn-sm" 
                      data-usuario-id="<?php echo $idUsuario; ?>">
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
</body>
</html>
