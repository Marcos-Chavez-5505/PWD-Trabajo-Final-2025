<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD/modelo/conector/BDautenticacion.php';

class Usuario {
    private $idUsuario;
    private $nombreUsuario;
    private $password;
    private $nombre;
    private $apellido;
    private $email;
    private $activo;
    private $idRol;
    private $objBaseDatos;

    public function __construct($objBaseDatos = null) {
        $this->idUsuario = null;
        $this->nombreUsuario = "";
        $this->password = "";
        $this->nombre = "";
        $this->apellido = "";
        $this->email = "";
        $this->activo = 1; // activo por defecto
        $this->idRol = null;
        $this->objBaseDatos = $objBaseDatos ?? new BDautenticacion(); // se crea si no existe
    }

    // Getters y Setters
    public function getIdUsuario() {return $this->idUsuario;}
    public function getNombreUsuario() {return $this->nombreUsuario;}
    public function getPassword() {return $this->password;}
    public function getNombre() {return $this->nombre;}
    public function getEmail() {return $this->email;}
    public function getApellido() {return $this->apellido;}
    public function getActivo() {return $this->activo;}
    public function getIdRol() {return $this->idRol;}

    public function setIdUsuario($idUsuario) {$this->idUsuario = $idUsuario;}
    public function setNombreUsuario($nombreUsuario) {$this->nombreUsuario = $nombreUsuario;}
    public function setPassword($password) {$this->password = $password;}
    public function setNombre($nombre) {$this->nombre = $nombre;}
    public function setApellido($apellido) {$this->apellido = $apellido;}
    public function setEmail($email) {$this->email = $email;}
    public function setActivo($activo) {$this->activo = $activo;}
    public function setIdRol($idRol) {$this->idRol = $idRol;}

   

    // Métodos CRUD usando 

    public function insertar() {
        $baseDatos = new BDautenticacion();
        $resultado = false;

        if ($baseDatos->Iniciar()) {
            $sql = "INSERT INTO usuario (nombreUsuario, password, nombre, apellido, email, activo, idRol)
                    VALUES ('{$this->nombreUsuario}', '{$this->password}', '{$this->nombre}', 
                            '{$this->apellido}', '{$this->email}', {$this->activo}, {$this->idRol})";

            $idInsertado = $baseDatos->Ejecutar($sql);

            if ($idInsertado != -1) {
                $this->idUsuario = $idInsertado;
                $resultado = true;
            } else {
                $baseDatos->setError('Error al insertar el usuario.');
            }
        } else {
            $baseDatos->setError('No se pudo conectar con la base de datos.');
        }

        return $resultado;
    }

    public function modificar() {
        $baseDatos = new BDautenticacion();
        $resultado = false;

        if ($baseDatos->Iniciar()) {
            $sql = "UPDATE usuario SET 
                        nombreUsuario = '{$this->nombreUsuario}',
                        password = '{$this->password}',
                        nombre = '{$this->nombre}',
                        apellido = '{$this->apellido}',
                        email = '{$this->email}',
                        activo = {$this->activo},
                        idRol = {$this->idRol}
                    WHERE idUsuario = {$this->idUsuario}";

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
            $sql = "UPDATE usuario SET activo = 0 WHERE idUsuario = {$this->idUsuario}";
            if ($baseDatos->Ejecutar($sql) >= 0) {
                $resultado = true;
            }
        }

        return $resultado;
    }

    public function buscar($idUsuario) {
        $baseDatos = new BDautenticacion();
        $resultado = false;

        if ($baseDatos->Iniciar()) {
            $sql = "SELECT * FROM usuario WHERE idUsuario = {$idUsuario}";

            if ($baseDatos->Ejecutar($sql) > 0) {
                $filaUsuario = $baseDatos->Registro();

                $this->idUsuario = $filaUsuario['idUsuario'];
                $this->nombreUsuario = $filaUsuario['nombreUsuario'];
                $this->password = $filaUsuario['password'];
                $this->nombre = $filaUsuario['nombre'];
                $this->apellido = $filaUsuario['apellido'];
                $this->email = $filaUsuario['email'];
                $this->activo = $filaUsuario['activo'];
                $this->idRol = $filaUsuario['idRol'];

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

        $sql .= " ORDER BY idUsuario";

        if ($baseDatos->Iniciar() && $baseDatos->Ejecutar($sql) > 0) {
            while ($filaUsuario = $baseDatos->Registro()) {
                $usuario = new Usuario();
                $usuario->buscar($filaUsuario['idUsuario']);
                array_push($listaUsuarios, $usuario);
            }
        }

        return $listaUsuarios;
    }
}
