<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/vista/estructura/header.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';

$session = new Session();

if (!$session->activa() || !$session->validar()) {
    header('Location: /PWD-TP-FINAL/vista/public/login.php');
    exit();
}

$usuarioNombre = $session->getUsuario();
?>

<div class="container mt-5">
    <h1 class="text-center">Mi Cuenta</h1>
    <p class="text-center">Bienvenido <?php echo htmlspecialchars($usuarioNombre); ?></p>

    <div class="text-center mt-4">
        <a href="/PWD-TP-FINAL/vista/cliente/miCuenta.php" class="btn btn-primary m-2">Editar Datos</a>
        <a href="/PWD-TP-FINAL/vista/cliente/compras.php" class="btn btn-primary m-2">Ver Mis Compras</a>
    </div>
</div>



<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/vista/estructura/footer.php'; ?>