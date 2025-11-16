<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";
class Compra{
    private $idcompra;
    private $cofecha;
    private $objUsuario;
    private $objPdo;
    private $colItems; // 1:N

    // === Getters & Setters ===
    public function getIdcompra() { return $this->idcompra; }
    public function setIdcompra($v) { $this->idcompra = $v; }
    public function getCofecha() { return $this->cofecha; }
    public function setCofecha($v) { $this->cofecha = $v; }
    public function getObjUsuario() { return $this->objUsuario; }
    public function setObjUsuario($v) { $this->objUsuario = $v; }
    public function getColItems() { return $this->colItems; }
    public function setColItems($v) { $this->colItems = $v; }

    public function __construct() {
        $this->objPdo = new bdCarritoCompras();
    }

    // Insertar
    public function insertar() {
        $rta = false;
        if ($this->objPdo->Iniciar()) {
            $idusuario = $this->getObjUsuario()->getIdusuario() ?? NULL;
            // por diseño de BD solo hace falta idusuario
            $sql = "INSERT INTO compra (idusuario)
                    VALUES ('{$idusuario}')";
            $rta = $this->objPdo->Ejecutar($sql);
        }
        return $rta;
    }

    // Modificar
    public function modificar() {
        $rta = false;
        if ($this->objPdo->Iniciar()) {
            $idusuario = $this->getObjUsuario()->getIdusuario() ?? NULL;
            $sql = "UPDATE usuario SET 
                        idcompra = '{$this->getIdcompra()}',
                        cofecha = '{$this->getCofecha()}',
                        idusuario = '{$idusuario}',
                    WHERE idcompra = {$this->getIdcompra()}";
            $rta = $this->objPdo->Ejecutar($sql);
        }
        return $rta;
    }

    // Listar
    public function listar($condicion = "") {
        $arreglo = [];

        if ($this->objPdo->Iniciar()) {
            $sql = "SELECT * FROM compra";
            if ($condicion !== "") {
                $sql .= " WHERE " . $condicion;
            }
            $this->objPdo->Ejecutar($sql);

            $filas = $this->objPdo->getFilas();
            if (!empty($filas)) {

                foreach ($filas as $fila) {
                    $obj = new Compra();
                    $res = $obj->buscar($fila['idcompra']);

                    if ($res) $arreglo[] = $obj;
                }
            }
        }
        $this->setColItems($arreglo);
        return $arreglo;
    }

    public function cargar($idcompra, $cofecha, $objUsuario){
        $this->setIdcompra($idcompra);
        $this->setCofecha($cofecha);
        $this->setObjUsuario($objUsuario);
    }

    public function buscar($id) {
        $resultado = false;
        if ($this->objPdo->Iniciar()) {
            $this->objPdo->Ejecutar("SELECT * FROM compra WHERE idcompra = {$id}");
            $filas = $this->objPdo->getFilas();
            if (!empty($filas)) {
                $fila = $filas[0];
                $objUsuario = new Usuario();
                $objUsuario->buscar($fila['idusuario']);
                $this->cargar(
                    $fila['idcompra'],
                    $fila['cofecha'],
                    $objUsuario
                );
                $resultado = true;
            }
        }
        return $resultado;
    }

}
?>

