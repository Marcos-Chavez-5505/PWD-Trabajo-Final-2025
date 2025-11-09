<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/vista/estructura/header.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $nombreUsuario = $_POST['nombreUsuario'];
    $password = $_POST['password'];

    $control = new ControlUsuario();
    $usuario = $control->autenticar($nombreUsuario, $password);

    if ($usuario) {
        $session = new Session();
        $session->iniciar(
            $usuario->getIdusuario(),
            $usuario->getUsnombre(),
            $usuario->getUspass()
        );
        header('Location: /PWD-TP-FINAL/vista/private/inicio.php');
        exit();
    } else {
        $mensaje = 'Usuario o contraseña incorrectos';
    }
}
?>

<h2 class="text-center mb-4">Iniciar Sesión</h2>

<div class="row justify-content-center">
    <div class="col-md-6">
        <form method="POST">
            <div class="mb-3">
                <label for="nombreUsuario" class="form-label">Nombre de usuario</label>
                <input type="text" class="form-control" name="nombreUsuario" id="nombreUsuario" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>
            <?php if ($mensaje): ?>
                <div class="alert alert-danger"><?php echo $mensaje; ?></div>
            <?php endif; ?>
            <button type="submit" class="btn btn-dark w-100">Ingresar</button>
        </form>
    </div>
</div>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/vista/estructura/footer.php'; ?>
