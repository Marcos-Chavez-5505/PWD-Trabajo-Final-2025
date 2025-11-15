<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/configuracion.php";

class menuRol{
    private $objMenu;
    private $objRol;
    private $objPdo;

    // Getters y Setters
    public function getObjMenu() { return $this->objMenu; }
    public function setObjMenu($v) { $this->objMenu = $v; }

    public function getObjRol() { return $this->objRol; }
    public function setObjRol($v) { $this->objRol = $v; }

    //Construct
    public function __construct() {
        $this->objPdo = new bdCarritoCompras();
    }

    // Insertar
    public function insertar() {
        $rta = false;
        if ($this->objPdo->Iniciar()) {
            $idmenu = $this->getObjMenu()->getIdmenu() ?? NULL;
            $idrol = $this->getObjRol()->getIdrol() ?? NULL;
            $sql = "INSERT INTO menurol (idmenu, idrol)
                    VALUES ({$idmenu}, {$idrol})";
            $rta = $this->objPdo->Ejecutar($sql);
        }
        return $rta;
    }

    // Listar
    public function listar($condicion = "") {
        $arreglo = [];

        if ($this->objPdo->Iniciar()) {
            $sql = "SELECT * FROM menurol";
            if ($condicion !== "") {
                $sql .= " WHERE " . $condicion;
            }
            $this->objPdo->Ejecutar($sql);

            $filas = $this->objPdo->getFilas();
            if (!empty($filas)) {

                foreach ($filas as $fila) {
                    $obj = new MenuRol();
                    $res = $obj->buscar($fila['idmenurol']);

                    if ($res) $arreglo[] = $obj;
                }
            }
        }
        return $arreglo;
    }

    public function cargar($objMenu, $objRol){
        $this->setObjMenu($objMenu);
        $this->setObjRol($objRol);
    }

    public function buscar($id) {
        $resultado = false;
        if ($this->objPdo->Iniciar()) {
            $this->objPdo->Ejecutar("SELECT * FROM menurol WHERE idmenu = {$id} OR idrol = {$id}");
            $filas = $this->objPdo->getFilas();
            if (!empty($filas)) {
                $fila = $filas[0];
                $this->cargar(
                    $fila['idmenu'],
                    $fila['idrol'],
                );
                $resultado = true;
            }
        }
        return $resultado;
    }

    /**
     * Lista todos los ítems de menú disponibles para un rol.
     * Devuelve ARRAY de objetos Menu.
     */
    public function listarMenuPorRol($idRol) {
        $arreglo = [];
        $sql = "SELECT m.*
                FROM menu m
                INNER JOIN menurol mr ON m.idmenu = mr.idmenu
                WHERE mr.idrol = $idRol
                ORDER BY m.idpadre, m.idmenu";
    
        if ($this->objPdo->Iniciar()) {
            $res = $this->objPdo->Ejecutar($sql);
            if ($res > 0) {
                while ($row = $this->objPdo->Registro()) {
                    $menu = new Menu();
                    $menu->setIdmenu($row['idmenu']);
                    $menu->cargar(); // carga padre e info completa
                    $arreglo[] = $menu;
                }
            }
        }
        return $arreglo;
    }



}
?>
