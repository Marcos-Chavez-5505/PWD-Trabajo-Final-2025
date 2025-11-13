<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/Vista/estructura/header.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';

$session = new Session();
$usuario = $session->activa() ? $session->getUsuario() : null;

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
        <?php if ($usuario): ?>
            <div class="dropdown">
                <a class="btn btn-outline-dark dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($usuario); ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
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
                <div class="card h-100 shadow-sm">
                    <?php if (!empty($p['proimagen'])): ?>
                        <img src="../image/<?php echo htmlspecialchars($p['proimagen']); ?>" class="card-img-top" alt="Imagen del producto">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/300x200.png?text=Sin+Imagen" class="card-img-top" alt="Sin imagen">
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo htmlspecialchars($p['pronombre']); ?></h5>
                        <p class="card-text text-muted flex-grow-1"><?php echo htmlspecialchars($p['prodetalle']); ?></p>
                        <p><strong>Precio:</strong> $<?php echo number_format($p['proprecio'], 2); ?></p>
                        <p><strong>Stock:</strong> <?php echo (int)$p['procantstock']; ?></p>
                        <a href="#" class="btn btn-compra mt-auto w-100" style="background-color: #ee370a;">
                            <i class="bi bi-cart-fill"></i> Agregar al carrito
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/Vista/estructura/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
