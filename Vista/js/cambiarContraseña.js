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

document.getElementById('formCambiarContraseña').addEventListener('submit', async function(e) {
    e.preventDefault(); 

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
        alert(mensajeError);
    } else {
        cambiarContraseña();
    }
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

function mostrarModal(titulo, mensaje, esExitoso) {
    const modalElement = document.getElementById('modalResultado');
    const modalTitulo = document.getElementById('modalTitulo');
    const modalMensaje = document.getElementById('modalMensaje');
    
    modalTitulo.textContent = titulo;
    modalTitulo.className = `modal-title ${esExitoso ? 'text-success' : 'text-danger'}`;
    
    modalMensaje.innerHTML = `
        <div class="text-center">
            <i class="bi bi-${esExitoso ? 'check-circle-fill text-success' : 'exclamation-triangle-fill text-danger'}" style="font-size: 3rem;"></i>
            <p class="mt-3">${mensaje}</p>
        </div>
    `;
    
    const modal = new bootstrap.Modal(modalElement);
    modal.show();
    
    if (esExitoso) {
        modalElement.addEventListener('hidden.bs.modal', function() {
            document.getElementById('formCambiarContraseña').reset();
        }, { once: true });
    }
}
