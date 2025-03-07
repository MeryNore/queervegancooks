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


    # Obtener el nombre de la imagen actual de la base de datos
    $query = "SELECT imagen FROM noticias WHERE idNoticia = ?";
    $stmt = $mysqli_connection->prepare($query);
    $stmt->bind_param("i", $idNoticia);
    $stmt->execute();
    $stmt->bind_result($foto_name);
    $stmt->fetch();
    $stmt->close();


    # Validamos los datos del formulario a travéz de la función validar_registro
    # $errores_validacion = validar_noticias($titulo, $texto, $fecha);


    // Verificar si se ha subido un archivo
    if (isset($_FILES['imagen_noticia']) && $_FILES['imagen_noticia']['error'] == UPLOAD_ERR_OK) {
        $foto = file_get_contents($_FILES['imagen_noticia']['tmp_name']);
    } else {
        $foto = $foto_name; // Mantener la imagen existente si no se ha subido una nueva;
    }


    try{
        # Preparar la sentencía de actualización
        $update_stmt = $mysqli_connection->prepare('UPDATE noticias SET titulo = ?, imagen = ?, texto = ?, fecha = ? WHERE idNoticia = ?');

        if (!$update_stmt) {
            error_log('Error preparando actualización: ' . $mysqli_connection->error);
            return false;
        }else{
             # Vincular los parámetros
            $update_stmt->bind_param('ssssi', $titulo, $foto, $texto, $fecha, $idNoticia);

            # Ejecutamos la sentencia
            if($update_stmt -> execute()){

                $_SESSION['mensaje_exito'] = "¡Los datos se han actualizado correctamente!";
                header('location: ../../../views/views_admins/noticias_admin.php');
                exit();

            # SI no se ha podido ejecutar la sentencia
            }else{
                error_log("Error: No se puede ejecutar la sentencia " . $update_stmt -> error);
                
                $_SESSION['mensaje_error'] = "¡Hubo un error al actualizar los datos!";
                header('location: ../../../views/views_admins/noticias_admin.php');
                exit();
            }
        }

    }catch(Exception $e){
        error_log("Error en c_editar_news.php: " . $e -> getMessage());
        # Redirigimos al usuario a la página de error 500
        header('Location: ../../../views/errors/error500.html');
        exit();
    }finally{
        # Cerrar la sentencia si no lo está
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