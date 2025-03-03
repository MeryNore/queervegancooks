<!-- Validación login -->

<?php

# Declaramos como constantes las expresiones regulares que van a filtrar o comprobar los datos
define('PASSWORD_REGEX', '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/');
                      
# Definimos la función que va a validar los datos del formulario de registro
function validar_login($login_mail, $login_password){
    # Declarar un array asociativo que va a contener los errores de validación
    $errores = [];

    # Validación de usuario(dato email), el email tiene su propia validación en PHP
    if(!filter_var($login_mail, FILTER_VALIDATE_EMAIL)){
        $errores['usuario'] = '- Usuario: Debe ser un email válido';
    }

    # Validación de variables haciendo uso de la constante REGEX_PASSWORD
    if(!preg_match(PASSWORD_REGEX, $login_password)){
        $errores['pass'] = '- Contraseña: Debe contener al menos 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial';
    }

    # Devolvemos el array de errores
    return $errores;

}

?>