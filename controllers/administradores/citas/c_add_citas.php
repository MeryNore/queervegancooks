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


if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agendar_cita'])){

    # Primero obtenemos los datos del formulario SANEADOS
    $idUser = htmlspecialchars($_POST["user_date"]);
    $fecha_cita = htmlspecialchars((string)$_POST["fecha_cita"]); // Convertir a string y sanear
    $motivo_cita = htmlspecialchars($_POST["motivo_cita"]);
    
    # Validamos los datos del formulario
    #$errores_validacion = validar_citas($fecha, $texto, $fecha);

    
    try{
        $insert_stmt = null;
        $exception_error = false;

        # Comprobamos que NO exista otra CITA con los mismos datos
        if(check_cita($idUser, $fecha_cita, $mysqli_connection, $exception_error)){
            $_SESSION['mensaje_error'] = 'ERROR: Ya tiene una cita agendada este día';
            header('Location: ../../../views/views_admins/citas_admin.php');
            exit();
        }else{
            if($exception_error  == true){
                header('Location: ../../../views/errors/error500.html');
                exit();
            }else{
                # Se prepara la sentencia SQL para realizar la inserción de la cita
                $insert_stmt = $mysqli_connection -> prepare('INSERT INTO citas (idUser, fecha_cita, motivo_cita) VALUES (?, ?, ?)');

                if(!$insert_stmt){
                    # Se guarda el error de preparación de la sentencia
                    error_log('No se pudo preparar la sentencia ' . $mysqli_connection -> error);
                    header('Location: ../../../views/errors/error500.html');
                    exit();
                }else{
                    # Vinculamos los valores instroducidos por el usuario a los valores de la sentancia de inserción
                    $insert_stmt -> bind_param('iss', $idUser, $fecha_cita, $motivo_cita);

                    # SI la sentencia se ha podido ejecutar
                    if($insert_stmt -> execute()){
                        $insert_stmt -> close();

                        $_SESSION['mensaje_exito'] = 'EXITO: Cita agendada correctamente';
                        header('Location: ../../../views/views_admins/citas_admin.php');
                        exit();
                    }else{
                        error_log('Error: ' . $insert_stmt -> error);
                        header('Location: ../../../views/errors/error500.html');
                        exit();
                    }
                }
            }
        }
    }catch(Exception $e){
        error_log('Error en c_add_citas.php ' . $e -> getMessage());
        header('Location: ../../../views/errors/error500.html');
        exit();
    }finally{
        if($insert_stmt !== null){
            $insert_stmt -> close();
        }
        # Cerramos la conexión con la BBDD si aún sigue abierta
        if(isset($mysqli_connection) && ($mysqli_connection)){
            $mysqli_connection -> close();
        }
    }
}


?>