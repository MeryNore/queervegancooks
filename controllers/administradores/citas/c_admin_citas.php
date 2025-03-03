<?php
# Vincular los archivos necearios
require_once '../../db_conn.php';
require_once '../../db_functions.php';
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../validations/v_citas.php';

# Comprobar si existe una sesión activa, si no existe, la vamos a crear/inciar una nueva sesión
if(session_status() == PHP_SESSION_NONE){
    session_start();
}


if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_cita'])){

    # Primero obtenemos los datos del formulario SANEADOS
    $idUser = htmlspecialchars($_POST["user_date"]);
    $fecha_cita = htmlspecialchars((string)$_POST["fecha_cita"]); // Convertir a string y sanear
    $motivo_cita = htmlspecialchars($_POST["motivo_cita"]);
    $idCita = htmlspecialchars($_POST['idCita']);
    
    # Validamos los datos del formulario
    #$errores_validacion = validar_citas($fecha, $texto, $fecha);

    
    try{
        $insert_stmt = null;
        $exception_error = false;

        if($exception_error  == true){
            header('Location: ../../../views/errors/error500.html');
            exit();
        }else{
            # Se prepara la sentencia SQL para realizar la inserción de la cita
            $update_stmt = $mysqli_connection -> prepare('UPDATE citas SET idUser = ?, fecha_cita = ?, motivo_cita = ? WHERE idCita = ?');

            if(!$update_stmt){
                # Se guarda el error de preparación de la sentencia
                error_log('No se pudo preparar la sentencia ' . $mysqli_connection -> error);
                header('Location: ../../../views/errors/error500.html');
                exit();
            }else{
                # Vinculamos los valores instroducidos por el usuario a los valores de la sentancia de inserción
                $update_stmt -> bind_param('issi', $idUser, $fecha_cita, $motivo_cita, $idCita);

                # SI la sentencia se ha podido ejecutar
                if($update_stmt -> execute()){
                    $update_stmt -> close();

                    $_SESSION['mensaje_exito'] = '¡Los datos se han actualizado correctamente!';
                    header('Location: ../../../views/views_admins/citas_admin.php');
                    exit();
                }else{
                    $_SESSION['mensaje_error'] = "¡Hubo un error al actualizar los datos!";
                    header('Location: ../../../views/views_admins/citas_admin.php');
                    exit();
                }
            }
        }
    }catch(Exception $e){
        error_log('Error en c_admin_citas.php ' . $e -> getMessage());
        header('Location: ../../../views/errors/error500.html');
        exit();
    }finally{
        if($update_stmt !== null){
            $update_stmt -> close();
        }
        # Cerramos la conexión con la BBDD si aún sigue abierta
        if(isset($mysqli_connection) && ($mysqli_connection)){
            $mysqli_connection -> close();
        }
    }
}


?>