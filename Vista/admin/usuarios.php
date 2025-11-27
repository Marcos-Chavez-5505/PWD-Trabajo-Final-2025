<?php
require_once $_SERVER['DOCUMENT_ROOT']."/PWD-TP-FINAL/Vista/action/accionRolesPermitidos.php";
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/Vista/action/listarUsuarios.php";
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/Vista/estructura/header.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/PWD-TP-FINAL/home/fonts/css/all.min.css">
</head>
<body>

<?php if (isset($_GET['exito']) && $_GET['exito'] === 'Usuario actualizado correctamente'): ?>
    <div class="alert alert-success text-center">
        Usuario actualizado correctamente.
    </div>

<?php elseif (isset($_GET['error']) && $_GET['error'] === 'No se pudo actualizar el usuario'): ?>
    <div class="alert alert-warning text-center">
        No se pudo actualizar el usuario.
    </div>

<?php endif; ?>

<main class="container py-5 my-5">
    <div class="card p-4 shadow-sm">
        <h2 class="text-center mb-4">Usuarios Registrados</h2>

        <?php if (count($listaUsuarios) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th>Activo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($listaUsuarios as $usuario): ?>
                            <tr>
                                <td><?= $usuario->getIdusuario(); ?></td>
                                <td><?= htmlspecialchars($usuario->getUsnombre()); ?></td>
                                <td><?= htmlspecialchars($usuario->getUsmail()); ?></td>
                                <td>
                                    <?php if ($usuario->getUsdeshabilitado() == 0): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form action="/PWD-TP-FINAL/Vista/admin/actualizarLogin.php" method="get" class="d-inline">
                                        <input type="hidden" name="idUsuario" value="<?= $usuario->getIdusuario(); ?>">
                                        <button type="submit" class="btn btn-warning btn-sm">
                                            <i class="fa-solid fa-pen-to-square"></i> Actualizar
                                        </button>
                                    </form>

                                    <form action="/PWD-TP-FINAL/Vista/action/eliminarLogin.php" method="post" class="d-inline" onsubmit="return confirmarEliminacion()">
                                        <input type="hidden" name="idUsuario" value="<?= $usuario->getIdusuario(); ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fa-solid fa-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">No hay usuarios registrados.</div>
        <?php endif; ?>
    </div>
</main>

<script>
function confirmarEliminacion() {
    return confirm('¿Seguro que quieres eliminar este usuario? (borrado lógico)');
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/Vista/estructura/footer.php'; ?>
