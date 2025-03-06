<!-- Controlador que gestiona el login -->

<?php
# Vinculamos los archivos necesarios
require_once 'db_conn.php';
require_once 'db_functions.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/validations/v_login.php';

# Comprobar si existe una sesión activa, si no existe, la vamos a crear/inciar una nueva sesión
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

# Cpmprobamos los datos enviamos desde el LOGIN. Primero miramos si el método de envío que se está utilizando es POST y si el botón que se ha presionado para enviar el formulario es el correcto (login)
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])){
    
    # Primero obtenemos los datos del formulario SANEADOS
    $login_mail = filter_input(INPUT_POST, 'user_mail', FILTER_SANITIZE_EMAIL);
    $login_password = htmlspecialchars($_POST['user_password']);

    # Validamos los datos del formulario a travéz de la función validar_login
    $errores_validacion = validar_login($login_mail, $login_password);

    # Comprobamos SI se han generado errores de validación (SI el array de validación NO está vacio)
    if(!empty($errores_validacion)){
        # Si hay errores los guardamos en una cadena de caracteres que mostraremos al usuario
        $mensaje_error = "";

        # Recorremos el array de errores de validación y los concatenamos en una cadena en la variable mensaje_error
        foreach($errores_validacion as $clave => $mensaje){
            $mensaje_error .= $mensaje . "<br>";
        }

        # Asignamos la cadena de errores a una variable de sesión (La que creamos para los errores $_SESSION['mensaje_error'])
        $_SESSION['mensaje_error'] = $mensaje_error;
        header("Location: ../views/login.php");
        exit();
    }


    // Intetamos comprobar el inicio de sesión
    try{
        # Declaramos la variable que registrará si se ha producido una excepción durante el proceso que comprueba si el usuario ya existe en la BBDD
        $exception_error = false;

        # Buscamos el usuario en la tabla USERS_LOGIN
        $user_login = getUserByEmail($login_mail, $mysqli_connection, $exception_error);

        # Comprobar si se ha capturado alguna excepción
        if($exception_error){
            # Redirigimos a la página de error que tengamos configurada
            $_SESSION['mensaje_error'] = "Error al buscar el usuario. Inténtelo de nuevo más tarde. Si el error continúa contacte con el equipo de soporte";
            header("Location: ../views/errors/error500.html");
            exit();
        }

        # Comprobar si hemos encontrado al usuario (si $user_login tiene contenido)
        if($user_login){
            # Verificar si la contraseña faciltiada por el usuario en el formulario coincide con la de la BBDD
            if(password_verify($login_password, $user_login['user_password'])){

                # Establecer las variables de sesión y redirigir al usuario 
                # Podemos guardar TODOS los datos en una sola variable (para acceder: $_SESSION['user_login']['idLogin'])
                $_SESSION['user_login'] = $user_login;
                /*# O podemos guardarlos uno a uno
                $_SESSION['id_login'] = $user_login['idLogin'];
                $_SESSION['id_user'] = $user_login['idUser'];
                $_SESSION['user_name'] = $user_login['usuario'];
                $_SESSION['rol'] = $user_login['rol'];*/
                

                # Obtenermo el resto de datos del usuario desde USERS_DATA
                $user_data = getUserDatesById($user_login['idUser'], $mysqli_connection, $exception_error);
                    if($user_data && !$exception_error){
                        # Guardamos las variables de sesión con los datos
                        $_SESSION['user_data'] = $user_data;
                    }else{
                        $_SESSION['mensaje_error'] = "No se encontró un usuario con ese ID";
                        header("Location: ../views/login.php");
                        exit();
                    }
                


                # Redirigimos al usuaio a la pagina del Perfil
                header('Location: ../index.php');
                exit();

            }else{
                # Si la contraseña no coincide o existe otro error, establecemos un mensaje de error
                $_SESSION['mensaje_error'] = "La contraseña no es correcta";
                header("Location: ../views/login.php");
                exit();
            }
        }else{
            # Si no se encuentra el usuario o no existe, establecemos un mensaje de error
            $_SESSION['mensaje_error'] = "No se encontro un usuario con ese correo electrónico";
            header("Location: ../views/login.php");
            exit();
        }

    }catch(Exception $e){
        error_log("Error durante el proceso de inicio de sesión: " . $e -> getMessage());
        header("Location: ../views/errors/error500.html");
        exit();
    
    }finally{
        # Cerrar la conexión a la base de datos si aún sigue abierta. Es como decir, (si está tablecida la conexión) y (existe la conexión) entonces cierra la conexión
        if(isset($mysqli_connection) && ($mysqli_connection)){
            $mysqli_connection -> close();
        }
    }

}



?>
