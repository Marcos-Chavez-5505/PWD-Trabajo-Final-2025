<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/Vista/estructura/header.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/Vista/action/verificarSesionActivaCarrito.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/Vista/action/elementosCarrito.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carrito de compras</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    const BASE_URL = "/PWD-TP-FINAL/";
  </script>
  
  <script src="<?= BASE_URL ?>Vista/js/carrito.js?v=<?= time() ?>"></script>
</head>

<body>
  <main class="container py-5 min-vh-100 d-flex flex-column">
    <h2 class="text-center mb-4">🛒 Mi Carrito</h2>
    
      <?php if (!empty($flashMessage)): ?>
          <div class="alert alert-<?= htmlspecialchars($flashMessage['tipo']); ?> alert-dismissible fade show text-center shadow-sm mx-auto" style="max-width:600px;">
              <?= htmlspecialchars($flashMessage['texto']); ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
      <?php session::clearFlashMessage(); ?>
      <?php endif; ?>
      
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
                <?php foreach ($itemsCarrito as $item): ?>

                    <tr data-id-producto="<?= $item['producto']['id'] ?>" 
                        data-precio="<?= $item['producto']['precio'] ?>">

                        <td><?= htmlspecialchars($item['producto']['nombre']); ?></td>

                        <td><?= htmlspecialchars($item['producto']['detalle']); ?></td>

                        <td>
                            $<?= number_format($item['producto']['precio'], 2, ',', '.'); ?>
                        </td>

                        <td class="cantidad">
                            <?= $item['item']['cantidad']; ?>
                        </td>

                        <td class="subtotal">
                            $<?= number_format($item['subtotal'], 2, ',', '.'); ?>
                        </td>

                        <td>
                            <div class="d-flex justify-content-center align-items-center gap-1">
                                <button class="btn btn-sm btn-outline-secondary reducir-cantidad"
                                        data-id-producto="<?= $item['producto']['id']; ?>">-</button>

                                <button class="btn btn-sm btn-outline-secondary aumentar-cantidad"
                                        data-id-producto="<?= $item['producto']['id']; ?>">+</button>
                            </div>
                        </td>
                    </tr>

                <?php endforeach; ?>


                <tr class="table-secondary fw-bold">
                    <td colspan="4" class="text-end">Total:</td>
                    <td id="total-carrito">
                        $<?= number_format($total, 2, ',', '.'); ?>
                    </td>
                    <td>
                        <button id="finalizar-compra"
                                class="btn btn-success btn-sm"
                                data-usuario-id="<?= $idUsuario; ?>">
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
