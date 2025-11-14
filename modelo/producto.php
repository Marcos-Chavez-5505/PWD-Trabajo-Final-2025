<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/configuracion.php";
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
    public function setIdproducto($v) { $this->idproducto = $v; }
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
        $rta = false;
        if ($this->objPdo->Iniciar()) {
            $sql = "INSERT INTO producto (idproducto, pronombre, prodetalle, procantstock, proprecio, proimagen)
                    VALUES ('{$this->getIdproducto()}', '{$this->getPronombre()}', '{$this->getProdetalle()}', '{$this->getProcantstock()}', '{$this->getProprecio()}', '{$this->getProimagen()}')";
            $rta = $this->objPdo->Ejecutar($sql);
        }
        return $rta;
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
                        proimagen = '{$this->getProimagen()}',
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

    public function cargar($idproducto, $pronombre, $prodetalle, $procantstock, $proprecio, $proimagen){
        $this->setIdproducto($idproducto);
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
                    $fila['idproducto'],
                    $fila['pronombre'],
                    $fila['prodetalle'],
                    $fila['procantstock'],
                    $fila['proprecio'],
                    $fila['proimagen'],
                );
                $resultado = true;
            }
        }
        return $resultado;
    }

}
?>

