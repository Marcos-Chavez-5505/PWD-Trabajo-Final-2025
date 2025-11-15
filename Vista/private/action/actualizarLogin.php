<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';

$control = new ControlUsuario();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['idUsuario'])) {
        header("Location: /PWD-TP-FINAL/vista/private/listarUsuarios.php");
        exit();
    }

    $idUsuario = intval($_GET['idUsuario']);
    $usuario = $control->buscarUsuario($idUsuario);

    if (!$usuario) {
        header("Location: /PWD-TP-FINAL/vista/private/listarUsuarios.php?error=Usuario no encontrado");
        exit();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idUsuario = intval($_POST['idUsuario']);
    $datos = [
        'idusuario' => $idUsuario,
        'usnombre' => trim($_POST['usnombre']),
        'uspass' => trim($_POST['uspass']),
        'usmail' => trim($_POST['usmail']),
        'usdeshabilitado' => isset($_POST['usdeshabilitado']) ? 1 : 0
    ];

    if ($control->modificarUsuario($datos)) {
        header("Location: /PWD-TP-FINAL/vista/private/listarUsuarios.php?exito=Usuario actualizado correctamente");
    } else {
        header("Location: /PWD-TP-FINAL/vista/private/listarUsuarios.php?error=No se pudo actualizar el usuario");
    }
    exit();
}
?>

<!DOCTYPE html>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/Vista/estructura/header.php'; ?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/PWD-TP-FINAL/vista/css/tpFinal.css">
</head>
<body>
<main class="container py-5 my-5 form-actualizar-usuario">
    <div class="card p-4 shadow-sm mx-auto">
        <h2 class="text-center mb-4">Actualizar Usuario</h2>

        <form method="post" action="">
            <input type="hidden" name="idUsuario" value="<?= $usuario->getIdusuario(); ?>">

            <div class="mb-3">
                <label for="usnombre" class="form-label">Usuario:</label>
                <input type="text" class="form-control" id="usnombre" name="usnombre"
                    value="<?= htmlspecialchars($usuario->getUsnombre()); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="uspass" class="form-label">Contraseña:</label>
                <input type="text" class="form-control" id="uspass" name="uspass"
                    value="<?= htmlspecialchars($usuario->getUspass()); ?>" required>
            </div>

            <div class="mb-3">
                <label for="usmail" class="form-label">Email:</label>
                <input type="email" class="form-control" id="usmail" name="usmail"
                    value="<?= htmlspecialchars($usuario->getUsmail()); ?>" required>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="usdeshabilitado" name="usdeshabilitado"
                    <?= $usuario->getUsdeshabilitado() == 0 ? 'checked' : ''; ?>>
                <label class="form-check-label" for="usdeshabilitado">Activo</label>
            </div>

            <button type="submit" class="btn btn-primary w-100">Actualizar</button>
        </form>
    </div>
</main>
</body>
</html>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/Vista/estructura/footer.php'; ?>
