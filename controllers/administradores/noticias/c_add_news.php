<?php
# Vincular los archivos necearios
require_once '../../db_conn.php';
require_once '../../db_functions.php';
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../validations/v_news.php';

# Comprobar si existe una sesión activa, si no existe, la vamos a crear/inciar una nueva sesión
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

# Realizamos los registros de datos. Primero miramos si el método de envío que se está utilizando es POST y si el botón que se ha presionado para enviar el formulario es el correcto
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['guardar_noticia'])){

    $titulo = htmlspecialchars($_POST["titulo_noticia"]);
    $texto = htmlspecialchars($_POST["texto_noticia"]);
    $fecha = htmlspecialchars((string)$_POST["fecha_noticia"]); // Convertir a string y sanear
    $foto = $_FILES['imagen_noticia'];


    # Subir la imagen al servidor
    $target_dir = "../../../assets/images/uploads/";
    $target_file = $target_dir . basename($foto["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    

    # Recuperar el idUser del usuario de la sesión
    if (!isset($_SESSION['user_login']['idUser'])) {
        $_SESSION['mensaje_error'] = "Error de sesión. Inicia sesión nuevamente.";
        header('Location: ../../../views/views_admins/noticias_admin.php');
        exit();
    }
    # Añadimos el IdUser en una variable
    $idUser = $_SESSION['user_login']['idUser'];

    
    # Validamos los datos del formulario a travéz de la función validar_registro
    $errores_validacion = validar_noticias($titulo, $texto, $fecha, $foto, $target_file, $imageFileType);
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
        # redirigimos al usuario a la página de noticias
        header('Location: ../../../views/views_admins/noticias_admin.php');
        exit();
    }else{
        if (move_uploaded_file($foto["tmp_name"], $target_file)) {
            $foto_name = basename($target_file); // Extraer solo el nombre del archivo
        } else {
            $_SESSION['mensaje_error'] = "Lo siento, hubo un error al subir tu archivo.";
            header('Location: ../../../views/views_admins/noticias_admin.php');
            exit();
        }
    }


    try{
        $insert_stmt = null;
        $exception_error = false;

        # Comprobamos que NO exista otra noticia con el  mismo titulo
        if(check_title($titulo, $mysqli_connection, $exception_error)){
            # Establecemos un mensaje de error (variable de sesión)
            $_SESSION['mensaje_error'] = 'ERROR: Ya tiene una noticia con ese titulo';
            
            # Redirigimos al usuario a la página de noticias
            header('Location: ../../../views/views_admins/noticias_admin.php');
            exit();
        }else{
            # SI se produjo una excepción durante el proceso de comprobación
            if($exception_error  == true){
                # Se redirige al usuario a la página de error 500
                header('Location: ../../../views/errors/error500.html');
                exit();
            }else{
                # Se prepara la sentencia SQL para realizar la inserción de la cita
                $insert_stmt = $mysqli_connection -> prepare('INSERT INTO noticias (titulo, imagen, texto, fecha, idUser) VALUES (?, ?, ?, ?, ?)');

                # SI la sentencia NO se ha podido preparar
                if(!$insert_stmt){
                    # Se guarda el error de preparación de la sentencia
                    error_log('No se pudo preparar la sentencia ' . $mysqli_connection -> error);

                    # Se redirige al usuario a la página de error 500
                    header('Location: ../../../views/errors/error500.html');
                    exit();
                # SI la sentencia se ha podido preparar
                }else{
                    # Vinculamos los valores instroducidos por el usuario a los valores de la sentancia de inserción
                    $insert_stmt -> bind_param('ssssi', $titulo, $foto_name, $texto, $fecha, $idUser);

                    # SI la sentencia se ha podido ejecutar
                    if($insert_stmt -> execute()){
                        
                        # Cerramos la sentencia
                        $insert_stmt -> close();

                        # Configuramos un mensaje de éxito para el usuario y le redirigimos a la misma página de noticias
                        $_SESSION['mensaje_exito'] = 'EXITO: Noticia guardada correctamente';
                        header('Location: ../../../views/views_admins/noticias_admin.php');
                        exit();

                    #SI NO se ha posidio ejecutrar la sentencia
                    }else{
                        $_SESSION['mensaje_error'] = 'ERROR: hubo un problema al guardar la noticia';
                        header('Location: ../../../views/views_admins/noticias_admin.php');
                        exit();
                    }
                }
            }
        }
    }catch(Exception $e){
        # Registramos la excepción en el error_log
        error_log('Error en c_registro.php ' . $e -> getMessage());
        # Redirigimos al usuario a la página de error 500
        header('Location: ../../../views/errors/error500.html');
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