<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/configuracion.php";
class CompraEstadoTipo{
    private $idcompraestadotipo;
    private $cetdescripcion;
    private $cetdetalle;
    private $objPdo;

    // === Getters & Setters ===
    public function getIdcompraestadotipo() { return $this->idcompraestadotipo; }
    public function setIdcompraestadotipo($v) { $this->idcompraestadotipo = $v; }
    public function getCetdescripcion() { return $this->cetdescripcion; }
    public function setCetdescripcion($v) { $this->cetdescripcion = $v; }
    public function getCetdetalle() { return $this->cetdetalle; }
    public function setCetdetalle($v) { $this->cetdetalle = $v; }

    public function __construct() {
        $this->objPdo = new bdCarritoCompras();
    }

    // Insertar
    public function insertar() {
        $rta = false;
        if ($this->objPdo->Iniciar()) {
            $sql = "INSERT INTO compraestadotipo (idcompraestadotipo, cetdescripcion, cetdetalle)
                    VALUES ('{$this->getIdcompraestadotipo()}', '{$this->getCetdescripcion()}', '{$this->getCetdetalle()}')";
            $rta = $this->objPdo->Ejecutar($sql);
        }
        return $rta;
    }

    // Modificar
    public function modificar() {
        $rta = false;
        if ($this->objPdo->Iniciar()) {
            $sql = "UPDATE compraestadotipo SET 
                        idcompraestadotipo = '{$this->getIdcompraestadotipo()}',
                        cetdescripcion = '{$this->getCetdescripcion()}',
                        cetdetalle = '{$this->getCetdetalle()}',
                    WHERE idcompraestadotipo = {$this->getIdcompraestadotipo()}";
            $rta = $this->objPdo->Ejecutar($sql);
        }
        return $rta;
    }

    // Listar
    public function listar($condicion = "") {
        $arreglo = [];

        if ($this->objPdo->Iniciar()) {
            $sql = "SELECT * FROM compraestadotipo";
            if ($condicion !== "") {
                $sql .= " WHERE " . $condicion;
            }
            $this->objPdo->Ejecutar($sql);

            $filas = $this->objPdo->getFilas();
            if (!empty($filas)) {

                foreach ($filas as $fila) {
                    $obj = new CompraEstadoTipo();
                    $res = $obj->buscar($fila['idcompraestadotipo']);

                    if ($res) $arreglo[] = $obj;
                }
            }
        }
        return $arreglo;
    }

    public function cargar($idcompraestadotipo, $cetdescripcion, $cetdetalle){
        $this->setIdcompraestadotipo($idcompraestadotipo);
        $this->setCetdescripcion($cetdescripcion);
        $this->setCetdetalle($cetdetalle);
    }

    public function buscar($id) {
        $resultado = false;
        if ($this->objPdo->Iniciar()) {
            $this->objPdo->Ejecutar("SELECT * FROM compraestadotipo WHERE idcompraestadotipo = {$id}");
            $filas = $this->objPdo->getFilas();
            if (!empty($filas)) {
                $fila = $filas[0];
                $this->cargar(
                    $fila['idcompraestadotipo'],
                    $fila['cetdescripcion'],
                    $fila['cetdetalle']
                );
                $resultado = true;
            }
        }
        return $resultado;
    }

}
?>

