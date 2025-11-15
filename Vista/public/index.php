<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/Vista/estructura/header.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';

$session = new Session();

//con esta seccion obtenemos la informacion del usuario desde la session
$usuarioActivo = $session->activa();
$nombreUsuario = $usuarioActivo ? $session->getUsuario() : null;//si hay un usuario logueado, se guardara el nombre
$rolUsuario = $usuarioActivo ? $session->getRol() : null; // si hay seecion activa, se obtiene el rol (admin / cliente)

$bd = new bdCarritoCompras();
$productos = [];

if ($bd->Iniciar()) {
    $sql = "SELECT * FROM producto";
    if ($bd->Ejecutar($sql) > 0) {
        while ($fila = $bd->Registro()) {
            $productos[] = $fila;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos Disponibles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/PWD-TP-FINAL/Vista/css/tpFinal.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light px-4">
    <div class="ms-auto">
        <?php if ($usuarioActivo): ?>
            <div class="dropdown">
                <a class="btn btn-outline-dark dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i> <?= htmlspecialchars($nombreUsuario); ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <?php if ($rolUsuario === "admin"): ?>
                        <li><a class="dropdown-item" href="/PWD-TP-FINAL/Vista/admin/panelAdmin.php">Panel Admin</a></li>
                        <li><hr class="dropdown-divider"></li>
                    <?php endif; ?>

                    <li><a class="dropdown-item" href="/PWD-TP-FINAL/Vista/public/perfil.php">Editar cuenta</a></li>
                    <li><a class="dropdown-item" href="/PWD-TP-FINAL/Vista/private/logout.php">Cerrar sesión</a></li>
                </ul>
            </div>
        <?php else: ?>
            <a href="/PWD-TP-FINAL/Vista/public/perfil.php" class="btn btn-outline-primary">
                <i class="bi bi-box-arrow-in-right"></i> Iniciar sesión
            </a>
        <?php endif; ?>
    </div>
</nav>

<div class="container my-5">
    <h1 class="mb-4 text-center">Productos Disponibles</h1>
    <div class="row">
        <?php foreach ($productos as $p): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm product-card">
                    <?php if (!empty($p['proimagen'])): ?>
                        <img src="../image/<?= htmlspecialchars($p['proimagen']); ?>" 
                             class="card-img-top" alt="Imagen del producto">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/300x200.png?text=Sin+Imagen" 
                             class="card-img-top" alt="Sin imagen">
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title product-title"><?= htmlspecialchars($p['pronombre']); ?></h5>

                        <p class="card-text text-muted flex-grow-1 product-description">
                            <?= htmlspecialchars($p['prodetalle']); ?>
                        </p>

                        <p class="product-price"><strong>Precio:</strong> $<?= number_format($p['proprecio'], 2); ?></p>
                        <p class="product-stock"><strong>Stock:</strong> <?= (int)$p['procantstock']; ?></p>

                        <?php if ($usuarioActivo): ?>
                            <button class="btn btn-compra mt-auto w-100 agregar-carrito" data-id="<?= $p['idproducto']; ?>">
                                <i class="bi bi-cart-fill"></i> Agregar al carrito
                            </button>
                        <?php else: ?>
                            <a class="btn btn-secondary mt-auto w-100 disabled">
                                Inicia sesión para comprar
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/Vista/estructura/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const BASE_URL = "<?= BASE_URL ?>";
</script>
<script src="/PWD-TP-FINAL/Vista/js/agregarProducto.js"></script>



</body>
</html>
