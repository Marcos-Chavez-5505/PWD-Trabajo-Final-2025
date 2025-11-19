<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";

class Rol {
    private $idrol;
    private $rodescripcion;
    private $objBaseDatos;

    public function __construct($objBaseDatos = null) {
        $this->idrol = null;
        $this->rodescripcion = "";
        $this->objBaseDatos = $objBaseDatos ?? new bdCarritoCompras();
    }

    public function getIdRol() { return $this->idrol; }
    public function getDescripcionRol() { return $this->rodescripcion; }

    public function setIdRol($idrol) { $this->idrol = $idrol; }
    public function setDescripcionRol($rodescripcion) { $this->rodescripcion = $rodescripcion; }

    public function insertar() {
        $exito = false;
        if ($this->objBaseDatos->Iniciar()) {
            $sql = "INSERT INTO rol (rodescripcion) VALUES ('{$this->rodescripcion}')";
            $id = $this->objBaseDatos->Ejecutar($sql);
            if ($id != -1) {
                $this->idrol = $id;
                $exito = true;
            }
        }
        return $exito;
    }

    public function modificar() {
        $exito = false;
        if ($this->objBaseDatos->Iniciar()) {
            $sql = "UPDATE rol SET rodescripcion = '{$this->rodescripcion}' WHERE idrol = {$this->idrol}";
            if ($this->objBaseDatos->Ejecutar($sql) >= 0) $exito = true;
        }
        return $exito;
    }

    public function eliminar() {
        $exito = false;
        if ($this->objBaseDatos->Iniciar()) {
            $sql = "DELETE FROM rol WHERE idrol = {$this->idrol}";
            if ($this->objBaseDatos->Ejecutar($sql) >= 0) $exito = true;
        }
        return $exito;
    }

    public function buscar($idrol) {
        $exito = false;
        if ($this->objBaseDatos->Iniciar()) {
            $sql = "SELECT * FROM rol WHERE idrol = {$idrol}";
            if ($this->objBaseDatos->Ejecutar($sql) > 0) {
                $fila = $this->objBaseDatos->Registro();
                $this->idrol = $fila['idrol'];
                $this->rodescripcion = $fila['rodescripcion'];
                $exito = true;
            }
        }
        return $exito;
    }

    public function listar($condicion = "") {
        $arreglo = [];
        $sql = "SELECT * FROM rol";
        if ($condicion != "") $sql .= " WHERE " . $condicion;
        $sql .= " ORDER BY idrol";

        if ($this->objBaseDatos->Iniciar() && $this->objBaseDatos->Ejecutar($sql) > 0) {
            while ($fila = $this->objBaseDatos->Registro()) {
                $obj = new Rol($this->objBaseDatos);
                $obj->buscar($fila['idrol']);
                $arreglo[] = $obj;
            }
        }
        return $arreglo;
    }
}
?>
