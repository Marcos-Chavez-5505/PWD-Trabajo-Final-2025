<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD/modelo/conector/BDautenticacion.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD/modelo/usuario.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD/modelo/rol.php';

class UsuarioRol {
    private $idUsuarioRol;
    private $idUsuario;
    private $idRol;
    private $objUsuario;
    private $objRol;
    private $objBaseDatos;

    public function __construct($objBaseDatos = null) {
        $this->idUsuarioRol = null;
        $this->idUsuario = null;
        $this->idRol = null;
        $this->objUsuario = null;
        $this->objRol = null;
        $this->objBaseDatos = $objBaseDatos ?? new BDautenticacion();
    }

    // Getters y Setters
    public function getIdUsuarioRol() { return $this->idUsuarioRol; }
    public function getIdUsuario() { return $this->idUsuario; }
    public function getIdRol() { return $this->idRol; }
    public function getObjUsuario() { return $this->objUsuario; }
    public function getObjRol() { return $this->objRol; }

    public function setIdUsuarioRol($idUsuarioRol) { $this->idUsuarioRol = $idUsuarioRol; }
    public function setIdUsuario($idUsuario) { $this->idUsuario = $idUsuario; }
    public function setIdRol($idRol) { $this->idRol = $idRol; }
    public function setObjUsuario($objUsuario) { $this->objUsuario = $objUsuario; }
    public function setObjRol($objRol) { $this->objRol = $objRol; }

    // CRUD
    public function insertar() {
        $baseDatos = new BDautenticacion();
        $resultado = false;

        if ($baseDatos->Iniciar()) {
            $sql = "INSERT INTO usuariorol (idUsuario, idRol)
                    VALUES ({$this->idUsuario}, {$this->idRol})";

            $idInsertado = $baseDatos->Ejecutar($sql);

            if ($idInsertado != -1) {
                $this->idUsuarioRol = $idInsertado;
                $resultado = true;
            }
        }
        return $resultado;
    }

    public function modificar() {
        $baseDatos = new BDautenticacion();
        $resultado = false;

        if ($baseDatos->Iniciar()) {
            $sql = "UPDATE usuariorol SET 
                        idUsuario = {$this->idUsuario}, 
                        idRol = {$this->idRol}
                    WHERE idUsuarioRol = {$this->idUsuarioRol}";
            if ($baseDatos->Ejecutar($sql) >= 0) {
                $resultado = true;
            }
        }
        return $resultado;
    }

    public function eliminar() {
        $baseDatos = new BDautenticacion();
        $resultado = false;

        if ($baseDatos->Iniciar()) {
            $sql = "DELETE FROM usuariorol WHERE idUsuarioRol = {$this->idUsuarioRol}";
            if ($baseDatos->Ejecutar($sql) >= 0) {
                $resultado = true;
            }
        }
        return $resultado;
    }

    public function buscar($idUsuarioRol) {
        $baseDatos = new BDautenticacion();
        $resultado = false;

        if ($baseDatos->Iniciar()) {
            $sql = "SELECT * FROM usuariorol WHERE idUsuarioRol = {$idUsuarioRol}";
            if ($baseDatos->Ejecutar($sql) > 0) {
                $fila = $baseDatos->Registro();

                $this->idUsuarioRol = $fila['idUsuarioRol'];
                $this->idUsuario = $fila['idUsuario'];
                $this->idRol = $fila['idRol'];

                // Vincular objetos
                $objUsuario = new Usuario();
                $objUsuario->buscar($this->idUsuario);
                $this->objUsuario = $objUsuario;

                $objRol = new Rol();
                $objRol->buscar($this->idRol);
                $this->objRol = $objRol;

                $resultado = true;
            }
        }
        return $resultado;
    }

    public function listar($condicion = "") {
        $lista = [];
        $baseDatos = new BDautenticacion();
        $sql = "SELECT * FROM usuariorol";

        if ($condicion != "") {
            $sql .= " WHERE " . $condicion;
        }
        $sql .= " ORDER BY idUsuarioRol";

        if ($baseDatos->Iniciar() && $baseDatos->Ejecutar($sql) > 0) {
            while ($fila = $baseDatos->Registro()) {
                $obj = new UsuarioRol();
                $obj->buscar($fila['idUsuarioRol']);
                $lista[] = $obj;
            }
        }
        return $lista;
    }
}
