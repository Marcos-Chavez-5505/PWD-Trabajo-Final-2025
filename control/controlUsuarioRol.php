<?php
class controlUsuarioRol {
    const ADMIN = 1;
    const CLIENTE = 2;

    public function listarUsuarios($idUsuario){
        $usuarioRol = new UsuarioRol();
        $listaRoles = $usuarioRol->listar("idusuario = " . $idUsuario);

        return $listaRoles;
    }
}
?>
