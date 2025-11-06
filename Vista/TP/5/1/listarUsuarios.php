<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD/configuracion.php';

$controlUsuario = new ControlUsuario();
$listaUsuarios = $controlUsuario->listarUsuarios();
?>

<!DOCTYPE html>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD/vista/estructura/header.php'; ?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/PWD/vista/css/header-footer.css">
    <link rel="stylesheet" href="/PWD/home/fonts/css/all.min.css">
</head>
<body>
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
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Activo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($listaUsuarios as $usuario): ?>
                            <tr>
                                <td><?= $usuario->getIdUsuario(); ?></td>
                                <td><?= htmlspecialchars($usuario->getNombreUsuario()); ?></td>
                                <td><?= htmlspecialchars($usuario->getNombre()); ?></td>
                                <td><?= htmlspecialchars($usuario->getApellido()); ?></td>
                                <td><?= htmlspecialchars($usuario->getEmail()); ?></td>
                                <td><?= htmlspecialchars($usuario->getIdRol()); ?></td>
                                <td>
                                    <?php if ($usuario->getActivo() == 1): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form action="/PWD/vista/TP/5/action/actualizarLogin.php" method="get" class="d-inline">
                                        <input type="hidden" name="idUsuario" value="<?= $usuario->getIdUsuario(); ?>">
                                        <button type="submit" class="btn btn-warning btn-sm">
                                            <i class="fa-solid fa-pen-to-square"></i> Actualizar
                                        </button>
                                    </form>

                                    <form action="/PWD/vista/TP/5/action/eliminarLogin.php" method="post" class="d-inline" onsubmit="return confirmarEliminacion()">
                                        <input type="hidden" name="idUsuario" value="<?= $usuario->getIdUsuario(); ?>">
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

</body>
</html>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD/vista/estructura/footer.php'; ?>
