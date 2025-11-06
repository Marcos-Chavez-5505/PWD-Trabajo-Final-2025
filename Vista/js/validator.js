// validatorLogin.js - Validación de Login
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("loginForm");
    if (!form) return;

    const usuario = document.getElementById("usuario");
    const pass = document.getElementById("pass");

    // Funciones helper
    const markInvalid = (el) => el.classList.add("is-invalid");
    const markValid = (el) => el.classList.add("is-valid");
    const unmarkInvalid = (el) => el.classList.remove("is-invalid");
    const unmarkValid = (el) => el.classList.remove("is-valid");

    // Limpiar errores en tiempo real
    [usuario, pass].forEach(input => {
        input.addEventListener("input", () => {
            unmarkInvalid(input);
            unmarkValid(input);
        });
    });

    // Validar al enviar
    form.addEventListener("submit", function (event) {
        let isValid = true;

        if (!usuario.value.trim()) {
            markInvalid(usuario);
            isValid = false;
        } else {
            markValid(usuario);
        }

        if (!pass.value.trim()) {
            markInvalid(pass);
            isValid = false;
        } else {
            markValid(pass);
        }

        if (!isValid) {
            event.preventDefault();
            event.stopPropagation();
        }
    });
});
