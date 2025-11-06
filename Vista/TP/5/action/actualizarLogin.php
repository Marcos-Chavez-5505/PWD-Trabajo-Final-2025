<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD/configuracion.php';

$control = new ControlUsuario();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['idUsuario'])) {
        header("Location: /PWD/vista/TP/5/2/listarUsuario.php");
        exit();
    }

    $idUsuario = intval($_GET['idUsuario']);
    $usuario = $control->buscarUsuario($idUsuario);

    if (!$usuario) {
        header("Location: /PWD/vista/TP/5/2/listarUsuario.php?error=Usuario no encontrado");
        exit();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idUsuario = intval($_POST['idUsuario']);
    $datos = [
        'idUsuario' => $idUsuario,
        'nombreUsuario' => trim($_POST['nombreUsuario']),
        'password' => trim($_POST['password']),
        'nombre' => trim($_POST['nombre']),
        'apellido' => trim($_POST['apellido']),
        'email' => trim($_POST['email']),
        'idRol' => isset($_POST['idRol']) ? intval($_POST['idRol']) : 1, // 👈 agregado invisible por seguridad
        'activo' => isset($_POST['activo']) ? 1 : 0
    ];

    if ($control->modificarUsuario($datos)) {
        header("Location: /PWD/vista/TP/5/1/listarUsuarios.php?exito=Usuario actualizado correctamente");
    } else {
        header("Location: /PWD/vista/TP/5/1/listarUsuarios.php?error=No se pudo actualizar el usuario");
    }
    exit();
}
?>

<!DOCTYPE html>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD/vista/estructura/header.php'; ?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/PWD/vista/css/tp5.css">
</head>
<body>
<main class="container py-5 my-5 form-actualizar-usuario">
    <div class="card p-4 shadow-sm mx-auto">
        <h2 class="text-center mb-4">Actualizar Usuario</h2>

        <form method="post" action="">
            <input type="hidden" name="idUsuario" value="<?= $usuario->getIdUsuario(); ?>">
            <input type="hidden" name="idRol" value="<?= $usuario->getIdRol(); ?>"> <!-- 👈 invisible pero enviado -->

            <div class="mb-3">
                <label for="nombreUsuario" class="form-label">Usuario:</label>
                <input type="text" class="form-control" id="nombreUsuario" name="nombreUsuario"
                    value="<?= htmlspecialchars($usuario->getNombreUsuario()); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="text" class="form-control" id="password" name="password"
                    value="<?= htmlspecialchars($usuario->getPassword()); ?>" required>
            </div>

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre"
                    value="<?= htmlspecialchars($usuario->getNombre()); ?>" required>
            </div>

            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido:</label>
                <input type="text" class="form-control" id="apellido" name="apellido"
                    value="<?= htmlspecialchars($usuario->getApellido()); ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="<?= htmlspecialchars($usuario->getEmail()); ?>" required>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="activo" name="activo"
                    <?= $usuario->getActivo() == 1 ? 'checked' : ''; ?>>
                <label class="form-check-label" for="activo">Activo</label>
            </div>

            <button type="submit" class="btn btn-primary w-100">Actualizar</button>
        </form>
    </div>
</main>
</body>
</html>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD/vista/estructura/footer.php'; ?>
