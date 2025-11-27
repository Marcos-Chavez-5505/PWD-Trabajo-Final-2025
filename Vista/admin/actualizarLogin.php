<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/Vista/action/accionRolesPermitidos.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/Vista/action/actualizarUsuarioLogin.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/Vista/estructura/header.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/PWD-TP-FINAL/Vista/css/tpFinal.css">
</head>
<body>

<main class="container py-5 my-5 form-actualizar-usuario">
    <div class="card p-4 shadow-sm mx-auto" style="max-width:600px;">
        <h2 class="text-center mb-4">Actualizar Usuario</h2>

        <form method="post" action="">
            <input type="hidden" name="idUsuario" value="<?= $datosVista['idusuario']; ?>">

            <div class="mb-3">
                <label for="usnombre" class="form-label">Usuario:</label>
                <input type="text" class="form-control" id="usnombre" name="usnombre"
                    value="<?= htmlspecialchars($datosVista['usnombre']); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="uspass" class="form-label">Contraseña:</label>
                <input type="text" class="form-control" id="uspass" name="uspass"
                    value="<?= htmlspecialchars($datosVista['uspass']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="usmail" class="form-label">Email:</label>
                <input type="email" class="form-control" id="usmail" name="usmail"
                    value="<?= htmlspecialchars($datosVista['usmail']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="idrol" class="form-label">Rol:</label>
                <select class="form-select" id="idrol" name="idrol" required>
                    <option value="">Seleccione un rol...</option>
                    <?php foreach ($listaRoles as $rol): ?>
                        <option value="<?= $rol->getIdRol(); ?>" <?= ($idRolActual == $rol->getIdRol()) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($rol->getDescripcionRol()); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="usdeshabilitado" name="usdeshabilitado"
                    <?= $datosVista['usdeshabilitado'] == 0 ? 'checked' : ''; ?>>
                <label class="form-check-label" for="usdeshabilitado">Activo</label>
            </div>

            <button type="submit" class="btn btn-primary w-100">Actualizar</button>
        </form>
    </div>
</main>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/Vista/estructura/footer.php'; ?>
</body>
</html>
