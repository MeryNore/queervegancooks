// Formulario e inputs
const form = document.getElementById('form');
const nombre = document.getElementById('nombre');
const apellido = document.getElementById('apellido');
const email = document.getElementById('email');
const telefono = document.getElementById('telefono');
const fecha_nac = document.getElementById('fecha_nac');
const direccion = document.getElementById('direccion');
const password = document.getElementById('user_password');
//Checkbox política de privacidad
const checkboxPriv = document.getElementById('acepto');


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

function validarPassword(user_password) {
    let regex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/;
    return regex.test(user_password);
}

//Checkbox política de privacidad
function validarcheckboxPriv(checkboxPriv) {
   return checkboxPriv.checked;
}


// Definimos las funciones de validación que se ejecutarán al salir del input
function validateOnBlur(inputElement, validator){
    inputElement.addEventListener('blur', function(){
        let value = inputElement.value;
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

// Definimos las funciones de validación que se ejecutarán al hacer click en el checkbox
function validateClick(inputElement, validator){
    inputElement.addEventListener('click', function(){
        let value = inputElement.checked;
        let valid = validator(inputElement);
        let smallElement = inputElement.nextElementSibling;

        if(!valid){
            smallElement.textContent = "Debe aceptar la política de privacidad";
            smallElement.style.color = "red";
            smallElement.style.visibility = "visible";
        }else{
            smallElement.style.visibility = "hidden";
            smallElement.textContent = '';
        }
    });
}



// Prevenimos el envío del formulario si hay errores
form.addEventListener('submit', function (event) {
    let isNombreValid = validarNombre(nombre.value);
    let isApellidoValid = validarApellido(apellido.value);
    let isEmailValid = validarEmail(email.value);
    let isTelValid = validarTel(telefono.value);
    let isFechaNacValid = validarFechaNac(fecha_nac.value);
    let isDireccionValid = validarDireccion(direccion.value);
    let isPasswordValid = validarPassword(password.value);
    let isCheckboxValid = validarcheckboxPriv(checkboxPriv);

    if(!isNombreValid || !isApellidoValid || !isEmailValid || !isTelValid || !isFechaNacValid || !isDireccionValid || !isPasswordValid || !isCheckboxValid){
        // Prevenimos el envío del forumulario
        event.preventDefault();
        // Enviamos mensaje de error si no se ha marchado la política de privacidad
        if(!isCheckboxValid){
            let smallElement = checkboxPriv.nextElementSibling;
            smallElement.textContent = "Debe aceptar la política de privacidad";
            smallElement.style.color = "red";
            smallElement.style.visibility = "visible";
        }else{
            smallElement.style.visibility = "hidden";
            smallElement.textContent = '';
        }
    }
});

// Ejecutamos las funciones
validateOnBlur(nombre, validarNombre);
validateOnBlur(apellido, validarApellido);
validateOnBlur(email, validarEmail);
validateOnBlur(telefono, validarTel);
validateOnBlur(fecha_nac, validarFechaNac);
validateOnBlur(direccion, validarDireccion);
validateOnBlur(password, validarPassword);
validateClick(checkboxPriv, validarcheckboxPriv);