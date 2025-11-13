<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/modelo/usuario.php';

class ControlUsuario {

    public function crearUsuario($datos) {
        $usuario = new Usuario();
        $usuario->setUsnombre($datos['usnombre']);
        $usuario->setUspass($datos['uspass']);
        $usuario->setUsmail($datos['usmail']);
        $usuario->setUsdeshabilitado(null);
        $resultado = $usuario->insertar();
        return $resultado;
    }

    public function listarUsuarios($condicion = "") {
        $usuario = new Usuario();
        $resultado = $usuario->listar($condicion);
        return $resultado;
    }

    public function buscarUsuario($idUsuario) {
        $usuario = new Usuario();
        $resultado = null;
        if ($usuario->buscar($idUsuario)) {
            $resultado = $usuario;
        }
        return $resultado;
    }

    public function modificarUsuario($datos) {
        $usuario = new Usuario();
        $resultado = false;
        if ($usuario->buscar($datos['idusuario'])) {
            $usuario->setUsnombre($datos['usnombre']);
            $usuario->setUspass($datos['uspass']);
            $usuario->setUsmail($datos['usmail']);
            $usuario->setUsdeshabilitado($datos['usdeshabilitado'] ?? null);
            $resultado = $usuario->modificar();
        }
        return $resultado;
    }

    public function eliminarUsuario($idUsuario) {
        $usuario = new Usuario();
        $resultado = false;
        if ($usuario->buscar($idUsuario)) {
            $resultado = $usuario->borradoLogico();
        }
        return $resultado;
    }

   public function autenticar($nombreUsuario, $password) {
        $usuario = new Usuario();
        $resultado = null;

        $lista = $usuario->listar("usnombre = '$nombreUsuario'");

        if (count($lista) > 0) {
            $usuario = $lista[0];
            if ($usuario->getUspass() === $password) {
                $resultado = $usuario;
            }
        }

        return $resultado;
    }

}
?>
