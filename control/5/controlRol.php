<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD/modelo/tp5/rol.php';

class ControlRol {

    public function crearRol($datos) {
        $rol = new Rol();
        $rol->setDescripcionRol($datos['descripcionRol']);
        $resultado = $rol->insertar();
        return $resultado;
    }

    public function listarRoles($condicion = "") {
        $rol = new Rol();
        $lista = $rol->listar($condicion);
        return $lista;
    }

    public function buscarRol($idRol) {
        $rol = new Rol();
        $resultado = null;

        if ($rol->buscar($idRol)) {
            $resultado = $rol;
        }

        return $resultado;
    }

    public function modificarRol($datos) {
        $rol = new Rol();
        $resultado = false;

        if ($rol->buscar($datos['idRol'])) {
            $rol->setDescripcionRol($datos['descripcionRol']);
            $resultado = $rol->modificar();
        }

        return $resultado;
    }

    public function eliminarRol($idRol) {
        $rol = new Rol();
        $resultado = false;

        if ($rol->buscar($idRol)) {
            $resultado = $rol->eliminar();
        }

        return $resultado;
    }
}
?>
