<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD/modelo/tp5/usuario.php';

class ControlUsuario {

    /**
    * Creamos un nuevo usuario con los datos que recibimos
    */
    public function crearUsuario($datos) {
        $usuario = new Usuario();
        $usuario->setNombreUsuario($datos['nombreUsuario']);
        $usuario->setPassword($datos['password']);
        $usuario->setNombre($datos['nombre']);
        $usuario->setApellido($datos['apellido']);
        $usuario->setEmail($datos['email']);
        $usuario->setIdRol($datos['idRol']);
        $usuario->setActivo(1);

        $resultado = $usuario->insertar();
        return $resultado;
    }

    /**
    * Nos devuelve una lista de todos los usuarios
    */
    public function listarUsuarios($condicion = "") {
        $usuario = new Usuario();
        $lista = $usuario->listar($condicion);
        return $lista;
    }

    /**
    * Buscamos a un usuario por su id
    */
    public function buscarUsuario($idUsuario) {
        $usuario = new Usuario();
        $resultado = null;

        if ($usuario->buscar($idUsuario)) {
            $resultado = $usuario;
        }

        return $resultado;
    }

    /**
    * Se modifica los datos de un usuario existente
    */
    public function modificarUsuario($datos) {
        $usuario = new Usuario();
        $resultado = false;

        if ($usuario->buscar($datos['idUsuario'])) {
            $usuario->setNombreUsuario($datos['nombreUsuario']);
            $usuario->setPassword($datos['password']);
            $usuario->setNombre($datos['nombre']);
            $usuario->setApellido($datos['apellido']);
            $usuario->setEmail($datos['email']);
            $usuario->setIdRol($datos['idRol']);
            $usuario->setActivo($datos['activo']);
            $resultado = $usuario->modificar();
        }

        return $resultado;
    }

    /**
    * Se marca a un usuario como incactivo
    */
    public function eliminarUsuario($idUsuario) {
        $usuario = new Usuario();
        $resultado = false;

        if ($usuario->buscar($idUsuario)) {
            $resultado = $usuario->borradoLogico();
        }

        return $resultado;
    }

    /**
    * Se hace la autenticacion del usuario por nombre y contraseña
    */
    public function autenticar($nombreUsuario, $password) {
        $usuario = new Usuario();
        $resultado = null;
        $lista = $usuario->listar("nombreUsuario = '$nombreUsuario' AND activo = 1");

        if (count($lista) > 0) {
            $usuario = $lista[0];
            if ($usuario->getPassword() === $password) {
                $resultado = $usuario;
            }
        }

        return $resultado;
    }
}
?>
