// Formulario e inputs
const profile_form = document.getElementById('profile_form');
const nombre = document.getElementById('nombre');
const apellido = document.getElementById('apellido');
const email = document.getElementById('email');
const telefono = document.getElementById('telefono');
const fecha_nac = document.getElementById('fecha_nac');
const direccion = document.getElementById('direccion');
const password = document.getElementById('user_password');


// Definimos las funciones de validación
function validarNombre(nombre) {
    let regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{2,45}$/;
    return regex.test(nombre);
}

function validarApellido(apellido){
    let regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{2,60}$/;
    return regex.test(apellido);
}

function validarEmail(email) {
    let regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return regex.test(email);
}

function validarTel(telefono) {
    let regex = /^(6|7|8|9){1}[0-9]{8}$/;
    return regex.test(telefono);
}

function validarFechaNac(fecha_nac) {
    let fecha = new Date(fecha_nac);
    let hoy = new Date();
    let fechaMinima = new Date(hoy.getFullYear() - 120, hoy.getMonth(), hoy.getDate());
    
    if (fecha > hoy) {
        return;
    } else if (fecha < fechaMinima) {
        return;
    };

    let regex = /^[0-9]{4}-[0-9]{2}-[0-9]{2}$/;
    return regex.test(fecha_nac);
}

function validarDireccion(direccion) {
    let regex = /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\.\,\/\ª\º]{3,300}$/;
    return regex.test(direccion);
}

// Validación de contraseña solo si el usuario ingresa algo
function validarPassword(user_password) {
    if(user_password.trim() === ""){
        return true; // Si está vacío, no hay error
    }
    let regex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/;
    return regex.test(user_password);
}


// Definimos las funciones de validación que se ejecutarán al salir del input
function validateOnBlur(inputElement, validator){
    inputElement.addEventListener('blur', function(){
        let value = inputElement.value.trim();
        let valid = validator(value);
        let smallElement = inputElement.nextElementSibling;

        if(!valid){
            smallElement.textContent = "Error: El contenido introducido no es válido";
            smallElement.style.color = "red";
            smallElement.style.visibility = "visible";
        }else{
            smallElement.style.visibility = "hidden";
            smallElement.textContent = '';
        }
    });
}


// Prevenimos el envío del formulario si hay errores
profile_form.addEventListener('submit', function (event) {
    let isNombreValid = validarNombre(nombre.value);
    let isApellidoValid = validarApellido(apellido.value);
    let isEmailValid = validarEmail(email.value);
    let isTelValid = validarTel(telefono.value);
    let isFechaNacValid = validarFechaNac(fecha_nac.value);
    let isDireccionValid = validarDireccion(direccion.value);
    // Solo validar la contraseña si el usuario ha escrito algo
    let isPasswordValid = password.value.trim() === "" || validarPassword(password.value);

    if(!isNombreValid || !isApellidoValid || !isEmailValid || !isTelValid || !isFechaNacValid || !isDireccionValid || !isPasswordValid){
        // Prevenimos el envío del forumulario
        event.preventDefault();
    }
});

// Ejecutamos las funciones de validación en Blur
validateOnBlur(nombre, validarNombre);
validateOnBlur(apellido, validarApellido);
validateOnBlur(email, validarEmail);
validateOnBlur(telefono, validarTel);
validateOnBlur(fecha_nac, validarFechaNac);
validateOnBlur(direccion, validarDireccion);

// Validar contraseña solo si el usuario la escribe
password.addEventListener('blur', function(){
    if(password.value.trim() !== ""){
        validateOnBlur(password, validarPassword);
    }
});
