<!-- Controlador que gestiona la eliminación de NOTICIAS -->

<?php
# Vincular los archivos necearios
require_once '../../db_conn.php';
require_once __DIR__ . '/../../../config/config.php';

# Comprobar si existe una sesión activa, si no existe, la vamos a crear/inciar una nueva sesión
if(session_status() == PHP_SESSION_NONE){
    session_start();
}


# ELIMINAR NOTICIAS
if(isset($_GET['borrar_noticia'])){
    
    # Recuperamos los datos de la base de datos
    $idNoticia = $_GET['borrar_noticia'];
 
    # Intentamos realizar la ELIMINACIÓN de los datos
    try{
        $exception_error = false;

        # Recuperar la ruta de la foto desde la base de datos
        $select_stmt = $mysqli_connection->prepare('SELECT imagen FROM noticias WHERE idNoticia = ?');
        $select_stmt->bind_param('i', $idNoticia);
        $select_stmt->execute();
        $select_stmt->bind_result($nombre_imagen);
        $select_stmt->fetch();
        $select_stmt->close();

        # Construir la ruta completa de la imagen
        $ruta_imagen = __DIR__ . '/../../../assets/images/uploads/' . $nombre_imagen;

        # Eliminar la foto de la carpeta uploads
        if(file_exists($ruta_imagen)){
            unlink($ruta_imagen);
        }
       
        # eliminar los datos en la base de datos
        $delete_stmt = $mysqli_connection->prepare('DELETE FROM noticias WHERE idNoticia = ?');

        $delete_stmt->bind_param('i', $idNoticia);

        if($delete_stmt->execute()){
            $_SESSION['mensaje_exito'] = "Los datos se han eliminado correctamente";
            header('location: ../../../views/views_admins/noticias_admin.php');
            exit();
        }else{
            $_SESSION['mensaje_error'] = "Hubo un error al eliminar los datos";
            header('location: ../../../views/views_admins/noticias_admin.php');
            exit();
        }

        $delete_stmt->close();
        header('location: ../../../views/views_admins/noticias_admin.php');
        exit();
    
    }catch(Exception $e){
        error_log("Error durante el proceso de eliminación de datos: " . $e -> getMessage());
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