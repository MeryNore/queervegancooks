<!-- Validación Noticias -->

<?php

# Declaramos como constantes las expresiones regulares que van a filtrar o comprobar los datos
define('TITULO_REGEX', '/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\.\,\;\/\%\-\¿\?]{3,200}$/');
define('TEXTO_REGEX', '/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\.\,\;\/\%\-\¿\?]{3,}$/');
define('FECHA_REGEX', '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/');

                      
# Definimos la función que va a validar los datos del formulario de citas
function validar_noticias($titulo_noticia, $texto_noticia, $fecha_noticia, $foto, $imageFileType){
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


    // COMPROBACIONES DE LA FOTO SUBIDA

    # Comprobar si el archivo es una imagen real
    if(!empty($foto["tmp_name"])){
        $check = getimagesize($foto["tmp_name"]);
        if($check == false) {
            $errores['imagen'] = "El archivo no es una imagen.";
        }
    }else{
        $errores['imagen'] = "No se ha subido ninguna imagen.";
    }
    

    # Comprobar el tamaño del archivo
    if ($foto["size"] > 60000000) { //60MB
        $errores['imagen'] = "Lo siento, tu archivo es demasiado grande.";
    }

    # Permitir ciertos formatos de archivo
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ){
        $errores['imagen'] = "Lo siento, solo se permiten archivos JPG, JPEG, PNG y GIF.";
    }


    
    return $errores;
    
}

?>