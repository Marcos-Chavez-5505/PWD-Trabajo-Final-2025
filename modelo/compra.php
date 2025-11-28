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

    public function getObjPdo(){ return $this->objPdo;}

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
            $sql = "UPDATE compra SET 
                        idcompra = '{$this->getIdcompra()}',
                        cofecha = '{$this->getCofecha()}',
                        idusuario = '{$idusuario}'
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

    public function listarComprasSinEstado($condicion = "") {
        $arreglo = [];
        
        $sql = "SELECT c.* 
                FROM compra c
                LEFT JOIN compraestado ce ON c.idcompra = ce.idcompra
                WHERE ce.idcompra IS NULL";
        
        if ($condicion !== "") {
            $sql .= " AND " . $condicion; 
        }
        
        if ($this->objPdo->Iniciar()) {
                $this->objPdo->Ejecutar($sql);
                $filas = $this->objPdo->getFilas();

            if (!empty($filas)) {
                foreach ($filas as $fila) {
                $obj = new Compra(); 
                $obj->setIdcompra($fila['idcompra']);
                $obj->setCofecha($fila['cofecha']);
                
                
                $usuario = new usuario();
                $usuario->buscar($fila['idusuario']);
                $obj->setObjUsuario($usuario);
                
                $arreglo[] = $obj;
                }
            }
        }
        return $arreglo;
    }


    public function listarIDComprasSinEstadoNiFecha($idUsuario, $condicion = "") {
        $idCompra = null;

        $sql = "
            SELECT c.idcompra 
            FROM compra c
            LEFT JOIN compraestado ce ON c.idcompra = ce.idcompra
            LEFT JOIN compraestadotipo cet ON ce.idcompraestadotipo = cet.idcompraestadotipo
            WHERE c.idusuario = {$idUsuario}
            AND (ce.cefechafin IS NULL OR ce.idcompraestado IS NULL)
            ORDER BY c.idcompra DESC
            LIMIT 1  
        ";

        if ($condicion !== "") {
            $sql = rtrim($sql, ";");
            $sql .= " AND " . $condicion;
        }

        if ($this->objPdo->Iniciar()) {
            $this->objPdo->Ejecutar($sql);
            $filas = $this->objPdo->getFilas();

            if (!empty($filas)) {
                $idCompra = (int)$filas[0]['idcompra'];
            }
        }

        return $idCompra;
    }


}
?>

