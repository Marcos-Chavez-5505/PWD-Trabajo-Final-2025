<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/Vista/estructura/header.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';

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

    <div class="container my-5">
        <h1 class="mb-4 text-center">Productos Disponibles</h1>

        <div class="row">
            <?php foreach ($productos as $p): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <?php if (!empty($p['proimagen'])): ?>
                            <img src="../image/<?php echo htmlspecialchars($p['proimagen']); ?>" 
                                 class="card-img-top" alt="Imagen del producto">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/300x200.png?text=Sin+Imagen" 
                                 class="card-img-top" alt="Sin imagen">
                        <?php endif; ?>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo htmlspecialchars($p['pronombre']); ?></h5>
                            <p class="card-text text-muted flex-grow-1"><?php echo htmlspecialchars($p['prodetalle']); ?></p>
                            
                            <?php if (isset($p['proprecio'])): ?>
                                <p><strong>Precio:</strong> $<?php echo number_format($p['proprecio'], 2); ?></p>
                            <?php endif; ?>
                            <p><strong>Stock:</strong> <?php echo (int)$p['procantstock']; ?></p>

                            <a href="#" class="btn btn-compra mt-auto w-100">
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
