<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
class Session{

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function iniciarSesion($usuario) {
        $_SESSION['idusuario'] = $usuario->getIdusuario();
        $_SESSION['usnombre'] = $usuario->getUsnombre();
        $_SESSION['activa'] = true;
        return true;
    }

    // public function validar() {
    //     $resultado = false;

    //     if (isset($_SESSION['idusuario'])) {

    //         include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/modelo/usuario.php';
    //         $user = new Usuario();

    //         if ($user->buscar($_SESSION['idusuario'])) {
    //             if ($user->getUsdeshabilitado() === null) {
    //                 $resultado = true;
    //             }
    //         }
    //     }

    //     return $resultado;
    // }
/**
 * Valida que el usuario esté logueado, no esté deshabilitado
 * y que posea el rol requerido.
 *
 * @param string|null $rolRequerido  Ej: 1 (administrador), 2 (cliente), etc.
 *                                   Si es null, solo valida login.
 */
    public function validar($rolRequerido = null) {
        // No hay sesión → redirigir
        if (!isset($_SESSION['idusuario'])) {
            header("Location: /PWD-TP-FINAL/Vista/public/cuenta.php");
            exit();
        }

        $user = new Usuario();

        // Usuario inexistente o deshabilitado
        if (!$user->buscar($_SESSION['idusuario']) || $user->getUsdeshabilitado() !== null){
            session_unset();
            session_destroy();
            header("Location: /PWD-TP-FINAL/Vista/public/cuenta.php");
            exit();
        }

        // Si se requiere un rol, verificarlo
        if ($rolRequerido !== null){
            $usrol = new UsuarioRol();
            $idrol = $usrol->rolDeUsuario($_SESSION['idusuario']);
            if ($idrol >= 0){    
                if ((int)$idrol !== (int)$rolRequerido){
                    // Usuario logueado pero sin permisos
                    header("Location: /PWD-TP-FINAL/Vista/public/cuenta.php");
                    exit();
                }
            }
        }
        return true;
    }

    /**
     * Solo verificasi la sesión está activa o no
     */
    public function activa() {
        $resultado = false;

        if (isset($_SESSION['activa']) && $_SESSION['activa'] === true) {
            $user = new Usuario();
            if ($user->buscar($_SESSION['idusuario'])) {
                if ($user->getUsdeshabilitado() === null) {
                    $resultado = true;
                }
            }
        }
        return $resultado;
    }

    public function getUsuario() {
        $usuario = null;

        if (isset($_SESSION['usnombre'])) {
            $usuario = $_SESSION['usnombre'];
        }

        return $usuario;
    }

    public function getIdUsuario() {
        $id = null;

        if (isset($_SESSION['idusuario'])) {
            $id = $_SESSION['idusuario'];
        }

        return $id;
    }

    public function getRol() {
        $resultado = null;

        if ($this->activa()) {

            $idUsuario = $_SESSION['idusuario'];
            $usuarioRol = new UsuarioRol();
            $listaRoles = $usuarioRol->listar("idusuario = {$idUsuario}");

            if (count($listaRoles) > 0) {
                $objUsuarioRol = $listaRoles[0];
                $rol = $objUsuarioRol->getObjRol();
                $resultado = $rol->getDescripcionRol();  // "Administrador" | "Cliente"
            }
        }

        return $resultado;
    }

    public function cerrar() {
        session_unset();
        session_destroy();
        $_SESSION = [];
        return true;
    }

    public static function getFlashMessage() {
        return $_SESSION['flash_msg'] ?? null;
    }

    public static function clearFlashMessage() {
        unset($_SESSION['flash_msg']);
    }

    public static function setFlashMessage($tipo, $texto) {
        $_SESSION['flash_msg'] = [
            'tipo'  => $tipo,
            'texto' => $texto
        ];
    }

    /** Este mensaje se una en action agregarProducto.php */
    public function mensajeIniciarSesion(){
        $respuesta = ['ok' => false, 'msg' => 'Debes iniciar sesión.'];
        return $respuesta;
    }

    /** Este mensaje se una en action cantidadCarrito.php */
    public function mensajeIniciarSesionCantidadCero(){
        $respuesta = ['ok' => false, 'cantidad' => 0];
        return $respuesta;
    }

    /** Se usa en inicioLinkHeader.php */
    public function linkLogoHeader(){
        $usuarioActivo = $this->activa();
        $nombreUsuario = $usuarioActivo ? $this->getUsuario() : null;
        $rolUsuario = $usuarioActivo ? $this->getRol() : null;

        $inicioLink = ($usuarioActivo && $rolUsuario === "Administrador") 
            ? "/PWD-TP-FINAL/Vista/admin/usuarios.php" 
            : "/PWD-TP-FINAL/Vista/public/index.php";

        $salida = [
            'usuarioActivo' => $usuarioActivo,
            'nombreUsuario' => $nombreUsuario,
            'rolUsuario' => $rolUsuario,
            'inicioLink' => $inicioLink
        ];
        return $salida;
    }
}
?>
