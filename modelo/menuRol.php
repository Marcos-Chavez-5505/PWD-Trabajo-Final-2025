<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";
class MenuRol {
    private $idmenu;
    private $idrol;
    private $objPdo;
    private $objMenu;
    private $objRol;

    //getters
    public function getIdmenu() { return $this->idmenu; }
    public function getIdrol()  { return $this->idrol; }
    public function getObjMenu()  { return $this->objMenu; }
    public function getObjRol()  { return $this->objRol; }

    //setters
    public function setIdmenu($v) { $this->idmenu = $v; }
    public function setIdrol($v)  { $this->idrol = $v; }
    public function setObjMenu($v)  { $this->objMenu = $v; }
    public function setObjRol($v)  { $this->objRol = $v; }

    //construc
    public function __construct(){
        $this->objPdo = new bdCarritoCompras();
    }

    //cargar
    public function cargar($idmenu, $idrol, $objMenu, $objRol){
        $this->setIdmenu($idmenu);
        $this->setIdrol($idrol);
        $this->setObjMenu($objMenu);
        $this->setObjRol($objRol);
    }

    //insertar
    public function insertar(){
        $rta = false;

        if($this->objPdo->Iniciar()){
            $sql = "INSERT INTO menurol (idmenu, idrol)
                    VALUES ('{$this->getIdmenu()}', '{$this->getIdrol()}')";
            $rta = $this->objPdo->Ejecutar($sql);
        }   
    return $rta;
    }

    //eliminar
    public function eliminar(){
        $rta = false;

        if ($this->objPdo->Iniciar()) {
            $sql = "DELETE FROM menurol 
                    WHERE idmenu = '{$this->getIdmenu()}'
                      AND idrol = '{$this->getIdrol()}'";
            $rta = $this->objPdo->Ejecutar($sql);
        }
    return $rta;
    }

    //buscar
    public function buscar($idmenu, $idrol) {
        $rta = false;

        if ($this->objPdo->Iniciar()) {
            $sql = "SELECT * FROM menurol 
                    WHERE idmenu = {$idmenu} AND idrol = {$idrol}";
            $this->objPdo->Ejecutar($sql);
            $filas = $this->objPdo->getFilas();

            if (!empty($filas)) {
                $fila = $filas[0];

                $objMenu = new Menu();
                $objMenu->setIdmenu($fila['idmenu']);
                $objMenu->cargar();
                $objRol = new Rol();
                $objRol->buscar($fila['idrol']);
                $this->cargar(
                    $fila['idmenu'],
                    $fila['idrol'],
                    $objMenu,
                    $objRol
                );
                $rta = true;
            }
        }
        return $rta;
    }

    //listar
    public function listar($condicion = ""){
        $arreglo = [];

        if($this->objPdo->Iniciar()){

            $sql = "SELECT * FROM menurol";
            if ($condicion !== "") {
                $sql .= " WHERE " . $condicion;
            }

            $this->objPdo->Ejecutar($sql);
            $filas = $this->objPdo->getFilas();

            if(!empty($filas)){
                foreach ($filas as $fila){
                    $obj = new MenuRol();
                    $obj->buscar($fila['idmenu'], $fila['idrol']);
                    $arreglo[] = $obj;
                }
            }
        }
    return $arreglo;
    }
}
?>
