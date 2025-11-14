<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/configuracion.php";
class CompraItem{
    private $idcompraitem;
    private $objProducto;
    private $objCompra;
    private $cicantidad;
    private $objPdo;

    // === Getters & Setters ===
    public function getIdcompraitem() { return $this->idcompraitem; }
    public function setIdcompraitem($v) { $this->idcompraitem = $v; }
    public function getObjProducto() { return $this->objProducto; }
    public function setObjProducto($v) { $this->objProducto = $v; }
    public function getObjCompra() { return $this->objCompra; }
    public function setObjCompra($v) { $this->objCompra = $v; }
    public function getCicantidad() { return $this->cicantidad; }
    public function setCicantidad($v) { $this->cicantidad = $v; }

    public function __construct() {
        $this->objPdo = new bdCarritoCompras();
    }

    // Insertar Compra
    public function insertar() {
        $rta = false;
        if ($this->objPdo->Iniciar()) {
            $idproducto = $this->getObjProducto()->getIdproducto() ?? NULL;
            $idcompra = $this->getObjCompra()->getIdcompra() ?? NULL;
            $sql = "INSERT INTO compraitem (idcompraitem, idproducto, idcompra, cicantidad)
                    VALUES ('{$this->getIdcompraitem()}', '{$idproducto}', '{$idcompra}', '{$this->getCicantidad()}')";
            $rta = $this->objPdo->Ejecutar($sql);
        }
        return $rta;
    }

    // Modificar Compra
    public function modificar() {
        $rta = false;
        if ($this->objPdo->Iniciar()) {
            $idproducto = $this->getObjProducto()->getIdproducto() ?? NULL;
            $idcompra = $this->getObjCompra()->getIdcompra() ?? NULL;
            $sql = "UPDATE compraitem SET 
                        idcompraitem = '{$this->getIdcompraitem()}',
                        idproducto = '{$idproducto}',
                        idcompra = '{$idcompra}',
                        cicantidad = '{$this->getCicantidad()}',
                    WHERE idcompraitem = {$this->getIdcompraitem()}";
            $rta = $this->objPdo->Ejecutar($sql);
        }
        return $rta;
    }

    public function listar($condicion = "") {
        $arreglo = [];

        if ($this->objPdo->Iniciar()) {
            $sql = "SELECT * FROM compraitem";
            if ($condicion !== "") {
                $sql .= " WHERE " . $condicion;
            }
            $this->objPdo->Ejecutar($sql);

            $filas = $this->objPdo->getFilas();
            if (!empty($filas)) {

                foreach ($filas as $fila) {
                    $obj = new CompraItem();
                    $res = $obj->buscar($fila['idcompraitem']);

                    if ($res) $arreglo[] = $obj;
                }
            }
        }
        return $arreglo;
    }

    public function cargar($idcompraitem, $objProducto, $objCompra, $cicantidad){
        $this->setIdcompraitem($idcompraitem);
        $this->setObjProducto($objProducto);
        $this->setObjCompra($objCompra);
        $this->setCicantidad($cicantidad);
    }

    public function buscar($id) {
        $resultado = false;
        if ($this->objPdo->Iniciar()) {
            $this->objPdo->Ejecutar("SELECT * FROM compraitem WHERE idcompraitem = {$id}");
            $filas = $this->objPdo->getFilas();
            if (!empty($filas)) {
                $fila = $filas[0];
                $objCompra = new Compra();
                $objCompra->buscar($fila['idcompra']);
                $objProducto = new Producto();
                $objProducto->buscar($fila['idproducto']);
                $this->cargar(
                    $fila['idcompraitem'],
                    $objProducto,
                    $objCompra,
                    $fila['cicantidad'],
                );
                $resultado = true;
            }
        }
        return $resultado;
    }

}
?>

