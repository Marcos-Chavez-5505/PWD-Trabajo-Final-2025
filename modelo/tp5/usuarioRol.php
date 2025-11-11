<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/modelo/conector/BDautenticacion.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/modelo/tp5/usuario.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/modelo/tp5/rol.php';

class UsuarioRol {
    private $idusuario;
    private $idrol;
    private $objUsuario;
    private $objRol;
    private $objBaseDatos;

    public function __construct($objBaseDatos = null) {
        $this->idusuario = null;
        $this->idrol = null;
        $this->objUsuario = null;
        $this->objRol = null;
        $this->objBaseDatos = $objBaseDatos ?? new BDautenticacion();
    }

  // --- Getters ---
    public function getIdUsuario() { return $this->idusuario; }
    public function getIdRol() { return $this->idrol; }
    public function getObjUsuario() { return $this->objUsuario; }
    public function getObjRol() { return $this->objRol; }

    // --- Setters ---
    public function setIdUsuario($idusuario) { $this->idusuario = $idusuario; }
    public function setIdRol($idrol) { $this->idrol = $idrol; }
    public function setObjUsuario($objUsuario) { $this->objUsuario = $objUsuario; }
    public function setObjRol($objRol) { $this->objRol = $objRol; }

    // --- CRUD ---
    public function insertar() {
        $baseDatos = new BDautenticacion();
        $resultado = false;

        if ($baseDatos->Iniciar()) {
            $sql = "INSERT INTO usuariorol (idusuario, idrol)
                    VALUES (" . intval($this->idusuario) . ", " . intval($this->idrol) . ")";
            if ($baseDatos->Ejecutar($sql) >= 0) {
                $resultado = true;
            }
        }
        return $resultado;
    }

    public function eliminar() {
        $baseDatos = new BDautenticacion();
        $resultado = false;

        if ($baseDatos->Iniciar()) {
            $sql = "DELETE FROM usuariorol WHERE idusuario = " . intval($this->idusuario) . " AND idrol = " . intval($this->idrol);
            if ($baseDatos->Ejecutar($sql) >= 0) {
                $resultado = true;
            }
        }
        return $resultado;
    }

    public function listar($condicion = "") {
        $lista = [];
        $baseDatos = new BDautenticacion();
        $sql = "SELECT * FROM usuariorol";

        if ($condicion != "") {
            $sql .= " WHERE " . $condicion;
        }
        $sql .= " ORDER BY idusuario";

        if ($baseDatos->Iniciar() && $baseDatos->Ejecutar($sql) > 0) {
            while ($fila = $baseDatos->Registro()) {
                $obj = new UsuarioRol();
                $obj->setIdUsuario($fila['idusuario']);
                $obj->setIdRol($fila['idrol']);

                // Cargar objetos relacionados
                $objUsuario = new Usuario();
                $objUsuario->buscar($fila['idusuario']);
                $obj->setObjUsuario($objUsuario);

                $objRol = new Rol();
                $objRol->buscar($fila['idrol']);
                $obj->setObjRol($objRol);

                $lista[] = $obj;
            }
        }
        return $lista;
    }
}
?>
