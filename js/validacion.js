document.addEventListener("DOMContentLoaded", () => {
    const formulario = document.getElementById("formulario-registro");
    if (!formulario) return;

    formulario.addEventListener("submit", (event) => {
        limpiarErrores();
        let formularioValido = true;

        const nombreInput = document.getElementById("nombre");
        const nombreValor = nombreInput.value.trim();
        if (nombreValor === "") {
            mostrarError(nombreInput, "error-nombre", "El campo Nombre Completo es obligatorio.");
            formularioValido = false;
        } else if (nombreValor.length < 3) {
            mostrarError(nombreInput, "error-nombre", "El nombre debe contener al menos 3 caracteres.");
            formularioValido = false;
        } else {
            marcarValido(nombreInput);
        }

        const emailInput = document.getElementById("email");
        const emailValor = emailInput.value.trim();
        const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (emailValor === "") {
            mostrarError(emailInput, "error-email", "El correo electronico es obligatorio.");
            formularioValido = false;
        } else if (!regexEmail.test(emailValor)) {
            mostrarError(emailInput, "error-email", "El formato de correo ingresado no es valido.");
            formularioValido = false;
        } else {
            marcarValido(emailInput);
        }

        const perfilSelect = document.getElementById("perfil");
        if (perfilSelect.value === "") {
            mostrarError(perfilSelect, "error-perfil", "Debe seleccionar un perfil de acceso.");
            formularioValido = false;
        } else {
            marcarValido(perfilSelect);
        }

        const radiosNotificaciones = document.getElementsByName("notificaciones");
        let radioSeleccionado = false;
        for (const radio of radiosNotificaciones) {
            if (radio.checked) {
                radioSeleccionado = true;
                break;
            }
        }
        if (!radioSeleccionado) {
            mostrarError(null, "error-notificaciones", "Seleccione una frecuencia para las notificaciones.");
            formularioValido = false;
        }

        const terminosCheckbox = document.getElementById("terminos");
        if (!terminosCheckbox.checked) {
            mostrarError(terminosCheckbox, "error-terminos", "Debe aceptar los terminos para continuar.");
            formularioValido = false;
        } else {
            marcarValido(terminosCheckbox);
        }

        if (!formularioValido) {
            event.preventDefault();
            const mensajeGlobal = document.getElementById("mensaje-global");
            mensajeGlobal.className = "alert-danger mb-3";
            mensajeGlobal.textContent = "No se pudo procesar el formulario. Por favor, corrija los campos marcados.";
        }
    });

    function mostrarError(inputElement, idErrorDiv, textoMensaje) {
        if (inputElement) inputElement.classList.add("is-invalid");
        document.getElementById(idErrorDiv).textContent = textoMensaje;
    }

    function marcarValido(inputElement) {
        inputElement.classList.remove("is-invalid");
        inputElement.classList.add("is-valid");
    }

    function limpiarErrores() {
        const inputs = formulario.querySelectorAll(".form-control, .form-select");
        inputs.forEach(input => input.classList.remove("is-invalid", "is-valid"));
        const divsError = formulario.querySelectorAll("[id^='error-']");
        divsError.forEach(div => div.textContent = "");
        const mensajeGlobal = document.getElementById("mensaje-global");
        mensajeGlobal.className = "alert-danger d-none";
    }
});
