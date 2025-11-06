<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD/vista/estructura/header.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/PWD/vista/css/header-footer.css">
    <link rel="stylesheet" href="/PWD/home/fonts/css/all.min.css">
</head>
<body>
<main class="container py-5 my-5 d-flex justify-content-center">
    <div class="card p-4 shadow-sm w-100" style="max-width: 400px;">
        <h3 class="text-center mb-4">Iniciar Sesión</h3>

        <!-- FORMULARIO -->
        <form id="loginForm" action="/PWD/vista/TP/5/2/verificarLogin.php" method="post" novalidate>
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario:</label>
                <input type="text" name="nombreUsuario" id="usuario" class="form-control" required>
                <div class="invalid-feedback">El usuario es obligatorio.</div>
            </div>

            <div class="mb-3">
                <label for="pass" class="form-label">Contraseña:</label>
                <input type="password" name="password" id="pass" class="form-control" required>
                <div class="invalid-feedback">La contraseña es obligatoria.</div>
            </div>

            <?php
            if (isset($_GET['error'])) {
                echo '<div class="alert alert-danger mt-3 text-center">' . htmlspecialchars($_GET['error']) . '</div>';
            }
            ?>
            
            <button type="submit" class="btn btn-primary w-100">Ingresar</button>
        </form>

    </div>
</main>

<!-- Vinculamos el validador -->
<script src="/PWD/vista/js/validator.js"></script>
</body>
</html>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD/vista/estructura/footer.php'; ?>
