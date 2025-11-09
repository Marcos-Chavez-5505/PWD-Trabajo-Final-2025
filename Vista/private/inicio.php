<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/vista/estructura/header.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';

$session = new Session();

if (!$session->activa() || !$session->validar()) {
    header('Location: /PWD-TP-FINAL/vista/publica/login.php');
    exit();
}

$usuarioNombre = $session->getUsuario();
$usuarioRol = $session->getRol(); // 1 = Admin, 2 = Cliente
?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Bienvenido, <?php echo htmlspecialchars($usuarioNombre); ?> 👋</h1>

    <?php if ($usuarioRol == 1): ?>
        <div class="alert alert-primary text-center">
            <strong>Rol:</strong> Administrador
        </div>

        <div class="row text-center mt-4">
            <div class="col-md-3">
                <a href="/PWD-TP-FINAL/vista/admin/usuarios.php" class="btn btn-dark w-100 mb-3">Gestionar Usuarios</a>
            </div>
            <div class="col-md-3">
                <a href="/PWD-TP-FINAL/vista/admin/roles.php" class="btn btn-dark w-100 mb-3">Gestionar Roles</a>
            </div>
            <div class="col-md-3">
                <a href="/PWD-TP-FINAL/vista/admin/menu.php" class="btn btn-dark w-100 mb-3">Gestionar Menú</a>
            </div>
            <div class="col-md-3">
                <a href="/PWD-TP-FINAL/vista/admin/productos.php" class="btn btn-dark w-100 mb-3">Gestionar Productos</a>
            </div>
        </div>

    <?php elseif ($usuarioRol == 2): ?>
        <div class="alert alert-success text-center">
            <strong>Rol:</strong> Cliente
        </div>

        <div class="text-center mt-4">
            <a href="/PWD-TP-FINAL/vista/cliente/miCuenta.php" class="btn btn-outline-primary mb-3">Mi Cuenta</a>
            <a href="/PWD-TP-FINAL/vista/cliente/compras.php" class="btn btn-outline-primary mb-3">Mis Compras</a>
            <a href="/PWD-TP-FINAL/vista/publica/productos.php" class="btn btn-outline-primary mb-3">Ver Productos</a>
        </div>
    <?php endif; ?>

    <div class="text-center mt-5">
        <a href="/PWD-TP-FINAL/control/logout.php" class="btn btn-danger">Cerrar Sesión</a>
    </div>
</div>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/vista/estructura/footer.php'; ?>
