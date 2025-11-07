<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD/vista/estructura/header.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD/modelo/conector/bdCarritoCompras.php';

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

<h1 class="mb-4 text-center">Productos Disponibles</h1>

<div class="row">
<?php foreach ($productos as $p): ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            <?php if (!empty($p['proimagen'])): ?>
                <img src="../image/<?php echo htmlspecialchars($p['proimagen']); ?>" 
                    class="card-img-top" alt="Imagen del producto">
            <?php else: ?>
                <img src="https://via.placeholder.com/300x200.png?text=Sin+Imagen" class="card-img-top" alt="Sin imagen">
            <?php endif; ?>

            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($p['pronombre']); ?></h5>
                <p class="card-text"><?php echo htmlspecialchars($p['prodetalle']); ?></p>
                <?php if (isset($p['proprecio'])): ?>
                    <p><strong>Precio:</strong> $<?php echo number_format($p['proprecio'], 2); ?></p>
                <?php endif; ?>
                <p><strong>Stock:</strong> <?php echo $p['procantstock']; ?></p>
                <a href="#" class="btn btn-compra w-100">
                    <i class="bi bi-cart-fill"></i> Agregar al carrito
                </a>

            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD/vista/estructura/footer.php'; ?>
