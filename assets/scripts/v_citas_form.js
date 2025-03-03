// Formulario e inputs
const citas_form = document.getElementById('citas_form');
const fecha_cita = document.getElementById('fecha_cita');
const motivo_cita = document.getElementById('motivo_cita');


// Definimos las funciones de validación
function validarFechaCita(fecha_cita) {
    let fecha = new Date(fecha_cita);
    let hoy = new Date();
    let fechaMaxima = new Date(hoy.getFullYear() + 1, hoy.getMonth(), hoy.getDate());

    if (fecha <= hoy) {
        return;
    } else if (fecha > fechaMaxima) {
        return;
    };

    let regex = /^[0-9]{4}-[0-9]{2}-[0-9]{2}$/;
    return regex.test(fecha_cita);
}   

// Validación este campo solo si el usuario ingresa datos
function validarMotivoCita(motivo_cita) {
    if(motivo_cita.trim() === ""){
        return true; // Si está vacío, no hay error
    }
    let regex = /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\.\,\/]{1,300}$/;
    return regex.test(motivo_cita);
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
citas_form.addEventListener('submit', function (event) {
    let isFechaCitaValid = validarFechaCita(fecha_cita.value);
    // Solo validar la contraseña si el usuario ha escrito algo
    let isMotivoCitaValid = motivo_cita.value.trim() === "" || validarMotivoCita(motivo_cita.value);

    if(!isFechaCitaValid || !isMotivoCitaValid){
        // Prevenimos el envío del forumulario
        event.preventDefault();
    }
});

// Ejecutamos las funciones de validación en Blur
validateOnBlur(fecha_cita, validarFechaCita);

// Validar solo si el usuario la escribe
motivo_cita.addEventListener('blur', function(){
    if(motivo_cita.value.trim() !== ""){
        validateOnBlur(motivo_cita, validarMotivoCita);
    }
});
