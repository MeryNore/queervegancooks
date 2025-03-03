<!-- Controlador que gestiona la edición de Citas -->

<?php
# Vincular los archivos necearios
require_once '../db_conn.php';
require_once '../db_functions.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../validations/v_citas.php';

# Comprobar si existe una sesión activa, si no existe, la vamos a crear/inciar una nueva sesión
if(session_status() == PHP_SESSION_NONE){
    session_start();
}


# EDITAR citas
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_cita'])){
    
    # Primero obtenemos los datos del formulario SANEADOS
    $fecha_cita = htmlspecialchars((string)$_POST["fecha_cita"]); // Convertir a string y sanear
    $motivo_cita = htmlspecialchars($_POST["motivo_cita"]);
    $idCita = htmlspecialchars($_POST["idCita"]);

    # Validamos los datos del formulario a travéz de la función validar_registro
    $errores_validacion = validar_cita($fecha_cita, $motivo_cita);

    # Comprobamos SI se han generado errores de validación (SI el array de validación NO está vacio)
    if(!empty($errores_validacion)){
        # SI hay errores de validación, los guardamos en una variable para mostrarselos al usuario
        $mensaje_error = "";

        # Recorremos el array de errores de validación y los concatenamos en una cadena en la variable mensaje_error
        foreach($errores_validacion as $clave => $mensaje){
            $mensaje_error .= $mensaje . '<br>';
        }

        # Asignamos la cadena de errores a una variable de sesión (La que creamos para los errores $_SESSION['mensaje_error'])
        $_SESSION['mensaje_error'] = $mensaje_error;
        # redirigimos al usuario a la página de citas
        header('Location: ../../views/views_users/citas.php');
        exit();
    }

    # Intentamos realizar la modifición de los datos
    try{
        
        # Declaramos la variable que registrará si se ha producido una excepción durante el proceso que comprueba si el usuario ya tiene una cita agendada
        $exception_error = false;

        # Actualización de los datos en la base de datos
        $result_update = modificarCita($fecha_cita, $motivo_cita, $idCita, $mysqli_connection, $exception_error);

        if($result_update){
            # Actualizar los datos en la sesión en CITAS

            $_SESSION['mensaje_exito'] = "¡Los datos se han actualizado correctamente!";
            header('location: ../../views/views_users/citas.php');
            exit();
        }else{
            $_SESSION['mensaje_error'] = "¡Hubo un error al actualizar los datos!";
            header('location: ../../views/errors/error500.html');
            exit();
        }

    }catch(Exception $e){
        error_log("Error durante el proceso de actualización de datos: " . $e -> getMessage());
        header("Location: ../../views/errors/error500.html");
        exit();
    
    }finally{
        # Cerrar la conexión a la base de datos si aún sigue abierta.
        if(isset($mysqli_connection) && ($mysqli_connection)){
            $mysqli_connection -> close();
        }
    }

}


?>