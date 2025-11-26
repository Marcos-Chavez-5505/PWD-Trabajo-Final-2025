<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
class AuthMiddleware {

    public static function requireRole($rolesPermitidos = []) {

        // Si no hace falta validar rol → permitir
        if (empty($rolesPermitidos)) {
            return;
        }

        // Usuario no autenticado
        if (!isset($_SESSION['idusuario'])) {
            header("Location: /PWD-TP-FINAL/Vista/public/cuenta.php?err=401");
            exit();
        }

        $user = new Usuario();

        if (!$user->buscar($_SESSION['idusuario']) || $user->getUsdeshabilitado() !== null) {
            session_unset();
            session_destroy();
            header("Location: /PWD-TP-FINAL/Vista/public/cuenta.php?err=423");
            exit();
        }



        
        $usrol = new UsuarioRol();
        $rolUsuario = (int) $usrol->rolDeUsuario($_SESSION['idusuario']);

        // Compara contra un array de roles permitidos
        if (!in_array($rolUsuario, $rolesPermitidos, true)) {
            header("Location: /PWD-TP-FINAL/Vista/public/cuenta.php?err=403");
            exit();
        }
    }
}
?>