<!-- Controlador que gestiona la agenda de Citas -->

<?php
    # Vinculamos los archivos necesarios
    require_once '../db_conn.php';
    require_once '../db_functions.php';
    require_once __DIR__ . '/../../config/config.php';
    require_once __DIR__ . '/../validations/v_citas.php';

    # Comprobar si existe una sesión activa, si no existe, la vamos a crear/inciar una nueva sesión
    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }

    # AGENDAR citas
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agendar_cita'])){

        # Primero obtenemos los datos del formulario SANEADOS
        $fecha_cita = htmlspecialchars((string)$_POST["fecha_cita"]); // Convertir a string y sanear
        $motivo_cita = htmlspecialchars($_POST["motivo_cita"]);


        # Recuperar el idUser del usuario de la sesión
        if (!isset($_SESSION['user_login']['idUser'])) {
            $_SESSION['mensaje_error'] = "Error de sesión. Inicia sesión nuevamente.";
            header('Location: ../../views/views_users/citas.php');
            exit();
        }
        # Añadimos el IdUser en una variable
        $idUser = $_SESSION['user_login']['idUser'];
        

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

        # Intentamos realizar un registro de cita
        try{

            $insert_stmt = null;

            # Declaramos la variable que registrará si se ha producido una excepción durante el proceso que comprueba si el usuario ya tiene una cita agendada
            $exception_error = false;

            # Si el resultado de check_cita es TRUE (ya existe una cita agendada ese día)
            if(check_cita($idUser, $fecha_cita, $mysqli_connection, $exception_error)){
                # Establecemos un mensaje de error (variable de sesión)
                $_SESSION['mensaje_error'] = 'ERROR: Ya tiene una cita agendada este día';
                
                # Redirigimos al usuario a la página de citas
                header('Location: ../../views/views_users/citas.php');
                exit();
            
            # Si el resultado de check_user es FALSE (NO tiene citas agendadas ese día)
            }else{

                # Se se produjo una excepción durante el proceso de comprobación
                if($exception_error  == true){
                    # Se redirige al usuario a la página de error 500
                    header('Location: ../../views/errors/error500.html');
                    exit();
                # SI el usuario NO tiene cita agendada ese día
                }else{
                    # Se prepara la sentencia SQL para realizar la inserción de la cita
                    $insert_stmt = $mysqli_connection -> prepare('INSERT INTO citas (idUser, fecha_cita, motivo_cita) VALUES (?, ?, ?)');

                    # SI la sentencia NO se ha podido preparar
                    if(!$insert_stmt){
                        # Se guarda el error de preparación de la sentencia
                        error_log('No se pudo preparar la sentencia ' . $mysqli_connection -> error);

                        # Se redirige al usuario a la página de error 500
                        header('Location: ../../views/errors/error500.html');
                        exit();
                    # SI la sentencia se ha podido preparar
                    }else{
                        # Vinculamos los valores instroducidos por el usuario a los valores de la sentancia de inserción
                        $insert_stmt -> bind_param('iss', $idUser, $fecha_cita, $motivo_cita);

                        # SI la sentencia se ha podido ejecutar
                        if($insert_stmt -> execute()){
                            
                            # Cerramos la sentencia
                            $insert_stmt -> close();

                            # Configuramos un mensaje de éxito para el usuario y le redirigimos a la misma página de citas
                            $_SESSION['mensaje_exito'] = 'EXITO: Cita agendada correctamente';
                            header('Location: ../../views/views_users/citas.php');
                            exit();

                        #SI NO se ha posidio ejecutrar la sentencia
                        }else{
                            # Se guarda el error de ejecución en el error_log
                            error_log('Error: ' . $insert_stmt -> error);
                            # Redirigimos al usuario a la página de error 500
                            header('Location: ../../views/errors/error500.html');
                            exit();
                        }
                    }
                }
            }
        # SI durante el proceso de registro se ha producido una excepción
        }catch(Exception $e){
            # Registramos la excepción en el error_log
            error_log('Error en c_registro.php ' . $e -> getMessage());
            # Redirigimos al usuario a la página de error 500
            header('Location: ../../views/errors/error500.html');
            exit();

        # Independientemente de si se genera una excepción o no al final siempre se realizará el siguiente código
        }finally{
            # Cerramos la consulta si aún sigue abierta
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