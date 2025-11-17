<?php 
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/Vista/estructura/header.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cambiar Contraseña</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<style>
    body {
        background-color: #f8f9fa;
    }
    .card {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    #btnVolver:hover #txtVolver{
        color: white;
    }
</style>
</head>

<body class="bg-light">
<main class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="card shadow-sm p-4" style="max-width: 500px; width: 100%;">
        <div class="text-center mb-4">
            <i class="bi bi-shield-lock text-danger" style="font-size: 3rem;"></i>
            <h2 class="fw-bold mt-2">Cambiar Contraseña</h2>
            <p class="text-muted">Actualiza tu contraseña de acceso</p>
        </div>

        <form method="POST" name="formCambiarContraseña" id="formCambiarContraseña">
            <div class="mb-3">
                <label for="username" class="form-label fw-semibold">Nombre de usuario</label>
                <div class="input-group">
                    <span class="input-group-text bg-white">
                        <i class="bi bi-lock text-primary"></i>
                    </span>
                    <input type="text" class="form-control " id="username" name="username" required placeholder="Ingresa tu nombre de usuario">
                </div>
            </div>

            <div class="mb-3">
                <label for="currentPassword" class="form-label fw-semibold">Contraseña actual</label>
                <div class="input-group">
                    <span class="input-group-text bg-white">
                        <i class="bi bi-lock text-primary"></i>
                    </span>
                    <input type="password" class="form-control " id="currentPassword" name="currentPassword" required placeholder="Ingresa tu contraseña actual">
                    <button class="btn btn-outline-secondary" type="button" id="toggleCurrentPassword">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="newPassword" class="form-label fw-semibold">Nueva contraseña</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-lock text-primary"></i>
                    </span>
                    <input type="password" class="form-control" id="newPassword" name="newPassword" required placeholder="Ingresa tu nueva contraseña">
                    <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="confirmPassword" class="form-label fw-semibold">Confirmar nueva contraseña</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-lock text-primary"></i>
                    </span>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required placeholder="Confirma tu nueva contraseña">
                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button id="btnVolver" type="button" class="btn btn-outline-primary w-50 py-2">
                    <a id="txtVolver" href="pagina-anterior.html" class="text-decoration-none">
                        <i class="bi bi-arrow-left me-2"></i>Volver
                    </a>
                </button>
                <button type="submit" class="btn btn-primary w-50 py-2">
                    <i class="bi bi-check-circle me-2"></i>Actualizar
                </button>
            </div>
        </form>
    </div>
</main>

<div class="modal fade" id="modalResultado" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="modalTitulo">Resultado</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center py-4" id="modalMensaje">
        <!-- Mensaje dinámico -->
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>


    //!                                                                                      !\\
    //! FALTA AGREGAR QUE SOLO SE PUEDA CAMBIAR LA CONTRASEÑA DE LA CUENTA DE LA QUE ES DUEÑO !\\
    //!                                                                                      !\\





    document.getElementById('toggleCurrentPassword').addEventListener('click', function() {
        togglePasswordVisibility('currentPassword', this);
    });
    
    document.getElementById('toggleNewPassword').addEventListener('click', function() {
        togglePasswordVisibility('newPassword', this);
    });
    
    document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
        togglePasswordVisibility('confirmPassword', this);
    });
    
    function togglePasswordVisibility(inputId, button) {
        const passwordInput = document.getElementById(inputId);
        const icon = button.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }

    document.getElementById('formCambiarContraseña').addEventListener('submit', function(e) {
        let currentPassword = document.getElementById('currentPassword').value;
        let newPassword = document.getElementById('newPassword').value;
        let confirmPassword = document.getElementById('confirmPassword').value;
        
        let mensajeError = '';
        
        if (!currentPassword || !newPassword || !confirmPassword) {
            mensajeError = 'Por favor completá todos los campos';
        } else if (newPassword !== confirmPassword) {
            mensajeError = 'Las contraseñas nuevas no coinciden';
        } else if (newPassword === currentPassword) {
            mensajeError = 'La nueva contraseña debe ser diferente a la actual';
        }
        
        if (mensajeError) {
            e.preventDefault();
            alert(mensajeError);
        }else{
            cambiarContraseña();
        }
    })

    function mostrarModal(titulo, mensaje, esExitoso) {
        const modalElement = document.getElementById('modalResultado');
        
        const modalTitulo = document.getElementById('modalTitulo');
        const modalMensaje = document.getElementById('modalMensaje');
        
        modalTitulo.textContent = titulo;
        modalTitulo.className = `modal-title ${esExitoso ? 'text-success' : 'text-danger'}`;
        
        modalMensaje.innerHTML = `
            <div class="text-center">
                <i class="bi bi-${esExitoso ? 'check-circle-fill text-success' : 'exclamation-triangle-fill text-danger'}" 
                style="font-size: 3rem;"></i>
                <p class="mt-3">${mensaje}</p>
            </div>
        `;
        
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
        
        if (esExitoso) {
            modalElement.addEventListener('hidden.bs.modal', function() {
                document.getElementById('formCambiarContraseña').reset();
            }, { once: true }); // { once: true } para que solo se ejecute una vez
        }
    }

    //esto sirve para que no se reinicie el formulario enseguida
    document.getElementById('formCambiarContraseña').addEventListener('submit', async function(e) {
        e.preventDefault(); 
    });

    async function cambiarContraseña() {
        try {
            const username = document.getElementById('username').value;
            const currentPassword = document.getElementById('currentPassword').value;
            const newPassword = document.getElementById('newPassword').value;

            const formData = new FormData();
            formData.append('username', username); 
            formData.append('currentPassword', currentPassword);
            formData.append('newPassword', newPassword);

            const respuesta = await fetch('/PWD-TP-FINAL/Vista/private/action/user/cambiarContraseña.php', {
                method: 'POST',
                body: formData  
            });

            const data = await respuesta.json(); 

            if (data.ok) {
                mostrarModal('Éxito', data.msg || 'Contraseña cambiada correctamente', true);
            } else {
                mostrarModal('Error', data.msg || 'No se pudo cambiar la contraseña', false);
            }

        } catch (error) {
            console.error('Error:', error);
            mostrarModal('Error', 'Hubo un problema de conexión', false);
        }
    }
</script>
</body>
</html>
<?php 
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/Vista/estructura/footer.php";
?>