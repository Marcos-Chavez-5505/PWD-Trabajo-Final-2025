<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: /PWD/vista/login.php?error=Debes iniciar sesión");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página Segura</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/PWD/vista/css/tp5.css">
</head>
<body>
    <div class="tarjeta-pagina-segura">
        <h1>Bienvenido, <?= htmlspecialchars($_SESSION['usuario']) ?></h1>
        <p>Esta es una página segura, solo accesible para usuarios autenticados.</p>
    </div>
</body>
</html>
