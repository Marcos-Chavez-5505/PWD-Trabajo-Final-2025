<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/vista/estructura/header.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';

$session = new Session();

if (!$session->activa() || !$session->validar()) {
    header('Location: /PWD-TP-FINAL/vista/public/perfil.php');
    exit();
}

$usuarioNombre = $session->getUsuario();
$usuarioRolRaw = $session->getRol();
$usuarioRol = trim(strtolower((string)$usuarioRolRaw));

$currentScript = basename($_SERVER['SCRIPT_NAME']);

if ($usuarioRol === 'cliente' && $currentScript !== 'cliente.php') {
    header('Location: /PWD-TP-FINAL/vista/public/index.php');
    exit();
}
?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Bienvenido, <?php echo htmlspecialchars($usuarioNombre); ?> 👋</h1>

    <?php if ($usuarioRol === 'administrador'): ?>
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
    <?php endif; ?>

    <div class="text-center mt-5">
        <a href="/PWD-TP-FINAL/control/logout.php" class="btn btn-danger">Cerrar Sesión</a>
    </div>
</div>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/vista/estructura/footer.php'; ?>
