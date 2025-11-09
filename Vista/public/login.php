<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/vista/estructura/header.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/control/5/controlUsuario.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/control/session.php';

//tengo que cambiar los nombres de la bd usuario por que los nombre sson diferentes ahora :´v

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreUsuario = $_POST['nombreUsuario'];
    $password = $_POST['password'];

    $control = new ControlUsuario();
    $usuario = $control->autenticar($nombreUsuario, $password);

    if ($usuario) {
        $session = new Session();
        $session->iniciar($usuario->getNombreUsuario(), $usuario->getPassword());

        header('Location: /PWD-TP-FINAL/vista/privada/inicio.php');
        exit();
    } else {
        $mensaje = 'Usuario o contraseña incorrectos';
    }
}
?>

<h2 class="text-center mb-4">Iniciar Sesión</h2>

<div class="row justify-content-center">
    <div class="col-md-6">
        <?php if ($mensaje): ?>
            <div class="alert alert-danger"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="nombreUsuario" class="form-label">Nombre de usuario</label>
                <input type="text" class="form-control" name="nombreUsuario" id="nombreUsuario" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>
            <button type="submit" class="btn btn-dark w-100">Ingresar</button>
        </form>
    </div>
</div>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/vista/estructura/footer.php'; ?>
