<?php
# Vincular los archivos necearios
require_once '../../db_conn.php';
require_once '../../db_functions.php';
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../validations/v_registro.php';

# Comprobar si existe una sesión activa, si no existe, la vamos a crear/inciar una nueva sesión
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

# Realizamos los registros de datos. Primero miramos si el método de envío que se está utilizando es POST y si el botón que se ha presionado para enviar el formulario es el correcto
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['guardar_usuario'])){

    # Primero obtenemos los datos del formulario SANEADOS
    $nombre = htmlspecialchars($_POST["nombre"]);
    $apellido = htmlspecialchars($_POST["apellido"]);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $telefono = htmlspecialchars((string)$_POST["telefono"]); // Convertir a string y sanear
    $fecha_nac = htmlspecialchars((string)$_POST["fecha_nac"]); // Convertir a string y sanear
    $direccion = htmlspecialchars($_POST["direccion"]);
    $sexo = htmlspecialchars($_POST["sexo"]);
    $pass = htmlspecialchars($_POST["user_password"]);
    $rol = htmlspecialchars($_POST["rol"]);
    

    # Validamos los datos del formulario a travéz de la función validar_registro
    #$errores_validacion = validar_registro($nombre, $apellido, $email, $telefono, $fecha_nac, $direccion, $pass);
    
    
    #Hasheamos la contraseña para encriptarla
    $hashed_password = password_hash($pass, PASSWORD_BCRYPT);


    # Intentamos realizar un registro de usuario
    try{
        # Declaramos la variable que registrará si se ha producido una excepción durante el proceso que comprueba si el usuario ya existe en la BBDD
        $exception_error = false;

        # Si el regultado de check_user es TRUE (ya existe el usuario)
        if(check_user($email, $mysqli_connection, $exception_error)){
            # Establecemos un mensaje de error (variable de sesión)
            $_SESSION['mensaje_error'] = 'ERROR: El usuario ya existe en la base de datos';
            
            # Redirigimos al usuario a la página de registro
            header('Location: ../../../views/views_admins/usuarios_admin.php');
            exit();
        
        # Si el resultado de check_user es FALSE (el usuario no existe)
        }else{

            # Se se produjo una excepción durante el proceso de comprobación
            if($exception_error  == true){
                # Se redirige al usuario a la página de error 500
                header('Location: ../../../views/errors/error500.html');
                exit();
            # SI el usuario NO existe
            }else{
                # Se prepara la sentencia SQL para realizar la inserción
                $insert_stmt = $mysqli_connection -> prepare('INSERT INTO users_data (nombre, apellido, email, telefono, fecha_nac, direccion, sexo) VALUES (?, ?, ?, ?, ?, ?, ?)');

                # SI la sentencia NO se ha podido preparar
                if(!$insert_stmt){
                    # Se guarda el error de preparación de la sentencia
                    error_log('No se pudo preparar la sentencia ' . $mysqli_connection -> error);

                    # Se redirige al usuario a la página de error 500
                    header('Location: ../../../views/errors/error500.html');
                # SI la sentencia se ha podido preparar
                }else{
                    # Vinculamos los valores instroducidos por el usuario a los valores de la sentancia de inserción
                    $insert_stmt -> bind_param('sssisss', $nombre, $apellido, $email, $telefono, $fecha_nac, $direccion, $sexo);

                    # SI la sentencia se ha podido ejecutar
                    if($insert_stmt -> execute()){

                        # Obtenemos el ID del usuario que acabamos de insertar
                        $idUser = $mysqli_connection -> insert_id;
                        
                        # Cerramos la sentencia
                        $insert_stmt -> close();


                        ################################################################################################
                        ################################################################################################
                        ################################################################################################
                  
                        # AÑADIR DATOS EN OTRA TABLA: Preparamos la sentencia para insertar en USERS_LOGIN
                        $insert_login_stmt = $mysqli_connection -> prepare('INSERT INTO users_login (idUser, usuario, user_password, rol) VALUES (?, ?, ?, ?)');
                        # SI la sentencia LOGIN NO se ha podido preparar
                        if(!$insert_login_stmt){
                            # Se guarda el error de preparación de la sentencia
                            error_log('No se pudo preparar la sentencia insert_login_stmt ' . $mysqli_connection -> error);
                            # Se redirige al usuario a la página de error 500
                            header('Location: ../../../views/errors/error500.html');
                        # SI la sentencia LOGIN se ha podido preparar
                        }else{
                            # Vinculamos los valores instroducidos por el usuario a los valores de la sentancia de inserción LOGIN
                            $insert_login_stmt -> bind_param('isss', $idUser, $email, $hashed_password, $rol);
                            # SI la sentencia LOGIN se ha podido ejecutar
                            if($insert_login_stmt -> execute()){
                                # Cerramos la sentencia LOGIN
                                $insert_login_stmt -> close();
                                # Configuramos un mensaje de éxito para el usuario y le redirigimos a la misma página de registro
                                $_SESSION['mensaje_exito'] = 'EXITO: Usuario registrado correctamente';
                                header('Location: ../../../views/views_admins/usuarios_admin.php');
                                exit();
                            # SI NO se ha posidio ejecutrar la sentencia LOGIN
                            }else{
                                # Se guarda el error de ejecución en el error_log
                                error_log('Error: ' . $insert_login_stmt -> error);
                                # Redirigimos al usuario a la página de error 500
                                header('Location: ../../../views/errors/error500.html');
                                exit();
                            }
                        }
                        
                        ################################################################################################
                        ################################################################################################
                        ################################################################################################


                    #SI NO se ha posidio ejecutrar la sentencia
                    }else{
                        # Se guarda el error de ejecución en el error_log
                        error_log('Error: ' . $insert_stmt -> error);
                        # Redirigimos al usuario a la página de error 500
                        header('Location: ../../../views/errors/error500.html');
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
        header('Location: ../../../views/errors/error500.html');

    # Independientemente de si se genera una excepción o no al final siempre se realizará el siguiente código
    }finally{
        # Cerramos la consulta si aún sigue abierta
        if($insert_stmt !== null){
            $insert_stmt -> close();
        }

        # Cerramos la consulta LOGIN si aún sigue abierta
        if($insert_login_stmt !== null){
            $insert_login_stmt -> close();
        }

        # Cerramos la conexión con la BBDD si aún sigue abierta
        if(isset($mysqli_connection) && ($mysqli_connection)){
            $mysqli_connection -> close();
        }
    }
}


?>