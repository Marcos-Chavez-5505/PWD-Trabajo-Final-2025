<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD/modelo/tp5/usuariorol.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD/modelo/tp5/usuario.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD/modelo/tp5/rol.php';

class ControlUsuarioRol {

    public function asignarRolAUsuario($datos) {
        $usuarioRol = new UsuarioRol();
        $usuarioRol->setIdUsuario($datos['idUsuario']);
        $usuarioRol->setIdRol($datos['idRol']);
        $resultado = $usuarioRol->insertar();
        return $resultado;
    }

    public function listarUsuarioRol($condicion = "") {
        $usuarioRol = new UsuarioRol();
        $lista = $usuarioRol->listar($condicion);
        return $lista;
    }

    public function buscarUsuarioRol($idUsuarioRol) {
        $usuarioRol = new UsuarioRol();
        $resultado = null;

        if ($usuarioRol->buscar($idUsuarioRol)) {
            $resultado = $usuarioRol;
        }

        return $resultado;
    }

    public function eliminarUsuarioRol($idUsuarioRol) {
        $usuarioRol = new UsuarioRol();
        $resultado = false;

        if ($usuarioRol->buscar($idUsuarioRol)) {
            $resultado = $usuarioRol->eliminar();
        }

        return $resultado;
    }

    public function listarRolesDeUsuario($idUsuario) {
        $usuarioRol = new UsuarioRol();
        $condicion = "idUsuario = " . intval($idUsuario);
        $lista = $usuarioRol->listar($condicion);
        return $lista;
    }

    public function listarUsuariosPorRol($idRol) {
        $usuarioRol = new UsuarioRol();
        $condicion = "idRol = " . intval($idRol);
        $lista = $usuarioRol->listar($condicion);
        return $lista;
    }
}
?>
