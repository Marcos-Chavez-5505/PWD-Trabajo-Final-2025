<?php
//iniciamos la clase
class Session{
    //implementamos el metodo constructor
    public function __construct(){
        if (session_status() === PHP_SESSION_NONE);
        session_start(); //creara una sesion si no estraba previamente iniciada
    }

    //implementamos la funcion iniciar
    //en $_SESSION se guardaran los datos del usuario que se logueo
    public function iniciar($nombreUsuario, $constraseña){
        $_SESSION['usuario'] = $nombreUsuario;
        $_SESSION['constraseña'] = $constraseña;
        $_SESSION['activa'] = true; //con true marcamos la sesion como activa
    }

    //implementamos la funcion de validar
    //se verifica qur las variables de la sesion existan y que conincidadn con un usuario valido en la base 
    public function validar(){
        $valido = false;

        if(isset($_SESSION['usuario']) && isset($_SESSION['pwd'])){
            include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD/modelo/tp5/usuario.php';
            $usuario = new Usuario();

            //se busca al usuario por el nombre
            $lista = $usuario->listar("nombreUsuario = '{$_SESSION['usuario']}'");

            if(count($lista) > 0){
                $objUsuario = $lista[0];
                //se verifica que la contraseña
                if($objUsuario->getPassword() === $_SESSION['contraseña']){
                    $valido = true;
                }
            }
        }

        return $valido;
    }

    //implementamos la funcion activa
    //devolvera true si la sesion esta activa, en caso contrario devolvera false
    public function activa(){
        $activa = false;
        if(isset($_SESSION['activa']) && $_SESSION['activa'] === true){
            $activa = true;
        }
        return $activa;
    }

    //implementamos el funcion getUsuario()
    //que nos devolvera el nombre del usuario logeado
    public function getUsuario(){
        $usuario = null;
        if(isset($_SESSION['usuario'])){
            $usuario = $_SESSION['usuario'];
        }
        return $usuario;
    }

    //implementamos el funcion getRol()
    //que nos devolvera el rol del usuario logeado y que tambien este diponible
    public function getRol(){
        $rol = null;

        if($this->validar()){
            include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD/modelo/tp5/usuario.php';
            $usuario = new Usuario();
            $lista = $usuario->listar("nombreUsuario = '{$_SESSION['usuario']}'");

            if(count($lista) > 0) {
                $objUsuario = $lista[0];
                $rol = $objUsuario->getIdRol();
            }
        }

        return $rol;
    }

    //implementamos la funcion cerrar
    //que va a destruir la sesion actual
    public function cerrar(){
        session_unset();//limpia las variables
        session_destroy();//destruye la sesion
        $_SESSION = [];
    }
}
?>