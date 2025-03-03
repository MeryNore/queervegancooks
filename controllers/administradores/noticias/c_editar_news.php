<!-- Controlador que gestiona la edición de Noticias -->

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
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_noticia'])){

    $titulo = htmlspecialchars($_POST["titulo_noticia"]);
    $texto = htmlspecialchars($_POST["texto_noticia"]);
    $fecha = htmlspecialchars((string)$_POST["fecha_noticia"]); // Convertir a string y sanear
    $idNoticia = htmlspecialchars($_POST['idNoticia']);

   
    # Validamos los datos del formulario a travéz de la función validar_registro
    $errores_validacion = validar_noticias($titulo, $texto, $fecha);
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
    }

    try{
        $exception_error = false;

        # SI se produjo una excepción durante el proceso de comprobación
        if($exception_error  == true){
            # Se redirige al usuario a la página de error 500
            header('Location: ../../../views/errors/error500.html');
            exit();
        }else{
            # Actualización de los datos en la base de datos
            $result_update = modificarNoticia($titulo, $texto, $fecha, $idNoticia, $mysqli_connection, $exception_error);

            if($result_update){
                # Actualizar los datos en la sesión en NOTICIAS

                $_SESSION['mensaje_exito'] = "¡Los datos se han actualizado correctamente!";
                header('location: ../../../views/views_admins/noticias_admin.php');
                exit();
            }else{
                $_SESSION['mensaje_error'] = "¡Hubo un error al actualizar los datos!";
                header('location: ../../../views/views_admins/noticias_admin.php');
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