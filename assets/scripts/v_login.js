// Formulario e inputs
const login_form = document.getElementById('login_form');
const user_email = document.getElementById('user_email');
const user_password = document.getElementById('user_password');

// Definimos las funciones de validación
function validarUser(user_email) {
    let regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return regex.test(user_email);
}

function validarPassword(user_password) {
    let regex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/;
    return regex.test(user_password);
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

// Prevenimos el envío del formulario si hay errores
login_form.addEventListener('submit', function (event) {
    let isEmailValid = validarUser(user_email.value);
    let isPasswordValid = validarPassword(user_password.value);

    if(!isEmailValid || !isPasswordValid){
        // Prevenimos el envío del forumulario
        event.preventDefault();
    }
});

// Ejecutamos las funciones
validateOnBlur(user_email, validarUser);
validateOnBlur(user_password, validarPassword);