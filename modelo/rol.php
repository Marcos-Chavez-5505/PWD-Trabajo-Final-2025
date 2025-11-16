<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/modelo/conector/BDautenticacion.php';

class Rol {
    private $idrol;
    private $rodescripcion;
    private $objBaseDatos;

    public function __construct($objBaseDatos = null) {
        $this->idrol = null;
        $this->rodescripcion = "";
        $this->objBaseDatos = $objBaseDatos ?? new BDautenticacion();
    }

    // Getters y Setters
    public function getIdRol() { return $this->idrol; }
    public function getDescripcionRol() { return $this->rodescripcion; }

    public function setIdRol($idrol) { $this->idrol = $idrol; }
    public function setDescripcionRol($rodescripcion) { $this->rodescripcion = $rodescripcion; }

    // CRUD
    public function insertar() {
        $base = new BDautenticacion();
        $exito = false;

        if ($base->Iniciar()) {
            $sql = "INSERT INTO rol (rodescripcion)
                    VALUES ('{$this->rodescripcion}')";
            $id = $base->Ejecutar($sql);

            if ($id != -1) {
                $this->idrol = $id;
                $exito = true;
            }
        }
        return $exito;
    }

    public function modificar() {
        $base = new BDautenticacion();
        $exito = false;

        if ($base->Iniciar()) {
            $sql = "UPDATE rol SET rodescripcion = '{$this->rodescripcion}'
                    WHERE idrol = {$this->idrol}";
            if ($base->Ejecutar($sql) >= 0) {
                $exito = true;
            }
        }
        return $exito;
    }

    public function eliminar() {
        $base = new BDautenticacion();
        $exito = false;

        if ($base->Iniciar()) {
            $sql = "DELETE FROM rol WHERE idrol = {$this->idrol}";
            if ($base->Ejecutar($sql) >= 0) {
                $exito = true;
            }
        }
        return $exito;
    }

    public function buscar($idrol) {
        $base = new BDautenticacion();
        $exito = false;

        if ($base->Iniciar()) {
            $sql = "SELECT * FROM rol WHERE idrol = {$idrol}";
            if ($base->Ejecutar($sql) > 0) {
                $fila = $base->Registro();
                $this->idrol = $fila['idrol'];
                $this->rodescripcion = $fila['rodescripcion'];
                $exito = true;
            }
        }
        return $exito;
    }

    public function listar($condicion = "") {
        $arreglo = [];
        $base = new BDautenticacion();
        $sql = "SELECT * FROM rol";

        if ($condicion != "") {
            $sql .= " WHERE " . $condicion;
        }

        $sql .= " ORDER BY idrol";

        if ($base->Iniciar() && $base->Ejecutar($sql) > 0) {
            while ($fila = $base->Registro()) {
                $obj = new Rol();
                $obj->buscar($fila['idrol']);
                $arreglo[] = $obj;
            }
        }

        return $arreglo;
    }
}
