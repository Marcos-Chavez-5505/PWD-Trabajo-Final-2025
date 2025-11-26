<?php
$session = new Session();
$iniciarSesion = true;

if (!$session->activa()) {
    echo
    "<main class='container py-5 min-vh-100 d-flex flex-column'>
      <div class='alert alert-warning text-center mt-5'>Debes iniciar sesión para ver tu carrito.</div>
    </main>";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/Vista/estructura/footer.php";
    exit;
}
$idUsuario = $session->getIdUsuario();
$controlCarrito = new ControlCarrito();
$itemsCarrito = $controlCarrito->obtenerItemsSinEstado($idUsuario);
?>