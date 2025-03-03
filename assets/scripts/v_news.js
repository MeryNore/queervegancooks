// Formulario e inputs
const form_news = document.getElementById('form_news');
const titulo_noticia = document.getElementById('titulo_noticia');
const texto_noticia = document.getElementById('texto_noticia');
const fecha_noticia = document.getElementById('fecha_noticia');


function validarTitulo(titulo_noticia) {
    let regex = /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\.\,\;\/\%\-\¿\?]{1,200}$/;
    return regex.test(titulo_noticia);
}

function validarTexto(texto_noticia) {
    let regex = /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\.\,\;\/\%\-\¿\?]{1,}$/;
    return regex.test(texto_noticia);
}

function validarFecha(fecha_noticia) {
    let regex = /^[0-9]{4}-[0-9]{2}-[0-9]{2}$/;
    return regex.test(fecha_noticia);
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
form_news.addEventListener('submit', function (event) {
    let isTituloValid = validarTitulo(titulo_noticia.value);
    let isTextoValid = validarTexto(texto_noticia.value);
    let isFechaValid = validarFecha(fecha_noticia.value);

    if(!isTituloValid || !isTextoValid || !isFechaValid){
        // Prevenimos el envío del forumulario
        event.preventDefault();
    }
});

// Ejecutamos las funciones de validación en Blur
validateOnBlur(titulo_noticia, validarTitulo);
validateOnBlur(texto_noticia, validarTexto);
validateOnBlur(fecha_noticia, validarFecha);
