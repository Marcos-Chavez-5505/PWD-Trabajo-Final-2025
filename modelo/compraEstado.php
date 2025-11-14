<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/configuracion.php";
class CompraEstado{
    private $idcompraestado;
    private $objCompra;
    private $objCompraEstadoTipo;
    private $cefechaini;
    private $cefechafin;
    private $objPdo;

    // === Getters & Setters ===
    public function getIdcompraestado() { return $this->idcompraestado; }
    public function setIdcompraestado($v) { $this->idcompraestado = $v; }
    public function getObjCompra() { return $this->objCompra; }
    public function setObjCompra($v) { $this->objCompra = $v; }
    public function getObjCompraEstadoTipo() { return $this->objCompraEstadoTipo; }
    public function setObjCompraEstadoTipo($v) { $this->objCompraEstadoTipo = $v; }
    public function getCefechaini() { return $this->cefechaini; }
    public function setCefechaini($v) { $this->cefechaini = $v; }
    public function getCefechafin() { return $this->cefechafin; }
    public function setCefechafin($v) { $this->cefechafin = $v; }
    
    public function __construct() {
        $this->objPdo = new bdCarritoCompras();
    }

    // Insertar
    public function insertar() {
        $rta = false;
        if ($this->objPdo->Iniciar()) {
            $idcompra = $this->getObjCompra()->getIdcompra() ?? NULL;
            $idcompraestadotipo = $this->getObjCompraEstadoTipo()->getIdcompraestadotipo() ?? NULL;
            $sql = "INSERT INTO compraestadotipo (idcompraestado, idcompra, idcompraestadotipo, cefechaini, cefechafin)
                    VALUES ('{$this->getIdcompraestado()}', '{$idcompra}', '{$idcompraestadotipo}', '{$this->getCefechaini()}', '{$this->getCefechafin()}')";
            $rta = $this->objPdo->Ejecutar($sql);
        }
        return $rta;
    }

    // Modificar
    public function modificar() {
        $rta = false;
        if ($this->objPdo->Iniciar()) {
            $sql = "UPDATE compraestado SET 
                        idcompraestado = '{$this->getIdcompraestado()}',
                        idcompra = '{$this->getObjCompra()}',
                        idcompraestadotipo = '{$this->getObjCompraEstadoTipo()}',
                        cefechaini = '{$this->getCefechaini()}',
                        cefechafin = '{$this->getCefechafin()}',
                    WHERE idcompraestado = {$this->getIdcompraestado()}";
            $rta = $this->objPdo->Ejecutar($sql);
        }
        return $rta;
    }

    // Listar
    public function listar($condicion = "") {
        $arreglo = [];

        if ($this->objPdo->Iniciar()) {
            $sql = "SELECT * FROM compraestado";
            if ($condicion !== "") {
                $sql .= " WHERE " . $condicion;
            }
            $this->objPdo->Ejecutar($sql);

            $filas = $this->objPdo->getFilas();
            if (!empty($filas)) {

                foreach ($filas as $fila) {
                    $obj = new CompraEstado();
                    $res = $obj->buscar($fila['idcompraestado']);

                    if ($res) $arreglo[] = $obj;
                }
            }
        }
        return $arreglo;
    }

    public function cargar($idcompraestado, $objCompra, $objCompraEstadoTipo, $cefechaini, $cefechafin){
        $this->setIdcompraestado($idcompraestado);
        $this->setObjCompra($objCompra);
        $this->setObjCompraEstadoTipo($objCompraEstadoTipo);
        $this->setCefechaini($cefechaini);
        $this->setCefechafin($cefechafin);
    }

    public function buscar($id) {
        $resultado = false;
        if ($this->objPdo->Iniciar()) {
            $this->objPdo->Ejecutar("SELECT * FROM compraestado WHERE idcompraestado = {$id}");
            $filas = $this->objPdo->getFilas();
            if (!empty($filas)) {
                $fila = $filas[0];
                $objCompra = new Compra();
                $objCompra->buscar($fila['idcompra']);
                $objCompraEstadoTipo = new CompraEstadoTipo();
                $objCompraEstadoTipo->buscar($fila['idcompraestadotipo']);
                $this->cargar(
                    $fila['idcompraestado'],
                    $objCompra,
                    $objCompraEstadoTipo,
                    $fila['cofechaini'],
                    $fila['cofechafin']
                );
                $resultado = true;
            }
        }
        return $resultado;
    }

}
?>

