<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";
class Producto{
    private $idproducto;
    private $pronombre;
    private $prodetalle;
    private $procantstock;
    private $proprecio;
    private $proimagen;
    private $objPdo;

    // === Getters & Setters ===
    public function getIdproducto() { return $this->idproducto; }
    public function setIdproducto($idproducto) {$this->idproducto = ($idproducto === '' || $idproducto === null) ? null : (int)$idproducto;}
    public function getPronombre() { return $this->pronombre; }
    public function setPronombre($v) { $this->pronombre = $v; }
    public function getProdetalle() { return $this->prodetalle; }
    public function setProdetalle($v) { $this->prodetalle = $v; }
    public function getProcantstock() { return $this->procantstock; }
    public function setProcantstock($v) { $this->procantstock = $v; }
    public function getProprecio() { return $this->proprecio; }
    public function setProprecio($v) { $this->proprecio = $v; }
    public function getProimagen() { return $this->proimagen; }
    public function setProimagen($v) { $this->proimagen = $v; }

    public function __construct() {
        $this->objPdo = new bdCarritoCompras();
    }

    // Insertar Compra
    public function insertar() {
        $exito = false;
        if ($this->objPdo->Iniciar()) {
            $sql = "INSERT INTO producto (pronombre, prodetalle, procantstock, proprecio, proimagen)
                    VALUES ('{$this->getPronombre()}', '{$this->getProdetalle()}', '{$this->getProcantstock()}', '{$this->getProprecio()}', '{$this->getProimagen()}')";
            $idGenerado = $this->objPdo->Ejecutar($sql);
        }
        return $idGenerado;
    }

    // Modificar Compra
    public function modificar() {
        $rta = false;
        if ($this->objPdo->Iniciar()) {
            $sql = "UPDATE producto SET 
                        idproducto = '{$this->getIdproducto()}',
                        pronombre = '{$this->getPronombre()}',
                        prodetalle = '{$this->getProdetalle()}',
                        procantstock = '{$this->getProcantstock()}',
                        proprecio = '{$this->getProprecio()}',
                        proimagen = '{$this->getProimagen()}'
                    WHERE idproducto = {$this->getIdproducto()}";
            $rta = $this->objPdo->Ejecutar($sql);
        }
        return $rta;
    }

    public function listar($condicion = "") {
        $arreglo = [];

        if ($this->objPdo->Iniciar()) {
            $sql = "SELECT * FROM producto";
            if ($condicion !== "") {
                $sql .= " WHERE " . $condicion;
            }
            $this->objPdo->Ejecutar($sql);

            $filas = $this->objPdo->getFilas();
            if (!empty($filas)) {

                foreach ($filas as $fila) {
                    $obj = new Producto();
                    $res = $obj->buscar($fila['idproducto']);

                    if ($res) $arreglo[] = $obj;
                }
            }
        }
        return $arreglo;
    }

    public function cargar($pronombre, $prodetalle, $procantstock, $proprecio, $proimagen){
        $this->setPronombre($pronombre);
        $this->setProdetalle($prodetalle);
        $this->setProcantstock($procantstock);
        $this->setProprecio($proprecio);
        $this->setProimagen($proimagen);
    }

    public function buscar($id) {
        $resultado = false;
        if ($this->objPdo->Iniciar()) {
            $this->objPdo->Ejecutar("SELECT * FROM producto WHERE idproducto = {$id}");
            $filas = $this->objPdo->getFilas();
            if (!empty($filas)) {
                $fila = $filas[0];
                $this->cargar(
                    $fila['pronombre'],
                    $fila['prodetalle'],
                    $fila['procantstock'],
                    $fila['proprecio'],
                    $fila['proimagen'],
                );
                $this->setIdproducto($fila['idproducto']);
                $resultado = true;
            }
        }
        return $resultado;
    }

    // Modificar Compra
    public function eliminar() {
        $rta = false;
        if ($this->objPdo->Iniciar()) {
            $sql = "DELETE FROM producto WHERE idproducto = {$this->getIdproducto()}";
            $rta = $this->objPdo->Ejecutar($sql);
        }
        return $rta;
    }

    
    public function reducirStock($idproducto, $cantidad = 1) {
        $rta = false;
    
        if ((int)$cantidad > 0){
            $producto = new Producto();
    
            if ($producto->buscar($idproducto)){
    
                $stockActual = (int)$producto->getProcantstock();
                if ($stockActual > $cantidad) {
                    $producto->setProcantstock($stockActual - $cantidad);
                    $producto->modificar();
                    $rta = true;
                }
            }
        }
    
        return $rta;
    }
}

?>

