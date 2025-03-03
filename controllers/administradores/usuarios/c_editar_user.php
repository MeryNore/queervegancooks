<!-- Controlador que gestiona la edición de Noticias -->

<?php
# Vincular los archivos necearios
require_once '../../db_conn.php';
require_once '../../db_functions.php';
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../validations/v_profile.php';

# Comprobar si existe una sesión activa, si no existe, la vamos a crear/inciar una nueva sesión
if(session_status() == PHP_SESSION_NONE){
    session_start();
}


if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar_usuario'])){

    # Recuperar los datos del formulario
    $nombre = htmlspecialchars($_POST["nombre"]);
    $apellido = htmlspecialchars($_POST["apellido"]);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $telefono = htmlspecialchars((string)$_POST["telefono"]); // Convertir a string y sanear
    $fecha_nac = htmlspecialchars((string)$_POST["fecha_nac"]); // Convertir a string y sanear
    $direccion = htmlspecialchars($_POST["direccion"]);
    $sexo = htmlspecialchars($_POST["sexo"]);
    $rol = htmlspecialchars($_POST['rol']);
    $new_pass = htmlspecialchars($_POST["user_password"]);
    $idUser = htmlspecialchars($_POST["idUser"]);


    # Validamos los datos del formulario a travéz de la función validar_profile
    #$errores_validacion = validar_profile($nombre, $apellido, $email, $telefono, $fecha_nac, $direccion, $sexo, $rol, $new_pass);


    # Obtener la contraseña actual del usuario desde la base de datos
    $user = $email;
    $user_login = getUserByEmail($user, $mysqli_connection, $exception_error);
    $old_password = $user_login['user_password'];  # Contraseña encriptada almacenada en la BBDD
    # Si el usuario ingresó una nueva contraseña, la encriptamos. Si no, mantenemos la anterior.
    $hashed_password = !empty($new_pass) ? password_hash($new_pass, PASSWORD_BCRYPT) : $old_password;

    #Intentamos modificar los datos en ambas tablas
    try{
        # Preparar la SENTENCIA para ACTUALIZAR los datos del usuario a través del ID en la tabla USERS_DATA
        $update_stmt = $mysqli_connection -> prepare('UPDATE users_data SET nombre = ?, apellido = ?, email = ?, telefono = ?, fecha_nac = ?, direccion = ?, sexo = ? WHERE idUser = ?');

        if(!$update_stmt){
            error_log("No se pudo preparar la sentencia " . $mysqli_connection -> error);
            header('location: ../../../views/errors/error500.html');
            exit();
        }else{
            $update_stmt -> bind_param("sssisssi", $nombre, $apellido, $email, $telefono, $fecha_nac, $direccion, $sexo, $idUser);

            if($update_stmt -> execute()){

                #Cerramos la sentencia
                $update_stmt -> close();

                ########################################################################################
                ########################################################################################
                ########################################################################################

                # Preparar la SENTENCIA para ACTUALIZAR los datos del usuario a través del ID en la tabla USERS_LOGIN
                $update_login_stmt = $mysqli_connection -> prepare('UPDATE users_login SET usuario = ?, user_password = ?, rol = ? WHERE idUser = ?');

                if(!$update_login_stmt){
                    error_log("No se pudo preparar la sentencia update_login_stmt" . $mysqli_connection -> error);
                    header('location: ../../../views/errors/error500.html');
                    exit();
                }else{
                    $update_login_stmt -> bind_param("sssi", $email, $hashed_password, $rol, $idUser);
            
                    if($update_login_stmt -> execute()){
                        
                        $update_login_stmt -> close();

                        $_SESSION['mensaje_exito'] = "¡Los datos se han actualizado correctamente!";
                        header('location: ../../../views/views_admins/usuarios_admin.php');
                        exit();

                    }else{
                        error_log("Error: No se puede ejecutar la sentencia update_login_stmt" . $update_login_stmt -> error);
                        header('location: ../../../views/errors/error500.html');
                        exit();
                    }
                }

                ########################################################################################
                ########################################################################################
                ########################################################################################
            
            # SI no se ha podido ejecutar la sentencia
            }else{
                error_log("Error: No se puede ejecutar la sentencia " . $update_stmt -> error);
                header('location: ../../../views/errors/error500.html');
                exit();
            }
        } 
    
    }catch(Exception $e){
        error_log("Error durante el proceso de actualización de datos: " . $e -> getMessage());
        header("Location: ../../../views/errors/error500.html");
        exit();
    }finally{
        # Cerrar la conexión a la base de datos si aún sigue abierta.
        if(isset($mysqli_connection) && ($mysqli_connection)){
            $mysqli_connection -> close();
        }
    }

}

?>