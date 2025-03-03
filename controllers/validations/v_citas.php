<!-- Validación Citas -->

<?php

# Declaramos como constantes las expresiones regulares que van a filtrar o comprobar los datos
define('FECHA_CITA_REGEX', '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/');
define('MOTIVO_CITA_REGEX', '/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\.\,\/]{3,300}$/');
                      
# Definimos la función que va a validar los datos del formulario de citas
function validar_cita($fecha_cita, $motivo_cita){
    # Declarar un array asociativo que va a contener los errores de validación
    $errores = [];

    # Validación de variables haciendo uso de la constante REGEX_
    if(!preg_match(FECHA_CITA_REGEX, $fecha_cita)){
        $errores['fecha_cita'] = '- Formato de fecha incorrecto';
    }else{
        $fecha = new DateTime($fecha_cita);
        $hoy = new DateTime();
        $fechaMaxima = (new DateTime())->add(new DateInterval('P1Y'));
    
        if ($fecha < $hoy) {
            $errores['fecha_cita'] = '- Sólo puedes solicitar cita a partir de mañana';
        } elseif ($fecha > $fechaMaxima) {
            $errores['fecha_nac'] = '- No puedes solicitar cita con más de un año de antelación';
        }
    }

    # Validar la contraseña solo si el usuario ha ingresado una nueva
    if (!empty($motivo_cita) && !preg_match(MOTIVO_CITA_REGEX, $motivo_cita)) {
        $errores['motivo_cita'] = '- Intenta no escribir caracteres especiales';
    }


    return $errores;

}

?>