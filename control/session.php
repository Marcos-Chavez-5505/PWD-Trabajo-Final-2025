<?php
class session {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function iniciar($idusuario, $usnombre, $uspass) {
        $_SESSION['idusuario'] = $idusuario;
        $_SESSION['usnombre'] = $usnombre;
        $_SESSION['uspass'] = $uspass;
        $_SESSION['activa'] = true;
    }

    public function validar() {
        $valido = false;

        if (isset($_SESSION['usnombre']) && isset($_SESSION['uspass'])) {
            include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/modelo/usuario.php';
            $usuario = new Usuario();
            $lista = $usuario->listar("usnombre = '{$_SESSION['usnombre']}'");

            if (count($lista) > 0) {
                $objUsuario = $lista[0];
                if ($objUsuario->getUspass() === $_SESSION['uspass']) {
                    $valido = true;
                }
            }
        }

        return $valido;
    }

    public function activa() {
        $activa = false;
        if (isset($_SESSION['activa']) && $_SESSION['activa'] === true) {
            $activa = true;
        }
        return $activa;
    }

    public function getUsuario() {
        $usuario = null;
        if (isset($_SESSION['usnombre'])) {
            $usuario = $_SESSION['usnombre'];
        }
        return $usuario;
    }

    public function getRol() {
        $rol = null;

        if ($this->validar()) {
            include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/modelo/usuario.php';
            include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/modelo/usuarioRol.php';
            include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/modelo/rol.php';

            $usuario = new Usuario();
            $listaUsuarios = $usuario->listar("usnombre = '{$_SESSION['usnombre']}'");

            if (count($listaUsuarios) > 0) {
                $objUsuario = $listaUsuarios[0];
                $idUsuario = $objUsuario->getIdusuario();

                $usuarioRol = new UsuarioRol();
                $listaRoles = $usuarioRol->listar("idUsuario = {$idUsuario}");

                if (count($listaRoles) > 0) {
                    $objUsuarioRol = $listaRoles[0];
                    $objRol = $objUsuarioRol->getObjRol();
                    $rol = $objRol->getDescripcionRol();
                }
            }
        }

        return $rol;
    }



    public function getIdUsuario() {
        $id = null;
        if (isset($_SESSION['idusuario'])) {
            $id = $_SESSION['idusuario'];
        }
        return $id;
    }

    public function cerrar() {
        session_unset();
        session_destroy();
        $_SESSION = [];
    }
}
?>
