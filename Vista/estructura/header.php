<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/Vista/action/inicioLinkHeader.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tienda Online</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/PWD-TP-FINAL/Vista/css/tpFinal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    
    <a class="navbar-brand" href="<?= $inicioLink ?>">El Guapo Gamer</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul id="menu-dinamico" class="navbar-nav ms-auto">
      </ul>

      <div class="ms-3">
        <?php if ($usuarioActivo): ?>
            <div class="dropdown">
                <a class="btn btn-outline-light dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i> <?= htmlspecialchars($nombreUsuario); ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <?php if ($rolUsuario === "Administrador"): ?>
                        <li><a class="dropdown-item" href="/PWD-TP-FINAL/Vista/public/cuenta.php">Mi Cuenta</a></li>
                        <li><hr class="dropdown-divider"></li>
                    <?php else: ?>
                        <li><a class="dropdown-item" href="/PWD-TP-FINAL/Vista/public/cuenta.php">Mi Cuenta</a></li>
                    <?php endif; ?>
                    <li><a class="dropdown-item" href="/PWD-TP-FINAL/Vista/action/logout.php">Cerrar sesión</a></li>
                </ul>
            </div>
        <?php else: ?>
            <a href="/PWD-TP-FINAL/Vista/public/cuenta.php" class="btn btn-outline-primary">
                <i class="bi bi-box-arrow-in-right"></i> Iniciar sesión
            </a>
        <?php endif; ?>
      </div>

    </div>
  </div>
</nav>

<script>
  // Definición de la función que actualiza el contador
  async function actualizarContadorCarrito() {
    try {
      const res = await fetch('/PWD-TP-FINAL/Vista/action/cantidadCarrito.php');
      const data = await res.json();
      const badge = document.getElementById('contador-carrito');
      console.log("Badge encontrado:", badge);


      if (data.ok && badge) {
        if (data.cantidad > 0) {
          badge.textContent = data.cantidad;
          badge.style.display = 'inline';
        } else {
          badge.style.display = 'none';
        }
      }
    } catch (e) {
      console.error("Error al obtener cantidad del carrito:", e);
    }
  }
  window.actualizarContadorCarrito = actualizarContadorCarrito;


  // Carga del menú dinámico y actualización del contador cuando termine
  $(document).ready(function() {
    $.ajax({
      url: "/PWD-TP-FINAL/Vista/action/menuHeader.php",
      method: "GET",
      success: function(response) {
        $("#menu-dinamico").html(response);
        actualizarContadorCarrito(); 
        document.dispatchEvent(new CustomEvent('menuListo'));
      },
      error: function() {
        console.error("No se pudo cargar el menú.");
      }
    });
  });
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/PWD-TP-FINAL/Vista/js/agregarproducto.js"></script>
</body>
</html>
