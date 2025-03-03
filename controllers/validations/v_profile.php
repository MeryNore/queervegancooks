<!-- Validación registro -->

<?php

# Declaramos como constantes las expresiones regulares que van a filtrar o comprobar los datos
define('NOMBRE_REGEX', '/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{3,45}$/');
define('APELLIDO_REGEX', '/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{3,60}$/');
define('TELEFONO_REGEX', '/^[0-9]{9}$/');
define('FECHA_NAC_REGEX', '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/');
define('DIRECCION_REGEX', '/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\.\,\/\ª\º]{3,300}$/');
define('PASSWORD_REGEX', '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/');
                      
# Definimos la función que va a validar los datos del formulario de registro
function validar_profile($nombre, $apellido, $email, $telefono, $fecha_nac, $direccion, $new_pass){
    # Declarar un array asociativo que va a contener los errores de validación
    $errores = [];

    # Validación de variables haciendo uso de la constante REGEX_
    if(!preg_match(NOMBRE_REGEX, $nombre)){
        $errores['nombre'] = '- Nombre: Escriba sólo letras y máximo 45 caracteres';
    }

    if(!preg_match(APELLIDO_REGEX, $apellido)){
        $errores['apellido'] = '- Apellido: Escriba sólo letras y máximo 60 caracteres';
    }

    # Validación de email, tiene su propia validación en PHP, haremos uso para no crear las nuestras propias
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errores['email'] = '- Email: Formato de email incorrecto';
    }

    if(!preg_match(TELEFONO_REGEX, $telefono)){
        $errores['telefono'] = '- Teléfono: Escriba sólo números y máximo 9 digitos';
    }

    if(!preg_match(FECHA_NAC_REGEX, $fecha_nac)){
        $errores['fecha_nac'] = '- Fecha de nacimiento: Formato de fecha incorrecto';
    }else{
        $fecha = new DateTime($fecha_nac);
        $hoy = new DateTime();
        $fechaMinima = (new DateTime())->sub(new DateInterval('P120Y'));
    
        if ($fecha > $hoy) {
            $errores['fecha_nac'] = '- Fecha de nacimiento: No puede ser superior a la actual';
        } elseif ($fecha < $fechaMinima) {
            $errores['fecha_nac'] = '- Fecha de nacimiento: No puede ser tan antigua';
        }
    }

    if(!preg_match(DIRECCION_REGEX, $direccion)){
        $errores['direccion'] = '- Escriba una dirección válida sin caracteres especiales';
    }

    # Validar la contraseña solo si el usuario ha ingresado una nueva
    if (!empty($new_pass) && !preg_match(PASSWORD_REGEX, $new_pass)) {
        $errores['new_password'] = '- Contraseña: Debe contener al menos 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial';
    }


    return $errores;

}

?>