<?php
class Session {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function iniciarSesion($usuario) {
        $_SESSION['idusuario'] = $usuario->getIdusuario();
        $_SESSION['usnombre'] = $usuario->getUsnombre();
        $_SESSION['activa'] = true;
        return true;
    }

    public function validar() {
        $resultado = false;

        if (isset($_SESSION['idusuario'])) {

            include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/modelo/usuario.php';
            $user = new Usuario();

            if ($user->buscar($_SESSION['idusuario'])) {
                if ($user->getUsdeshabilitado() === null) {
                    $resultado = true;
                }
            }
        }

        return $resultado;
    }

    public function activa() {
        $resultado = false;

        if (isset($_SESSION['activa']) && $_SESSION['activa'] === true) {
            if ($this->validar()) {
                $resultado = true;
            }
        }

        return $resultado;
    }

    public function getUsuario() {
        $usuario = null;

        if (isset($_SESSION['usnombre'])) {
            $usuario = $_SESSION['usnombre'];
        }

        return $usuario;
    }

    public function getIdUsuario() {
        $id = null;

        if (isset($_SESSION['idusuario'])) {
            $id = $_SESSION['idusuario'];
        }

        return $id;
    }

    public function getRol() {
        $resultado = null;

        if ($this->validar()) {

            include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/modelo/usuarioRol.php';
            include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/modelo/rol.php';

            $idUsuario = $_SESSION['idusuario'];
            $usuarioRol = new UsuarioRol();
            $listaRoles = $usuarioRol->listar("idusuario = {$idUsuario}");

            if (count($listaRoles) > 0) {
                $objUsuarioRol = $listaRoles[0];
                $rol = $objUsuarioRol->getObjRol();
                $resultado = $rol->getDescripcionRol();  // "admin" | "cliente"
            }
        }

        return $resultado;
    }

    public function cerrar() {
        session_unset();
        session_destroy();
        $_SESSION = [];
        return true;
    }
}
?>
