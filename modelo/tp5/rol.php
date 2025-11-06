<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD/modelo/conector/BDautenticacion.php';

class Rol {
    private $idRol;
    private $descripcionRol;
    private $objBaseDatos;

    public function __construct($objBaseDatos = null) {
        $this->idRol = null;
        $this->descripcionRol = "";
        $this->objBaseDatos = $objBaseDatos ?? new BDautenticacion();
    }

    // Getters y Setters
    public function getIdRol() { return $this->idRol; }
    public function getDescripcionRol() { return $this->descripcionRol; }

    public function setIdRol($idRol) { $this->idRol = $idRol; }
    public function setDescripcionRol($descripcionRol) { $this->descripcionRol = $descripcionRol; }

    // CRUD
    public function insertar() {
        $baseDatos = new BDautenticacion();
        $resultado = false;

        if ($baseDatos->Iniciar()) {
            $sql = "INSERT INTO rol (descripcionRol)
                    VALUES ('{$this->descripcionRol}')";
            $idInsertado = $baseDatos->Ejecutar($sql);

            if ($idInsertado != -1) {
                $this->idRol = $idInsertado;
                $resultado = true;
            }
        }
        return $resultado;
    }

    public function modificar() {
        $baseDatos = new BDautenticacion();
        $resultado = false;

        if ($baseDatos->Iniciar()) {
            $sql = "UPDATE rol SET descripcionRol = '{$this->descripcionRol}'
                    WHERE idRol = {$this->idRol}";
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
            $sql = "DELETE FROM rol WHERE idRol = {$this->idRol}";
            if ($baseDatos->Ejecutar($sql) >= 0) {
                $resultado = true;
            }
        }
        return $resultado;
    }

    public function buscar($idRol) {
        $baseDatos = new BDautenticacion();
        $resultado = false;

        if ($baseDatos->Iniciar()) {
            $sql = "SELECT * FROM rol WHERE idRol = {$idRol}";
            if ($baseDatos->Ejecutar($sql) > 0) {
                $fila = $baseDatos->Registro();
                $this->idRol = $fila['idRol'];
                $this->descripcionRol = $fila['descripcionRol'];
                $resultado = true;
            }
        }
        return $resultado;
    }

    public function listar($condicion = "") {
        $lista = [];
        $baseDatos = new BDautenticacion();
        $sql = "SELECT * FROM rol";
        if ($condicion != "") {
            $sql .= " WHERE " . $condicion;
        }
        $sql .= " ORDER BY idRol";

        if ($baseDatos->Iniciar() && $baseDatos->Ejecutar($sql) > 0) {
            while ($fila = $baseDatos->Registro()) {
                $objRol = new Rol();
                $objRol->buscar($fila['idRol']);
                $lista[] = $objRol;
            }
        }
        return $lista;
    }
}
