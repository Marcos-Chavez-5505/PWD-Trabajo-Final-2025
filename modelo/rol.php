CREATE TABLE rol (
    idrol BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
    rodescripcion VARCHAR(50) NOT NULL
);

<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/configuracion.php";

class Rol{
    private $objPdo;
    private $idrol;
    private $rodescripcion;

    // === Getters & Setters ===
    public function getIdrol() { return $this->idrol; }
    public function setIdrol($v) { $this->idrol = $idrol; }

    public function getRodescripcion() { return $this->rodescripcion; }
    public function setRodescripcion($v) { $this->rodescripcion = $rodescripcion; }

    public function __construct() {
        $this->objPdo = new bdCarritoCompras();
    }

    // Insertar rol
    public function insertar() {
        $rta = false;
        if ($this->objPdo->Iniciar()) {
            $sql = "INSERT INTO rol (rodescripcion)
                    VALUES ('{$this->getRodescripcion()}')";
            $rta = $this->objPdo->Ejecutar($sql);
        }
        return $rta;
    }

    // Modificar rol
    public function modificar() {
        $rta = false;
        if ($this->objPdo->Iniciar()) {
            $sql = "UPDATE rol SET 
                        rodescripcion = '{$this->getRodescripcion()}'
                    WHERE idrol = {$this->getIdrol()}";
            $rta = $this->objPdo->Ejecutar($sql);
        }
        return $rta;
    }

    // Eliminar lógico → en esta tabla NO se deshabilita, se borra real
    public function eliminar() {
        $rta = false;
        if ($this->objPdo->Iniciar()) {
            $sql = "DELETE FROM rol WHERE idrol = {$this->getIdrol()}";
            $rta = $this->objPdo->Ejecutar($sql);
        }
        return $rta;
    }

    // Listar roles (devuelve array de objetos Rol)
    public function listar($condicion = "") {
        $arreglo = [];
        if ($this->objPdo->Iniciar()) {
            $sql = "SELECT * FROM rol";
            if ($condicion !== "") {
                $sql .= " WHERE " . $condicion;
            }
            $result = $this->objPdo->Ejecutar($sql);

            if ($result) {
                foreach ($result as $row) {
                    $obj = new Rol();
                    $obj->cargarDesdeArray($row);
                    $arreglo[] = $obj;
                }
            }
        }
        return $arreglo;
    }
}

?>