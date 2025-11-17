<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";

class Menu {
    private $idmenu;
    private $menombre;
    private $medescripcion;
    private $meurl;
    private $ObjMenu;
    private $idpadre;
    private $medeshabilitado;
    private $mensajeoperacion;

    public function getIdmenu() { return $this->idmenu; }
    public function setIdmenu($idmenu) { $this->idmenu = $idmenu; }

    public function getMenombre() { return $this->menombre; }
    public function setMenombre($menombre) { $this->menombre = $menombre; }

    public function getMedescripcion() { return $this->medescripcion; }
    public function setMedescripcion($medescripcion) { $this->medescripcion = $medescripcion; }

    public function getMeurl() { return $this->meurl; }
    public function setMeurl($meurl) { $this->meurl = $meurl; }

    public function getObjMenu() { return $this->ObjMenu; }
    public function setObjMenu($ObjMenu) { $this->ObjMenu = $ObjMenu; }

    public function getIdpadre() { return $this->idpadre; }
    public function setIdpadre($idpadre) { $this->idpadre = $idpadre; }

    public function getMedeshabilitado() { return $this->medeshabilitado; }
    public function setMedeshabilitado($medeshabilitado) { $this->medeshabilitado = $medeshabilitado; }

    public function getMensajeoperacion() { return $this->mensajeoperacion; }
    public function setMensajeoperacion($mensajeoperacion) { $this->mensajeoperacion = $mensajeoperacion; }

    public function __construct() {
        $this->idmenu = "";
        $this->menombre = "";
        $this->medescripcion = "";
        $this->meurl = "";
        $this->ObjMenu = null;
        $this->idpadre = null;
        $this->medeshabilitado = null;
        $this->mensajeoperacion = "";
    }

    public function setear($idmenu, $menombre, $medescripcion, $meurl, $ObjMenu, $medeshabilitado) {
        $this->setIdmenu($idmenu);
        $this->setMenombre($menombre);
        $this->setMedescripcion($medescripcion);
        $this->setMeurl($meurl);
        $this->setObjMenu($ObjMenu);
        $this->setMedeshabilitado($medeshabilitado);
    }

    public function cargar() {
        $resp = false;
        $base = new bdCarritoCompras();
        $sql = "SELECT * FROM menu WHERE idmenu = " . $this->getIdmenu();
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > 0) {
                $row = $base->Registro();
                $objMenuPadre = null;
                if (!empty($row['idpadre'])) {
                    $objMenuPadre = new Menu();
                    $objMenuPadre->setIdmenu($row['idpadre']);
                    $objMenuPadre->cargar();
                }
                $this->setear(
                    $row['idmenu'],
                    $row['menombre'],
                    $row['medescripcion'],
                    $row['meurl'],
                    $objMenuPadre,
                    $row['medeshabilitado']
                );
                $resp = true;
            }
        } else {
            $this->setMensajeoperacion("Menu->cargar: " . $base->getError()[2]);
        }
        return $resp;
    }

    public function insertar() {
        $resp = false;
        $base = new bdCarritoCompras();
        $sql = "INSERT INTO menu (menombre, medescripcion, meurl, idpadre, medeshabilitado) VALUES ('"
            . $this->getMenombre() . "', '"
            . $this->getMedescripcion() . "', '"
            . $this->getMeurl() . "', ";

        if ($this->getObjMenu() != null)
            $sql .= $this->getObjMenu()->getIdmenu() . ",";
        else
            $sql .= "null,";

        if ($this->getMedeshabilitado() != null)
            $sql .= "'" . $this->getMedeshabilitado() . "'";
        else
            $sql .= "null";

        $sql .= ");";

        if ($base->Iniciar()) {
            if ($elid = $base->Ejecutar($sql)) {
                $this->setIdmenu($elid);
                $resp = true;
            } else {
                $this->setMensajeoperacion("Menu->insertar: " . $base->getError()[2]);
            }
        } else {
            $this->setMensajeoperacion("Menu->insertar: " . $base->getError()[2]);
        }
        return $resp;
    }

    public function modificar() {
        $resp = false;
        $base = new bdCarritoCompras();
        $sql = "UPDATE menu SET "
            . "menombre='" . $this->getMenombre() . "', "
            . "medescripcion='" . $this->getMedescripcion() . "', "
            . "meurl='" . $this->getMeurl() . "'";

        if ($this->getObjMenu() != null)
            $sql .= ", idpadre=" . $this->getObjMenu()->getIdmenu();
        else
            $sql .= ", idpadre=null";

        if ($this->getMedeshabilitado() != null)
            $sql .= ", medeshabilitado='" . $this->getMedeshabilitado() . "'";
        else
            $sql .= ", medeshabilitado=null";

        $sql .= " WHERE idmenu=" . $this->getIdmenu();

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeoperacion("Menu->modificar 1: " . $base->getError());
            }
        } else {
            $this->setMensajeoperacion("Menu->modificar 2: " . $base->getError());
        }
        return $resp;
    }

    public function eliminar() {
        $resp = false;
        $base = new bdCarritoCompras();
        $sql = "DELETE FROM menu WHERE idmenu = " . $this->getIdmenu();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeoperacion("Menu->eliminar: " . $base->getError());
            }
        } else {
            $this->setMensajeoperacion("Menu->eliminar: " . $base->getError());
        }
        return $resp;
    }

    public static function listar($parametro = "") {
        $arreglo = array();
        $base = new bdCarritoCompras();
        $sql = "SELECT * FROM menu ";
        if ($parametro != "") {
            $sql .= 'WHERE ' . $parametro;
        }
        $res = $base->Ejecutar($sql);
        if ($res > 0) {
            while ($row = $base->Registro()) {
                $obj = new Menu();
                $objMenuPadre = null;
                if (!empty($row['idpadre'])) {
                    $objMenuPadre = new Menu();
                    $objMenuPadre->setIdmenu($row['idpadre']);
                    $objMenuPadre->cargar();
                }
                $obj->setear(
                    $row['idmenu'],
                    $row['menombre'],
                    $row['medescripcion'],
                    $row['meurl'],
                    $objMenuPadre,
                    $row['medeshabilitado']
                );
                array_push($arreglo, $obj);
            }
        }
        return $arreglo;
    }
}
?>
