<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/modelo/usuario.php';

class ControlUsuario {

    public function crearUsuario($datos) {
        $usuario = new Usuario();
        $usuario->setUsnombre($datos['usnombre']);
        $usuario->setUspass($datos['uspass']);
        $usuario->setUsmail($datos['usmail']);
        $usuario->setUsdeshabilitado(null);
        $resultado = $usuario->insertar();
        return $resultado;
    }

    public function listarUsuarios($condicion = "") {
        $usuario = new Usuario();
        $resultado = $usuario->listar($condicion);
        return $resultado;
    }

    public function buscarUsuario($idUsuario) {
        $usuario = new Usuario();
        $resultado = null;
        if ($usuario->buscar($idUsuario)) {
            $resultado = $usuario;
        }
        return $resultado;
    }

    public function modificarUsuario($datos) {
        $usuario = new Usuario();
        $resultado = false;

        if ($usuario->buscar($datos['idusuario'])) {
            $usuario->setUsnombre($datos['usnombre']);
            $usuario->setUspass($datos['uspass']);
            $usuario->setUsmail($datos['usmail']);
            $usuario->setUsdeshabilitado($datos['usdeshabilitado'] ?? null);

            $resultado = $usuario->modificar();

            if (isset($datos['idrol'])) {
                include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/modelo/usuarioRol.php';
                include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/modelo/rol.php';

                $idUsuario = intval($datos['idusuario']);
                $idRol = intval($datos['idrol']);

                $usuarioRol = new UsuarioRol();
                $rol = new Rol();
                $rol->buscar($idRol);

                $lista = $usuarioRol->listar("idusuario = {$idUsuario}");

                if (count($lista) > 0) {
                    $sql = "UPDATE usuariorol SET idrol = {$idRol} WHERE idusuario = {$idUsuario}";
                    $usuarioRol->getObjPdo()->Ejecutar($sql);
                } else {
                    $usuarioRol->setIdusuario($idUsuario);
                    $usuarioRol->setIdrol($idRol);
                    $usuarioRol->insertar();
                }
            }
        }

        return $resultado;
    }


    public function eliminarUsuario($idUsuario) {
        $usuario = new Usuario();
        $resultado = false;
        if ($usuario->buscar($idUsuario)) {
            $resultado = $usuario->borradoLogico();
        }
        return $resultado;
    }

   public function autenticar($nombreUsuario, $password) {
        $usuario = new Usuario();
        $resultado = null;

        $lista = $usuario->listar("usnombre = '$nombreUsuario'");

        if (count($lista) > 0) {
            $usuario = $lista[0];
            if ($usuario->getUspass() === $password) {
                $resultado = $usuario;
            }
        }

        return $resultado;
    }

    public function cambiarContraseña($nombreUsuario, $passwordActual, $nuevaPassword) {
        $usuarioAutenticado = $this->autenticar($nombreUsuario, $passwordActual);
        $retorno = false;

        if ($usuarioAutenticado) {
            // $usuarioAutenticado ya es un objeto Usuario con todos los datos cargados
            $usuarioAutenticado->setUspass($nuevaPassword);
            $retorno = $usuarioAutenticado->modificar();
        }
        
        return $retorno;
    }

    /** Se usa en cambiarContraseña.php */
    public function cambiarPassDesdeAction($session, $data) {

        if (!$session->activa()) {
            return ['ok' => false, 'msg' => 'Debes iniciar sesión.'];
        }

        $usuario = $session->getUsuario();

        if (!isset($data['username'], $data['currentPassword'], $data['newPassword'])) {
            return ['ok' => false, 'msg' => 'Datos incompletos.'];
        }

        if ($data['username'] !== $usuario) {
            return ['ok' => false, 'msg' => 'No tienes permisos para modificar esta cuenta.'];
        }

        $ok = $this->cambiarContraseña(
            $data['username'],
            $data['currentPassword'],
            $data['newPassword']
        );

        return [
            'ok' => $ok,
            'msg' => $ok
                ? 'Contraseña modificada correctamente.'
                : 'La contraseña actual no coincide.'
        ];
    }

    public function obtenerDatosParaVista(Usuario $usuario) {
        $datosVista = [
            'idusuario' => $usuario->getIdusuario(),
            'usnombre' => $usuario->getUsnombre(),
            'uspass' => $usuario->getUspass(), 
            'usmail' => $usuario->getUsmail(),
            'usdeshabilitado' => $usuario->getUsdeshabilitado()
        ];
        return $datosVista;
    }


    public function manejarEdicion($data){
        $respuesta = [
            'tipo' => null,
            'destino' => null,
            'usuario' => null,
            'roles' => null,
            'rolActual' => null,
            'datosVista' => null
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            // Validación inicial
            if (!isset($data['idUsuario'])) {
                $respuesta['tipo'] = 'redirect';
                $respuesta['destino'] = '/PWD-TP-FINAL/Vista/admin/usuarios.php?error=' . urlencode('ID inválido');
                return $respuesta;
            }

            $idUsuario = intval($data['idUsuario']);
            $usuario = $this->buscarUsuario($idUsuario);

            if (!$usuario) {
                $respuesta['tipo'] = 'redirect';
                $respuesta['destino'] = '/PWD-TP-FINAL/Vista/admin/usuarios.php?error=' . urlencode('Usuario no encontrado');
                return $respuesta;
            }

            // Si llegó hasta acá, hay que cargar la vista
            $controlRol = new ControlRol();
            $controlUsuarioRol = new ControlUsuarioRol();
            $roles = $controlRol->listarRoles();
            $rolActual = $controlUsuarioRol->listarUsuarios($idUsuario); // arreglo con roles asociados al usuario (solo asumimos que 1)
            $rolActual = $rolActual[0]->getIdrol();

            $respuesta['tipo'] = 'vista';
            $respuesta['usuario'] = $usuario;
            $respuesta['roles'] = $roles;
            $respuesta['rolActual'] = $rolActual;
            $respuesta['datosVista'] = $this->obtenerDatosParaVista($usuario);
        
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $datos = [
                'idusuario' => intval($data['idUsuario']),
                'usnombre' => trim($data['usnombre']),
                'uspass' => trim($data['uspass']),
                'usmail' => trim($data['usmail']),
                'usdeshabilitado' => isset($data['usdeshabilitado']) ? null : date('Y-m-d H:i:s'),
                'idrol' => intval($data['idrol'])
            ];

            $resultado = $this->modificarUsuario($datos);

            $respuesta['tipo'] = 'redirect';
            $respuesta['destino'] = $resultado
                ? '/PWD-TP-FINAL/Vista/admin/usuarios.php?exito=' . urlencode('Usuario actualizado')
                : '/PWD-TP-FINAL/Vista/admin/usuarios.php?error=' . urlencode('No se pudo actualizar');
        }

        return $respuesta;
    }

    public function loginDesdeAction($data) {
        $redirect = '/PWD-TP-FINAL/Vista/public/cuenta.php?err=401';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $redirect = '/PWD-TP-FINAL/Vista/public/cuenta.php?errLogin=1';
            
            $nombre = $data['nombreUsuario'] ?? '';
            $pass = $data['password'] ?? '';
            $usuario = $this->autenticar($nombre, $pass);
            
            if ($usuario) {
                $redirect = '/PWD-TP-FINAL/Vista/public/cuenta.php?errLogin=deshabilitado';
                
                if ($usuario->getUsdeshabilitado() === null) {
                    $session = new Session();
                    $session->iniciarSesion($usuario);
                    $rol = $this->obtenerRolUsuario($usuario);
                    
                    $redirect = ($rol === 'administrador')
                        ? '/PWD-TP-FINAL/Vista/admin/usuarios.php'
                        : '/PWD-TP-FINAL/Vista/public/index.php';
                }
            }
        }
        
        return ['redirect' => $redirect];
    }

    private function obtenerRolUsuario($usuario) {
        $ctrlRol = new ControlUsuarioRol();
        $roles = $ctrlRol->listarUsuarios($usuario->getIdusuario());

        return strtolower($roles[0]->getObjRol()->getDescripcionRol() ?? 'cliente');
    }

    public function eliminarUsuarioDesdeAction($idUsuario) {
        $respuesta = ['redirect' => '/PWD-TP-FINAL/Vista/admin/usuarios.php?error=ID de usuario no especificado'];
        
        if ($idUsuario) {
            $ok = $this->eliminarUsuario((int)$idUsuario);
    
            $param = $ok
                ? 'exito=Usuario eliminado correctamente'
                : 'error=No se pudo eliminar el usuario';

            $respuesta = ['redirect' => "/PWD-TP-FINAL/Vista/admin/usuarios.php?$param"];
        }

        return $respuesta;
    }

}
?>