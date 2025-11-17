<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";

// Mostrar todos los errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// O más específico
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tienda Online</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/PWD-TP-FINAL/Vista/css/tpFinal.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    
    <a class="navbar-brand" href="/PWD-TP-FINAL/Vista/public/index.php">El Guapo Gamer</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul id="menu-dinamico" class="navbar-nav ms-auto">
          <!-- Menú generado dinamicamente -->
      </ul>
    </div>

  </div>
</nav>

<script>
$(document).ready(function() {
    $.ajax({
        url: "/PWD-TP-FINAL/Vista/estructura/menuHeader.php",
        method: "GET",
        success: function(response) {
            $("#menu-dinamico").html(response);
        },
        error: function() {
            console.error("No se pudo cargar el menú.");
        }
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
