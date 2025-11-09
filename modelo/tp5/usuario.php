<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/modelo/conector/BDautenticacion.php';

class Usuario {
    private $idusuario;
    private $usnombre;
    private $uspass;
    private $usmail;
    private $usdeshabilitado;
    private $objBaseDatos;

    public function __construct($objBaseDatos = null) {
        $this->idusuario = null;
        $this->usnombre = "";
        $this->uspass = "";
        $this->usmail = "";
        $this->usdeshabilitado = null;
        $this->objBaseDatos = $objBaseDatos ?? new BDautenticacion();
    }

    public function getIdusuario() { return $this->idusuario; }
    public function getUsnombre() { return $this->usnombre; }
    public function getUspass() { return $this->uspass; }
    public function getUsmail() { return $this->usmail; }
    public function getUsdeshabilitado() { return $this->usdeshabilitado; }

    public function setIdusuario($idusuario) { $this->idusuario = $idusuario; }
    public function setUsnombre($usnombre) { $this->usnombre = $usnombre; }
    public function setUspass($uspass) { $this->uspass = $uspass; }
    public function setUsmail($usmail) { $this->usmail = $usmail; }
    public function setUsdeshabilitado($usdeshabilitado) { $this->usdeshabilitado = $usdeshabilitado; }

    public function insertar() {
        $baseDatos = new BDautenticacion();
        $resultado = false;
        if ($baseDatos->Iniciar()) {
            $sql = "INSERT INTO usuario (usnombre, uspass, usmail, usdeshabilitado)
                    VALUES ('{$this->usnombre}', '{$this->uspass}', '{$this->usmail}', NULL)";
            $idInsertado = $baseDatos->Ejecutar($sql);
            if ($idInsertado != -1) {
                $this->idusuario = $idInsertado;
                $resultado = true;
            }
        }
        return $resultado;
    }

    public function modificar() {
        $baseDatos = new BDautenticacion();
        $resultado = false;
        if ($baseDatos->Iniciar()) {
            $sql = "UPDATE usuario SET 
                        usnombre = '{$this->usnombre}',
                        uspass = '{$this->uspass}',
                        usmail = '{$this->usmail}',
                        usdeshabilitado = " . ($this->usdeshabilitado ? "'{$this->usdeshabilitado}'" : "NULL") . "
                    WHERE idusuario = {$this->idusuario}";
            if ($baseDatos->Ejecutar($sql) >= 0) {
                $resultado = true;
            }
        }
        return $resultado;
    }

    public function borradoLogico() {
        $baseDatos = new BDautenticacion();
        $resultado = false;
        if ($baseDatos->Iniciar()) {
            $sql = "UPDATE usuario SET usdeshabilitado = CURRENT_TIMESTAMP WHERE idusuario = {$this->idusuario}";
            if ($baseDatos->Ejecutar($sql) >= 0) {
                $resultado = true;
            }
        }
        return $resultado;
    }

    public function buscar($idusuario) {
        $baseDatos = new BDautenticacion();
        $resultado = false;
        if ($baseDatos->Iniciar()) {
            $sql = "SELECT * FROM usuario WHERE idusuario = {$idusuario}";
            if ($baseDatos->Ejecutar($sql) > 0) {
                $fila = $baseDatos->Registro();
                $this->idusuario = $fila['idusuario'];
                $this->usnombre = $fila['usnombre'];
                $this->uspass = $fila['uspass'];
                $this->usmail = $fila['usmail'];
                $this->usdeshabilitado = $fila['usdeshabilitado'];
                $resultado = true;
            }
        }
        return $resultado;
    }

    public function listar($condicion = "") {
        $listaUsuarios = [];
        $baseDatos = new BDautenticacion();
        $sql = "SELECT * FROM usuario";

        if ($condicion != "") {
            $sql .= " WHERE " . $condicion;
        }

        if ($baseDatos->Iniciar() && $baseDatos->Ejecutar($sql) > 0) {
            while ($filaUsuario = $baseDatos->Registro()) {
                $usuario = new Usuario();
                $usuario->buscar($filaUsuario['idusuario']);
                $listaUsuarios[] = $usuario;
            }
        }

        return $listaUsuarios;
    }

}
?>
