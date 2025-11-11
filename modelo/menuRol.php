CREATE TABLE menurol (
    idmenu BIGINT(20) NOT NULL,
    idrol BIGINT(20) NOT NULL,
    PRIMARY KEY (idmenu, idrol),
    FOREIGN KEY (idmenu) REFERENCES menu(idmenu),
    FOREIGN KEY (idrol) REFERENCES rol(idrol)
);

<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/configuracion.php";

class menuRol{
    private $objPdo;
    private $idmenu;
    private $idrol;

    // Getters y Setters
    public function getIdmenu() { return $this->idmenu; }
    public function setIdmenu($v) { $this->idmenu = $idmenu; }

    public function getIdrol() { return $this->idrol; }
    public function setIdrol($v) { $this->idrol = $idrol; }

    //Construct
    public function __construct() {
        $this->objPdo = new bdCarritoCompras();
    }

    // Cargar desde BD
    private function cargarDesdeArray($row) {
        $this->setIdmenu($row['idmenu']);
        $this->setIdrol($row['idrol']);
    }

    // Insertar relación
    public function insertar() {
        $rta = false;
        if ($this->objPdo->Iniciar()) {
            $sql = "INSERT INTO menurol (idmenu, idrol)
                    VALUES ({$this->getIdmenu()}, {$this->getIdrol()})";
            $rta = $this->objPdo->Ejecutar($sql);
        }
        return $rta;
    }

    // Eliminar relación
    public function eliminar() {
        $rta = false;
        if ($this->objPdo->Iniciar()) {
            $sql = "DELETE FROM menurol 
                    WHERE idmenu = {$this->getIdmenu()} 
                    AND idrol = {$this->getIdrol()}";
            $rta = $this->objPdo->Ejecutar($sql);
        }
        return $rta;
    }

    // Listar relaciones (devuelve array de objetos)
    public function listar($condicion = "") {
        $arreglo = [];
        if ($this->objPdo->Iniciar()) {
            $sql = "SELECT * FROM menurol";
            if ($condicion != "") {
                $sql .= " WHERE " . $condicion;
            }
            $result = $this->objPdo->Ejecutar($sql);

            foreach ($result as $row) {
                $obj = new MenuRol();
                $obj->cargarDesdeArray($row);
                $arreglo[] = $obj;
            }
        }
        return $arreglo;
    }

}
?>
