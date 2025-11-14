
<?php
//!!!!!!!!!!!IMPORTANTE!!!!!!!!!!!
//! La funcion MostrarProductosCarrito() todavia no se realiza
//! Pero la idea es que al añadir un producto al carrito de compras se cree un registro
//! en la tabla compra y uno en la tabla compraEstado con el estado "iniciada"

include_once $_SERVER['DOCUMENT_ROOT'] . "/Vista/estructura/header.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/configuracion.php";

$session = new Session();

$usuarioActivo = $session->activa();
$nombreUsuario = $usuarioActivo ? $session->getUsuario() : null;//si hay un usuario logueado, se guardara el nombre
$rolUsuario = $usuarioActivo ? $session->getRol() : null;

if ($usuarioActivo && ($rolUsuario === "admin" || $rolUsuario === "cliente")){
    //!MostrarProductosCarrito()
}

include_once $_SERVER['DOCUMENT_ROOT'] . "/Vista/estructura/footer.php";
?>

