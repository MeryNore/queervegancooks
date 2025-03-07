<!-- Validación Noticias -->

<?php

# Declaramos como constantes las expresiones regulares que van a filtrar o comprobar los datos
define('TITULO_REGEX', '/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\.\,\;\/\%\-\¿\?]{3,200}$/');
define('TEXTO_REGEX', '/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\.\,\;\/\%\-\¿\?]{3,}$/');
define('FECHA_REGEX', '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/');

                      
# Definimos la función que va a validar los datos del formulario de citas
function validar_noticias($titulo_noticia, $texto_noticia, $fecha_noticia){
    # Declarar un array asociativo que va a contener los errores de validación
    $errores = [];


    if (!preg_match(TITULO_REGEX, $titulo_noticia)) {
        $errores['titulo'] = '- Intenta no escribir caracteres especiales';
    }

    if (!preg_match(TEXTO_REGEX, $texto_noticia)) {
        $errores['texto'] = '- Intenta no escribir caracteres especiales';
    }

    if(!preg_match(FECHA_REGEX, $fecha_noticia)){
        $errores['fecha'] = '- Formato de fecha incorrecto';
    }


      
    return $errores;
    
}

?>