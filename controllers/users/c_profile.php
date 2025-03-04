<!-- Controlador que gestiona el Perfil -->

<?php
    # Vinculamos los archivos necesarios
    require_once '../db_conn.php';
    require_once '../db_functions.php';
    require_once __DIR__ . '/../../config/config.php';
    require_once __DIR__ . '/../validations/v_profile.php';

    # Comprobar si existe una sesión activa y en caso de que no sea así la creamos
    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }

    # Comprobar si hemos recibido los datos para actualizar el formulario
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar_datos'])){
    
        # Recuperar los datos del formulario
        $nombre = htmlspecialchars($_POST["nombre"]);
        $apellido = htmlspecialchars($_POST["apellido"]);
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $telefono = htmlspecialchars((string)$_POST["telefono"]); // Convertir a string y sanear
        $fecha_nac = htmlspecialchars((string)$_POST["fecha_nac"]); // Convertir a string y sanear
        $direccion = htmlspecialchars($_POST["direccion"]);
        $sexo = htmlspecialchars($_POST["sexo"]);
        $new_pass = htmlspecialchars($_POST["user_password"]);

        # Recuperar el idUser del usuario de la sesión
        if (!isset($_SESSION['user_login']['idUser'])) {
            $_SESSION['mensaje_error'] = "Error de sesión. Inicia sesión nuevamente.";
            header('Location: ../../views/login.php');
            exit();
        }
        $idUser = $_SESSION['user_login']['idUser'];


        # Validamos los datos del formulario a travéz de la función validar_profile
        $errores_validacion = validar_profile($nombre, $apellido, $email, $telefono, $fecha_nac, $direccion, $new_pass);
        
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
            # redirigimos al usuario a la página de registro
            header('location: ../../views/views_compartidas/profile.php');
            exit();
        }

        # Obtener la contraseña actual del usuario desde la base de datos
        $user = $_SESSION['user_login']['usuario'];
        $user_login = getUserByEmail($user, $mysqli_connection, $exception_error);
        $old_password = $user_login['user_password'];  # Contraseña encriptada almacenada en la BBDD

        # Si el usuario ingresó una nueva contraseña, la encriptamos. Si no, mantenemos la anterior.
        $hashed_password = !empty($new_pass) ? password_hash($new_pass, PASSWORD_BCRYPT) : $old_password;


        try{
            # Inicializamos una variable para guardar los errores de excepcion posibles
            $exception_error = false;

            # Actualización de los datos del usuario en la base de datos
            $result_update = updateUserData($idUser, $nombre, $apellido, $email, $telefono, $fecha_nac, $direccion, $sexo, $hashed_password, $mysqli_connection, $exception_error);

            if($result_update){
                # Actualizar los datos en la sesión tabla USERS_DATA
                $_SESSION['user_data']['nombre'] = $nombre;
                $_SESSION['user_data']['apellido'] = $apellido;
                $_SESSION['user_data']['email'] = $email;
                $_SESSION['user_data']['telefono'] = $telefono;
                $_SESSION['user_data']['fecha_nac'] = $fecha_nac;
                $_SESSION['user_data']['direccion'] = $direccion;
                $_SESSION['user_data']['sexo'] = $sexo;
                
                # Actualizar los datos en la sesión tabla USERS_LOGIN
                $_SESSION['user_login']['usuario'] = $email;

                
                $_SESSION['mensaje_exito'] = "¡Los datos se han actualizado correctamente!";
                header('location: ../../views/views_compartidas/profile.php');
                exit();
            }else{
                $_SESSION['mensaje_error'] = "¡Hubo un error al actualizar los datos!";
                header('location: ../../views/views_compartidas/profile.php');
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