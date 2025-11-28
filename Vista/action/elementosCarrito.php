<?php
$session = new Session();
$idUsuario = $session->getIdUsuario();

$controlCarrito = new ControlCarrito();

$data = $controlCarrito->obtenerCarritoUsuario($idUsuario);

$itemsCarrito = $data['items'];
$total = $data['total'];

$flashMessage = session::getFlashMessage();
?>