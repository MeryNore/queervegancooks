// datos del formulario y del botón de envío
const form = document.getElementById('form');
const boton = document.getElementById('sendButton')

boton.addEventListener('click', (e) => {
    
    //prevenimos que se envíe el formulario
    e.preventDefault();
    
    validar();
});

function validar () {

    //contro de envío de formulario y mensaje
    let valido = true ;
    let mensaje = "";

    
    //validaciones
    const nombre = document.getElementById('nombre').value;
    if(!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{2,15}$/.test(nombre)){
        valido = false;
        mensaje = mensaje + 'El nombre sólo puede contener letras y máximo 15 caracteres\n';
    };

    const apellido = document.getElementById('apellido').value;
    if(!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{2,40}$/.test(apellido)){
        valido = false;
        mensaje = mensaje + 'El apellido sólo puede contener letras y máximo 40 caracteres\n';
    };

    const telefono = document.getElementById('telefono').value;
    if (!/^(6|7|8|9){1}[0-9]{8}$/.test(telefono)){
        valido = false;
        mensaje = mensaje + 'El telefono sólo puede contener números y máximo 9 digitos\n';
    };
    
    const email = document.getElementById('email').value;
    if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,5}$/.test(email)){
        valido = false;
        mensaje = mensaje + 'El campo de email debe ser en formato algo@algo.algo\n';
    };

    const chekcboxk = document.getElementById('acepto');
    if (!chekcboxk.checked){
        valido = false;
        mensaje = mensaje + "Debe aceptar la POLÍTICA DE PRIVACIDAD\n"
    };

    const comensales = document.getElementById('comensales').value;
    if(comensales <= parseInt(1)){
        valido = false;
        mensaje = mensaje + "La candidad mínima de comensales son dos persona"
    }

    //si todo está ok, enviará el formulario
    if (valido){
        form.submit();
        alert("formulario enviado correctamente");
    }else{
        alert("Han ocurrido los siguientes errores:\n" + mensaje);
    }
};