<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tienda Online</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/PWD-TP-FINAL/Vista/css/tpFinal.css">

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="/PWD-TP-FINAL/Vista/public/index.php">El Guapo Gamer</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="/PWD-TP-FINAL/Vista/public/index.php">Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="/PWD-TP-FINAL/Vista/public/contacto.php">Contacto</a></li>
        <li class="nav-item"><a class="nav-link" href="/PWD-TP-FINAL/Vista/public/login.php">Login</a></li>
      </ul>
    </div>
  </div>
  <div class="dropdown-center">
    <button class="btn btn-secondary dropdown-toggle" 
            type="button" 
            data-bs-toggle="dropdown">
        <nav class="navbar-toggler-icon"></nav>
    </button>
    
    <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="#">Opción 1</a></li>
        <li><a class="dropdown-item" href="#">Opción 2</a></li>
        <li><a class="dropdown-item" href="#">Opción 3</a></li>
    </ul>
</div>
</nav>

<div class="container mt-4">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

